<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="content-script-type" content="text/javascript">
    <meta http-equiv="x-dns-prefetch-control" content="on">

    <!--  <link rel="apple-touch-icon" sizes="180x180" href="assests/images/fav_icon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assests/images/fav_icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assests/images/fav_icon.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assests/images/fav_icon.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assests/images/fav_icon.png"> -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Payment Page - {{ env('APP_NAME') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;300;500;700;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');


        .PaymentGatway {
            background: #0094FF;
            padding: 7px;
            text-align: center;
        }

        .PaymentGatway h1 {
            display: inline-block;
            border: 2px solid #FFFFFF;
            border-radius: 8px;
            padding: 2px 16px;
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 700;
            font-size: 54.7522px;
            line-height: 67px;
            /* identical to box height */


            color: #FFFFFF;
        }

        .DepositAmount {
            padding: 40px 0;
            border-bottom: 15px solid #0094FF;
        }

        .DepositAmount .box {
            width: 940px;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            background: #F8F8F8;
            box-shadow: 0px 0px 8px rgb(0 0 0 / 40%);
            border-radius: 8px;
            padding: 50px;
        }

        .DepositAmount .box .AmountArea {
            padding-bottom: 58px;
        }

        .DepositAmount .box .AmountArea .item {
            width: 50%;
            text-align: center;
        }

        .DepositAmount .box .AmountArea .item h4 {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 25px;
            line-height: 30px;
            color: #000000;
            margin-bottom: 32px;
        }

        .DepositAmount .box .AmountArea .item p {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 25px;
            line-height: 30px;
            color: #000000;
            margin-bottom: 0;
        }

        .DepositAmount .box .step {
            border-radius: 4px;
            margin-bottom: 36px;
        }

        .DepositAmount .box .step .item-left {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 24px;
            line-height: 29px;
            text-align: center;
            color: #FFFFFF;
            background: #0094FF;
            border-radius: 4px 0px 0px 4px;
            padding: 0 8px;
        }

        .DepositAmount .box .step .item-left span {}

        .DepositAmount .box .step .item-right {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 21px;
            line-height: 25px;
            text-align: center;
            padding: 4px 8px;
            color: #000000;
            background: #D9D9D9;
        }

        .DepositAmount .box .step .item-right p {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 21px;
            line-height: 25px;
            text-align: center;
            color: #000000;
        }

        .DepositAmount .box .UPIID {
            margin-bottom: 45px;
        }

        .DepositAmount .box .UPIID .item-left {
            align-self: center;
        }

        .DepositAmount .box .UPIID .item-left .img-fluid {}

        .DepositAmount .box .UPIID .item-mid {
            width: 135px;
            text-align: center;
            display: flex;
            justify-content: center;
            vertical-align: middle;
            align-items: center;
            position: relative;
        }

        .DepositAmount .box .UPIID .item-mid::before {
            position: absolute;
            content: "";
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            width: 2px;
            height: 100%;
            background-color: #D9D9D9;
        }

        .DepositAmount .box .UPIID .item-mid span {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 23px;
            line-height: 28px;
            text-align: center;
            color: #000000;
            background: #f8f8f8;
            position: relative;
            padding: 0;
        }

        .DepositAmount .box .UPIID .item-right {
            background: #0094FF;
            border: 2.8px solid #0094FF;
            border-radius: 9px;
            width: 100%;
            align-self: center;
        }

        .DepositAmount .box .UPIID .item-right .topBox {
            align-self: center;
        }

        .DepositAmount .box .UPIID .item-right .topBox h4 {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 36px;
            line-height: 44px;
            text-align: center;
            color: #FFFFFF;
            padding: 13px;
        }

        .DepositAmount .box .UPIID .item-right .midBox {
            padding-top: 33px;
            padding-left: 21px;
            padding-right: 21px;
            padding-bottom: 33px;
            background-color: #fff;
            border-radius: 9px;
            text-align: center;
        }

        .DepositAmount .box .UPIID .item-right .midBox input {
            background: #FFFFFF;
            border: 2px solid #0094FF;
            border-radius: 8px;
            padding: 15px 40px;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 26px;
            line-height: 31px;
            color: #000000;
            margin-bottom: 54px;
        }

        .DepositAmount .box .UPIID .item-right .midBox button {
            background: #04BE00;
            border-radius: 10px;
            width: 306px;
            padding: 12px 20px;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 26px;
            line-height: 31px;
            color: #FFFFFF;
            border: transparent;

        }

        .DepositAmount .box .UTRNumber {}

        .DepositAmount .box .UTRNumber h4 {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 25px;
            line-height: 30px;
            color: #000000;
            margin-bottom: 13px;
        }

        .DepositAmount .box .UTRNumber {}

        .DepositAmount .box .UTRNumber input::placeholder {
            color: #D2D2D2;
        }

        .DepositAmount .box .UTRNumber input {
            background: #FFFFFF;
            border: 2px solid #0094FF;
            border-radius: 8px;
            padding: 18px 22px;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 25px;
            line-height: 30px;
            color: #D2D2D2;
            margin-bottom: 41px;
        }

        .DepositAmount .box .UTRNumber button {
            background: #04BE00;
            border-radius: 10px;
            width: 619px;
            max-width: 100%;
            padding: 12px 20px;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 26px;
            line-height: 31px;
            border: transparent;
            color: #FFFFFF;
            margin-left: auto;
            margin-right: auto;
            display: block;

        }

        /*// Extra large devices (large desktops, 1440px and up)*/
        @media (min-width: 1200px) and (max-width: 1699.98px) {
            .PaymentGatway h1 {
                padding: 0px 14px;
                font-size: 41.0642px;
                line-height: 50px;
            }

            .DepositAmount .box {
                width: 690px;
                padding: 35px;
            }

            .DepositAmount .box .AmountArea .item h4 {
                margin-bottom: 23.5px;
                font-size: 18.75px;
                line-height: 23px;
            }

            .DepositAmount .box .AmountArea .item p {
                font-size: 18.75px;
                line-height: 23px;
            }

            .DepositAmount .box .step .item-left {
                padding: 0 4px;
                font-size: 18px;
                line-height: 22px;
                width: 90px;
            }

            .DepositAmount .box .AmountArea {
                padding-bottom: 40px;
            }

            .DepositAmount {
                padding: 20px 0;
                border-bottom: 8px solid #0094FF;
            }

            .DepositAmount .box .step {
                margin-bottom: 27px;
            }

            .DepositAmount .box .step .item-right {
                padding: 4px 6px;
            }

            .DepositAmount .box .UPIID .item-mid {
                width: 101px;
            }

            .DepositAmount .box .UPIID .item-right .topBox h4 {
                padding: 9.75px;
                font-size: 27px;
                line-height: 33px;
            }

            .DepositAmount .box .UPIID .item-right .midBox {
                padding-top: 24.75px;
                padding-left: 15px;
                padding-right: 15px;
                padding-bottom: 24.75px;
            }

            .DepositAmount .box .UPIID .item-right .midBox input {
                padding: 11px 40px;
                margin-bottom: 30px;
                font-size: 19.5px;
                text-align: center;
                line-height: 24px;
            }

            .DepositAmount .box .UPIID .item-mid span {
                font-size: 17.25px;
                line-height: 21px;
            }

            .DepositAmount .box .UPIID {
                margin-bottom: 30px;
            }

            .DepositAmount .box .UTRNumber h4 {
                margin-bottom: 9px;
                font-size: 18.75px;
                line-height: 23px;
            }

            .DepositAmount .box .UTRNumber input {
                padding: 14px 16px;
                margin-bottom: 30px;
                font-size: 18.75px;
                line-height: 23px;
            }

            .DepositAmount .box .UTRNumber button {
                padding: 9px 20px;
                width: 464.25px;
                font-size: 19.5px;
                line-height: 24px;
            }

            .DepositAmount .box .UPIID .item-left {
                width: 230px;
            }

            .DepositAmount .box .UPIID .item-right .midBox button {
                width: 230px;
                padding: 8px 20px;
                font-size: 19.5px;
                line-height: 24px;
            }

            .DepositAmount .box .step .item-right p {
                font-size: 15px;
                line-height: 19px;
            }

            /* end  */
        }


        /*// Medium devices (tablets, 768px and up)*/
        @media (min-width: 768px) and (max-width: 991.98px) {
            .PaymentGatway h1 {
                padding: 0px 16px;
                font-size: 36.7522px;
                line-height: 52px;
            }

            .DepositAmount .box {
                padding: 40px;
            }

            .DepositAmount .box .AmountArea .item h4 {
                font-size: 20px;
                line-height: 24px;
                margin-bottom: 25px;
            }

            .DepositAmount .box .AmountArea .item p {
                font-size: 22px;
                line-height: 26px;
            }

            .DepositAmount .box .step .item-left {
                font-size: 18px;
                line-height: 26px;
                padding: 0 8px;
                width: 100px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .DepositAmount .box .step .item-right p {
                font-size: 14px;
                line-height: 24px;
            }

            .DepositAmount .box .UPIID .item-right .topBox h4 {
                font-size: 26px;
                line-height: 30px;
                padding: 10px;
            }

            .DepositAmount .box .UPIID .item-right .midBox input {
                padding: 10px 30px;
                font-size: 20px;
                line-height: 30px;
                margin-bottom: 30px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox {
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
                padding-bottom: 20px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox button {
                background: #04BE00;
                border-radius: 10px;
                width: 280px;
                max-width: 100%;
                padding: 10px 20px;
                font-size: 20px;
                line-height: 30px;
            }

            .DepositAmount .box .UTRNumber h4 {
                font-size: 20px;
                line-height: 30px;
                margin-bottom: 10px;
            }

            .DepositAmount .box .UTRNumber input {
                padding: 14px 22px;
                font-size: 20px;
                line-height: 25px;
                margin-bottom: 30px;
            }

            .DepositAmount .box .UTRNumber button {
                width: 550px;
                padding: 12px 20px;
                font-size: 20px;
                line-height: 30px;
            }

            .DepositAmount .box .UPIID .item-mid span {
                font-size: 18px;
                line-height: 28px;
            }
        }

        /*// Small devices (landscape phones, 576px and up)*/
        @media (min-width: 576px) and (max-width: 767.98px) {
            .PaymentGatway h1 {
                padding: 0px 16px;
                font-size: 36.7522px;
                line-height: 52px;
            }

            .DepositAmount .box {
                padding: 40px;
            }

            .DepositAmount .box .AmountArea .item h4 {
                font-size: 20px;
                line-height: 24px;
                margin-bottom: 25px;
            }

            .DepositAmount .box .AmountArea .item p {
                font-size: 22px;
                line-height: 26px;
            }

            .DepositAmount .box .step .item-left {
                font-size: 18px;
                line-height: 26px;
                padding: 0 8px;
                width: 100px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .DepositAmount .box .step .item-right p {
                font-size: 14px;
                line-height: 24px;
            }

            .DepositAmount .box .UPIID .item-right .topBox h4 {
                font-size: 26px;
                line-height: 30px;
                padding: 10px;
            }

            .DepositAmount .box .UPIID .item-right .midBox input {
                padding: 10px 30px;
                font-size: 20px;
                line-height: 30px;
                margin-bottom: 30px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox {
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
                padding-bottom: 20px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox button {
                background: #04BE00;
                border-radius: 10px;
                width: 280px;
                max-width: 100%;
                padding: 10px 20px;
                font-size: 20px;
                line-height: 30px;
            }

            .DepositAmount .box .UTRNumber h4 {
                font-size: 20px;
                line-height: 30px;
                margin-bottom: 10px;
            }

            .DepositAmount .box .UTRNumber input {
                padding: 14px 22px;
                font-size: 20px;
                line-height: 25px;
                margin-bottom: 30px;
            }

            .DepositAmount .box .UTRNumber button {
                width: 550px;
                padding: 12px 20px;
                font-size: 20px;
                line-height: 30px;
            }

            .DepositAmount .box .UPIID .item-mid span {
                font-size: 18px;
                line-height: 28px;
            }

            .DepositAmount .box .AmountArea .item {
                width: 100%;
                text-align: center;
            }

            .DepositAmount .box .UPIID {
                margin-bottom: 45px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-mid::before {
                width: 100%;
                height: 2px;
            }

            .DepositAmount .box .UPIID .item-mid {
                width: 100%;
                margin: 20px;
            }

            .DepositAmount .box .UPIID .item-right {
                width: 100%;
            }
        }

        /*Extra small devices (portrait phones, less than 576px)*/
        @media (max-width: 575.98px) {
            .PaymentGatway h1 {
                padding: 0px 10px;
                font-size: 16px;
                line-height: 30px;
            }

            .DepositAmount {
                padding: 15px 0;
                border-bottom: 5px solid #0094FF;
            }

            .DepositAmount .box {
                padding: 40px 20px;
            }

            .DepositAmount .box {
                padding: 15px 10px;
            }

            .DepositAmount .box .AmountArea .item h4 {
                font-size: 16px;
                line-height: 22px;
                margin-bottom: 8px;
            }

            .DepositAmount .box .AmountArea .item p {
                font-size: 16px;
                line-height: 24px;
            }

            .DepositAmount .box .step .item-left {
                font-size: 14px;
                line-height: 26px;
                padding: 0 8px;
                width: 100px;
                display: block;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
                border-radius: 8px 8px 0 0;
            }

            .DepositAmount .box .step .item-right p {
                font-size: 13px;
                line-height: 20px;
            }

            .DepositAmount .box .UPIID .item-right .topBox h4 {
                font-size: 22px;
                line-height: 26px;
                padding: 8px;
            }

            .DepositAmount .box .UPIID .item-right .midBox input {
                padding: 6px 15px;
                font-size: 16px;
                line-height: 28px;
                margin-bottom: 15px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox {
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
                padding-bottom: 20px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-right .midBox button {
                width: 200px;
                padding: 6px 20px;
                font-size: 14px;
                line-height: 28px;
            }

            .DepositAmount .box .UTRNumber h4 {
                font-size: 20px;
                line-height: 30px;
                margin-bottom: 10px;
            }

            .DepositAmount .box .UTRNumber input {
                padding: 6px 15px;
                font-size: 16px;
                line-height: 28px;
                margin-bottom: 15px;
            }

            .DepositAmount .box .UTRNumber button {
                width: 200px;
                padding: 6px 20px;
                font-size: 14px;
                line-height: 28px;
            }

            .DepositAmount .box .UPIID .item-mid span {
                font-size: 18px;
                line-height: 28px;
            }

            .DepositAmount .box .AmountArea .item {
                width: 100%;
                text-align: center;
            }

            .DepositAmount .box .UPIID {
                margin-bottom: 25px;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-mid::before {
                width: 100%;
                height: 2px;
            }

            .DepositAmount .box .UPIID .item-mid {
                width: 100%;
                margin: 20px 0;
                max-width: 100%;
            }

            .DepositAmount .box .UPIID .item-right {
                width: 100%;
            }

            .DepositAmount .box .AmountArea {
                padding-bottom: 20px;
            }

            .DepositAmount .box .step {
                margin-bottom: 15px;
                display: block !important;
                text-align: center;
            }

            .DepositAmount .box .UPIID .item-left .img-fluid {
                max-width: 40%;
            }
        }

        /*Extra small devices (portrait phones, less than 414px)*/
        @media (max-width: 414px) {}

        /*Extra small devices (portrait phones, less than 375px)*/
        @media (max-width: 375px) {}

        /*Extra small devices (portrait phones, less than 360px)*/
        @media (max-width: 360px) {}

        /*Extra small devices (portrait phones, less than 320px)*/
        @media (max-width: 320px) {}
    </style>
</head>

<body>
    <!-- PAYMENT GATEWAY start  -->
    <div class="PaymentGatway">
        <div class="container">
            <h1 class="text-uppercase">PAYMENT GATEWAY</h1>
        </div>
    </div>
    <!-- PAYMENT GATEWAY end -->

    <!-- Deposit Amount start  -->
    <div class="DepositAmount">
        <div class="container">
            <!-- box star  -->
            <div class="box">
                <div class="AmountArea d-md-flex">
                    <div class="item align-self-center">
                        <h4>Deposit Amount</h4>
                        <p class="mb-0">Rs. {{ $amount }}</p>
                    </div>

                    <div class="item align-self-center mt-md-0 mt-3">
                        <h4>Registerd Name</h4>
                        @if (isset($user))
                            <p class="mb-0">{{ $user->name }}</p>
                        @else
                            <p class="mb-0">XXX</p>
                        @endif
                    </div>

                </div>
                <div class="step d-flex">
                    <div class="item-left">
                        <span class="d-block">Step 1</span>
                    </div>
                    <div class="item-right">
                        <p class="mb-0">To make a payment, you can either scan the QR code or copy the UPI ID</p>
                    </div>
                </div>
                <!-- UPI ID start  -->
                <div class="UPIID d-md-flex">
                    <!-- <div class="item-left">
                        <img src="scan.png" alt="img" class="img-fluid">
                    </div>
                    <div class="item-mid">
                        <span>OR</span>
                    </div> -->
                    <div class="item-right">
                        <div class="topBox">
                            <h4 class="mb-0">UPI ID</h4>
                        </div>
                        <div class="midBox">
                            <img class="img-fluid mb-3" src="{{ $appData->upi_image ?? '' }}" style="height: 220px;">
                            <input type="text" value="{{ $appData->admin_upi }}" id="myInput" readonly
                                class="form-control">
                            <button onclick="toastShow('UPI ID Copied!')"
                                data-clipboard-text="{{ $appData->admin_upi }}" class="btn">Copy UPI ID</button>
                        </div>
                    </div>
                </div>
                <!-- UPI ID end  -->
                <div class="step d-flex">
                    <div class="item-left">
                        <span class="d-block">Step 2</span>
                    </div>
                    <div class="item-right">
                        <p class="mb-0">Copy and paste your UTR number from the transaction here</p>
                    </div>
                </div>
                <!-- step end  -->
                <!-- UTR Number start  -->
                <div class="UTRNumber">
                    <h4>UTR Number</h4>
                    <form action="">
                        <input name="utr" id="utr" type="text" placeholder="Enter UTR Number"
                            class="form-control">
                        @if (isset($user))
                            <button onclick="return submitUTR('{{ $user->id }}', '{{ $amount }}');"
                                type="button">SUBMIT</button>
                        @else
                            <button onclick="return toastShow('Invalid User ID');" type="button">SUBMIT</button>
                        @endif
                    </form>
                </div>
                <!-- UTR Number end -->
            </div>
            <!-- box end -->

        </div>
    </div>
    <!-- Deposit Amountend -->

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function isEmpty(value) {
            return value ? value.trim().length == 0 : true;
        }


        function submitUTR(id, amount) {
            var utr = $('#utr').val();
            if (isEmpty(utr)) {
                swal("Sorry!", "Enter valid UTR", "error");
                return false;
            }
            console.log(id);
            $.ajax({
                url: "/verify-utr/" + id + '/' + utr + '/' + amount,
                type: "GET",
                success: function(response) {
                    if (!response.error) {
                        swal({
                            title: "Submitted!",
                            text: "Please Wait For 1 to 2 Hours.",
                            icon: "success",
                            buttons: true,
                            dangerMode: true,
                        }).then((goNow) => {
                            document.location.href = 'https://new.yogiclub777.com/wallet?tab=addPoints';
                        });
                    } else {
                        swal("Hey!", response.message, "error");
                    }
                },
                error: function(response) {
                    console.log(response);
                    swal("Sorry!", "Some Problem Occured!", "error");
                }
            });
            return false;
        }
    </script>

    @if ($user === null)
        <script>
            swal({
                title: "Your Account is Not Registerd!",
                text: "Do You Want to register?",
                icon: "success",
                buttons: true,
                dangerMode: true,
            }).then((goNow) => {
                document.location.href = 'https://new.yogiclub777.com/wallet?tab=addPoints';
            });
        </script>
    @endif

    <script>
        function toastShow(message) {
            swal({
                title: message,
                icon: "success",
                buttons: true,
                dangerMode: false,
            });
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
    <script>
        var btns = document.querySelectorAll('.btn');
        var clipboard = new ClipboardJS(btns);
    </script>
    <!--  jQuery js-->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <!-- bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>
