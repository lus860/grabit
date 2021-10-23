<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700&display=swap" rel="stylesheet">
    <title>You hve new order</title>
    <style type="text/css">
        body{
            background: #eee;
            font-family: 'Raleway', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 30px;
        }
        .container{
            width: 500px;
            margin: auto;
            background: #FFF;
            overflow: hidden;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            border: 1px solid #0a2e60;
        }
        .header {
            padding: 16px;
            background: #fff;
            text-align: center;
        }
        .order-heading{
            font-size: 20px;
            text-align: center;
            margin: 0;
            padding: 16px;
            border-bottom: 1px solid #0a2e60;
            color: #0a2e60;
        }
        .email-content{
            padding: 20px;
        }
        .clear{
            clear: both;
        }
        .order-details-label{
            float: left;
            width: 28%;
        }
        .order-details-label span{
            float: right;
        }
        .order-details-content{
            float: right;
            width: 70%;
        }
        .order-item-list{
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .order-detail-item{
            padding: 6px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details{
            border-top: 1px solid #eee;
        }
        .footer{
            padding: 16px 20px;
            text-align: left;
            background: #0a2e60;
            color: #FFF;
        }
        .footer p{
            padding: 0;
            margin:0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{url('/')}}/images/logo.png" height="60" />
    </div>
    <h3 class="order-heading">New order received</h3>
    <div class="email-content">

        <p>Hello, {{$data['admin']}}</p>
        <p>You have received new order at Mamboz Food Mobile Application.</p>
        <h4>Order details.</h4>
        <div class="order-details">
            <div class="order-detail-item">
                <div class="order-details-label">Order Number<span>:</span></div>
                <div class="order-details-content">{{$data['order']['id']}}</div>
                <div class="clear"></div>
            </div>
            <div class="order-detail-item">
                <div class="order-details-label">Order date<span>:</span></div>
                <div class="order-details-content">{{$data['order']['date']}} at {{$data['order']['time']}}</div>
                <div class="clear"></div>
            </div>
            <div class="order-detail-item">
                <div class="order-details-label">Quantity<span>:</span></div>
                <div class="order-details-content">{{$data['order']['quantity']}}</div>
                <div class="clear"></div>
            </div>
            <div class="order-detail-item">
                <div class="order-details-label">Amount<span>:</span></div>
                <div class="order-details-content">{{$data['order']['amount']}}</div>
                <div class="clear"></div>
            </div>

            <div class="order-detail-item">
                <div class="order-details-label">Order items<span>:</span></div>
                <div class="order-details-content">
                    <ul class="order-item-list">
                        @foreach($data['order']['products'] as $key=>$product)
                            <li>{{$key+1}}. {{$product['name']}}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="clear"></div>
            </div>

            <div class="order-detail-item">
                <div class="order-details-label">Customer<span>:</span></div>
                <div class="order-details-content">{{$data['order']['customer']->name}}<br/>{{$data['order']['customer']->email}}<br/>{{$data['order']['customer']->phone}}</div>
                <div class="clear"></div>
            </div>

        </div>

    </div>
<div class="footer">
    <p>Best regards,<br/>
        Mamboz Food<br/>
        This is an automated email - no need to reply </p>
</div>
</div>
</body>
</html>