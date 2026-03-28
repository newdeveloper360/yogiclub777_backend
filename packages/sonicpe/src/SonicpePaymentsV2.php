<?php

namespace sonicpe\paymentV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use sonicpe\paymentV2\Exception\sonicpeSdkException;

include_once base_path('packages/sonicpe/src/SonicpeUtil.php');

class sonicpePaymentsV2
{
    private $merchantId;
    private $accessToken;
    private $encKey;
    private $options;
    private $shippingAddress;
    private $billingAddress;
    private $successUrl;
    private $failureUrl;
    private $callbackUrl;
    private $customer;
    private $customData;
    private $theme;
    private $paymentOptions;

    private $util;

    public function __construct($merchantId, $accessToken, $apiSecret, $options = null)
    {
        $this->merchantId = $merchantId;
        $this->accessToken = $accessToken;
        $this->encKey = $apiSecret;
        $this->options = $options;
        $this->util = new sonicpeUtil($options);
    }

    public function addShippingAddress($address, $city, $state, $country, $pinCode)
    {
        $this->shippingAddress = [
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'pin_code' => $pinCode,
        ];
    }

    public function addBillingAddress($address, $city, $state, $country, $pinCode)
    {
        $this->billingAddress = [
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'pin_code' => $pinCode,
        ];
    }

    public function addCustomerInfo($name, $email, $mobile)
    {
        $this->customer = [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile
        ];
    }

    public function addCustomParameter($param1, $param2 = null, $param3 = null, $param4 = null, $param5 = null)
    {
        $this->customData = [
            'data1' => $param1
        ];

        if (isset($param2)) $this->customData['data2'] = $param2;
        if (isset($param3)) $this->customData['data3'] = $param3;
        if (isset($param4)) $this->customData['data4'] = $param4;
        if (isset($param5)) $this->customData['data5'] = $param5;
    }

    public function setResponseHandler($successUrl, $failureUrl, $callbackUrl = null)
    {
        $this->successUrl = $successUrl;
        $this->failureUrl = $failureUrl;
        $this->callbackUrl = $callbackUrl;
    }

    public function setTheme($color, $logoUrl)
    {
        $this->theme = [
            'theme_color' => $color,
            'theme_logo' => $logoUrl,
        ];
    }

    public function setPaymentOptions($paymentOptions)
    {
        $this->paymentOptions = $paymentOptions;
    }

    /**
     * @param $orderId
     * @param $productType
     * @param $productName
     * @param $amount
     * @param $currency
     * @param $storeId
     * @return mixed
     * @throws sonicpeSdkException
     */
    public function TransactionInit($orderId, $productType, $productName, $amount, $currency, $storeId)
    {
        // Prepare Request
        $order = [
            'product_name' => $productName,
            'product_type' => $productType,
            'currency' => $currency,
            'amount' => $amount,
            'order_id' => $orderId,
            'store_id' => $storeId
        ];

        if (isset($this->customer) && sizeof($this->customer) === 3) {
            $order['customer'] = $this->customer;
        }
        $order['payment_mode'] = 'seamless';
        $order['payment_method'] = 'UPI_INTENT';
        $order['payment_data'] = [];
        $order['customer_ip'] = '127.0.0.1';

        if (isset($this->shippingAddress) && sizeof($this->shippingAddress) === 5) {
            $order['shipping'] = $this->shippingAddress;
        }

        if (isset($this->billingAddress) && sizeof($this->billingAddress) === 5) {
            $order['shipping'] = $this->shippingAddress;
        }

        if (isset($this->customData)) {
            $order['custom_data'] = $this->customData;
        }

        if (!isset($this->successUrl) || !isset($this->failureUrl) || empty($this->successUrl) || empty($this->failureUrl)) {
            throw new sonicpeSdkException('Success URL & Failure URL is not provided, Please set it using setResponseHandler method', [
                'success_url' => $this->successUrl,
                'failure_url' => $this->failureUrl,
            ], $this->options);
        }

        $order['success_url'] = $this->successUrl;
        $order['failure_url'] = $this->failureUrl;

        if (isset($this->callbackUrl)) {
            $order['callback_url'] = $this->callbackUrl;
        }

        if (isset($this->theme)) {
            $order['theme'] = $this->theme;
        }

        if (isset($this->paymentOptions)) {
            $order['payment_methods'] = $this->paymentOptions;
        }

        $order['signature'] = $this->util->generateSignature($order, $this->encKey);
        // Execute create order API
        /*echo "<pre>";
        print_r(json_encode($order));
        exit;*/
        $response = $this->executeRequest("payment/transaction/init", "POST", $order);

        return $response;
    }

    /**
     * @param $transactionId
     * @return mixed
     * @throws sonicpeSdkException
     */
    public function getTransactionStatus($transactionId)
    {
        // Execute create order API
        $response = $this->executeRequest("payment/transaction/{$transactionId}/status", "GET");
        return $response;
    }

    /**
     * @param $orderId
     * @return mixed
     * @throws sonicpeSdkException
     */
    public function getOrderStatus($orderId)
    {
        $response = $this->executeRequest("payment/order/{$orderId}/status", "GET");
        return $response;
    }

    private function getClient()
    {
        $config = [];

        if (isset($this->options['mode']) && strcmp($this->options['mode'], 'Test') === 0) {
            $config['base_uri'] = 'https://api.sonicpe.com/api/v1/';
        } else {
            $config['base_uri'] = 'https://api.sonicpe.com/api/v1/';
        }

        if (isset($this->options['proxy'])) {
            $config['proxy'] = ['https' => $this->options['proxy']];
        }

        // Set authorization header
        $config['headers'] = [
            'MerchantId' => $this->merchantId,
            'AccessToken' => $this->accessToken,
        ];

        $apiClient = new Client($config);
        return $apiClient;
    }

    /**
     * @param $url
     * @param $method
     * @param $requestData
     * @return mixed
     * @throws sonicpeSdkException
     */
    private function executeRequest($url, $method, $requestData = null)
    {
        try {

            $client = $this->getClient();

            if (strcmp($method, "GET") === 0) {
                $response = $client->get($url);
            } else {
                $response = $client->post($url, [
                    'json' => $requestData
                ]);
            }

            $response = $response->getBody()->getContents();
            if (isset($response) && $this->isJson($response)) {
                return json_decode($response);
            } else {
                throw new sonicpeSdkException('Unexpected response received', [
                    'error_message' => 'Expected JSON but received string',
                    'response' => $response
                ], $this->options);
            }
        } catch (ConnectException $ex) {
            throw new sonicpeSdkException('Network error occurred during API Call', [
                'error_message' => $ex->getMessage(),
                'response' => null
            ], $this->options);
        } catch (ClientException $ex) {
            throw new sonicpeSdkException('API returned bad request error', [
                'error_message' => $ex->getMessage(),
                'response' => $ex->getResponse()->getBody()->getContents(),
            ], $this->options);
        } catch (ServerException $ex) {
            throw new sonicpeSdkException('API encountered server error during processing your request', [
                'error_message' => $ex->getMessage(),
                'response' => $ex->getResponse()->getBody()->getContents(),
            ], $this->options);
        } catch (GuzzleException $ex) {
            throw new sonicpeSdkException('API encountered server error during processing your request', [
                'error_message' => $ex->getMessage(),
                'response' => null,
            ], $this->options);
        } catch (Exception $ex) {
            throw new sonicpeSdkException('Unexpected Error occurred', [
                'error_message' => $ex->getMessage(),
                'response' => null,
            ], $this->options);
        }
    }

    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
