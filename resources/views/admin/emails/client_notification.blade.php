<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700&display=swap" rel="stylesheet">
    <title>Client notification</title>
    <style type="text/css">
        body{
            background: #eee;
            font-family: 'Raleway', sans-serif;
            font-size: 13px;
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
        .order-product-image{
            float: left;
            width: 25%;
        }
        .order-product-details{
            float: right;
            width: 73%;
        }
        .order-total{
            padding-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{url('/')}}/images/logo.png" height="60" />
    </div>
    <h3 class="order-heading">Your order detail #{{$data['order']['id']}}</h3>
    <div class="email-content">

        <p>Hello, {{$data['order']['customer']->name}}</p>
        <p>Thank you for your order. We’ll send a confirmation when your order ships.
            Your estimated delivery date is indicated below.
            If you would like to view the status of your order or make any changes to it, please visit Your Orders on Mamboz Food App.</p>

        <h4>Order details.</h4>
        <div class="order-details">
            <div class="order-detail-item">
                <div class="order-details-label">Order date<span>:</span></div>
                <div class="order-details-content">{{$data['order']['date']}} at {{$data['order']['time']}}</div>
                <div class="clear"></div>
            </div>
            <div class="order-detail-item">
                <div class="order-details-label">Delivery Address<span>:</span></div>
                <div class="order-details-content">{{$data['order']['address']->address}}</div>
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
                            <li>
                                <div class="order-product-image"><img src="{{url('/')}}/images/products/{{$product['image']}}" width="70" /></div>
                                <div class="order-product-details"><a href="#">{{$product['name']}}</a></div>
                                <div class="clear"></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="order-total">
            <div class="order-total-container">
                <div class="order-detail-item">
                    <div class="order-details-label">Item subtotal<span>:</span></div>
                    <div class="order-details-content">{{$data['sub_total']}}</div>
                    <div class="clear"></div>
                </div>
                <div class="order-detail-item">
                    <div class="order-details-label">Shipping &amp; Handling<span>:</span></div>
                    <div class="order-details-content">{{$data['shipping_charges']}}</div>
                    <div class="clear"></div>
                </div>

                <div class="order-detail-item">
                    <div class="order-details-label">Promotion Applied<span>:</span></div>
                    <div class="order-details-content">{{$data['promotion']}}</div>
                    <div class="clear"></div>
                </div>
                <div class="order-detail-item">
                    <div class="order-details-label"><strong>Order Total</strong><span>:</span></div>
                    <div class="order-details-content"><strong>{{$data['order_total']}}</strong></div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div class="" style="padding: 0 0 20px; font-size: 11px">
            <p>Need to make changes to your order? Visit our Help page for more information.</p>
            <p>If you use a mobile device, you can receive notifications about the delivery of your package and track it from our free Mamboz App.</p>
            <p>Some products have a limited quantity available for purchase. Please see the product’s Detail Page for the available quantity. Any orders which exceed this quantity will be automatically canceled.</p>
            <p>We hope to see you again soon.</p>
        </div>
    </div>
    <div class="footer" style="font-size: 11px">
        <p>Best regards,<br/>
            Mamboz Food<br/>
            This is an automated email - no need to reply </p>
    </div>
</div>
</body>
</html>