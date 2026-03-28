<?php

namespace sonicpe\paymentV2;

use Exception;
use sonicpe\paymentV2\Exception\sonicpeSdkException;

class sonicpeUtil
{

    private $option;

    public function __construct($option)
    {
        $this->option = $option;
    }

    /**
     * @param $requestBody
     * @param $dataString
     * @return string
     * @throws sonicpeSdkException
     */
    function getArrayValues($requestBody, &$dataString)
    {
        try {
            foreach ($requestBody as $key => $value) {
                if (is_array($value)) {
                    $this->getArrayValues($value, $dataString);
                } else {
                    $dataString .= $value;
                    $dataString .= '|';
                }
            }
            return $dataString;
        } catch (Exception $ex) {
            throw new sonicpeSdkException('Error while processing array in sonicpe Util', [
                'error_message' => $ex->getMessage()
            ], $this->option);
        }
    }

    /**
     * @param $requestBody
     * @param $apiSecret
     * @return string
     * @throws sonicpeSdkException
     */
    function generateSignature($requestBody, $apiSecret)
    {
        try {
            $dataString = '';
            unset($requestBody['signature']);
            $dataString = $this->getArrayValues($requestBody, $dataString);
            $dataString .= '#';
            $signature = hash_hmac('sha512', $dataString, $apiSecret);
            return $signature;
        } catch (Exception $ex) {
            throw new sonicpeSdkException('Error while generating signature in sonicpe Util', [
                'error_message' => $ex->getMessage()
            ], $this->option);
        }
    }
    function generateSignatureCV($requestBody, $apiSecret)
    {
        try {
            $dataString = '';
            unset($requestBody['signature']);
            $dataString = $this->getArrayValues($requestBody, $dataString);
            $dataString .= '#';
            $signature = hash_hmac('sha512', $dataString, $apiSecret);
            return $signature;
        } catch (Exception $ex) {
            throw new sonicpeSdkException('Error while generating signature in sonicpe Util', [
                'error_message' => $ex->getMessage()
            ], $this->option);
        }
    }

    /**
     * @param $paymentData
     * @param $apiSecret
     * @return string
     * @throws sonicpeSdkException
     */
    public function encrypt($paymentData, $apiSecret)
    {
        try {
            $iv = openssl_random_pseudo_bytes(16);
            $value = openssl_encrypt(serialize($paymentData), 'AES-256-CBC', $apiSecret, 0, $iv);
            $bIv = base64_encode($iv);
            $mac = hash_hmac('sha256', $bIv . $value, $apiSecret);
            $c_arr = array('iv' => $bIv, 'value' => $value, 'mac' => $mac);
            $json = json_encode($c_arr);
            $crypted = base64_encode($json);
            return $crypted;
        } catch (Exception $ex) {
            throw new sonicpeSdkException('Error while encrypting data in sonicpe Util', [
                'error_message' => $ex->getMessage()
            ], $this->option);
        }
    }
}
