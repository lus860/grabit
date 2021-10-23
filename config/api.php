<?php
return [
    'global'=>[
        'user'=>[],
        'updated_phone'=>[],
    ],
    'admin'=>[
        'email'=>env('MAIL_ADDRESS_TO', 'test@test.com')
    ],
    'types='=>[
        'ios' => [
            'clientID'=> 'Xx8iOsfum2eE1Oci83Bg9odF2',
            'clientSecret'=> 'hm6RgbAhS7ft5n2ghm6RgbAhS7ft5n2ghm6RgbAhS7ft5n2gOci83Bg9o'
        ],

        'android' => [
            'clientID' => 'Xx4Android0pxoA3QOn3Qam4',
            'clientSecret'=> 'Y5sIQBiYKAwUoTDiY5sIQB676KAwUoTDiY5sIQB1234wUoTDiWRcPXklX'
        ]
    ],
    'sources' => [
        1 => 'ios',
        2 => 'android'
    ],
    'error_message'=>[
        'user_register_error'=>"Invalid otp",
        'user_login_error'=>"Invalid otp",
        'user_not_registered'=>"You are not a registered user",
        'not_parameter'=>"Not Parameter",
        'other_error'=>'Could not reach to the server or any message',
        'error_otp'=>'Wrong in otp',
        'user_firebase'=>'Error in notification token',
        'unauthorised_user'=>'Unauthorised user',
        'no_data'=>'No data available',
        'rider_login_error'=>'Invalid username or password',
        'rider_no_order_error'=>'No order',
    ],
    'success_message'=>[
        'user_updated'=>'User information updated',
        'sending_message'=>'Sending message',

    ],
    'mail_subjects'=>[
        'user_create'=>"New Profile",
        'login_verification_code'=>"Login verification code",
        'registration_verification_code'=>"Registration verification code",
        'forgot_password'=>'Forgot password – Grab it Account!',
        'welcome'=>'Warm welcome to Grab it!',
        'new_order_for_restaurant'=>'New Order - Grab it',
        'your_otp'=>'Get Your Code',
        'user_update_email'=>'Update your email',
        'new_order_for_admin'=>'New Orders - Grab it',
        'failed_schedule'=>'Failed Cron Job - Grab it',
        'order_cancelled'=>'Order cancelled - Grab it',
        'message_for_admin'=>'Message for Admin - Grab it'
    ],
    'sms_fasthub'=>[
        'config'=>[
            'channel'=>'119394',
            'password'=>'FgE9"([M',
            'source'=>'GRAB IT',
            'url'=>'https://secure-gw.fasthub.co.tz/fasthub/messaging/json/api',
            'headers'=>'application/json'
        ],
        'message'=>'Welcome to Grab it! Your verification code (OTP) is: '
    ],
    'order'=>[
        'order_from' => [
            1=>'Android',
            2=>'iOS',
            3=>'Web',
        ],
        'order_type' => [
            1 => 'Delivery',
            2 => 'Pick up',
            3 => 'Dine in',
        ],
        'schedule'=>[
            1=>'ASAP',
            2=>'Future',
        ],
        'payment'=>[
            1=>[
                'title'=>'CASH',
                'payment_status'=>'Unpaid',
                'payment_method'=>'COD'
            ],
        ],
        'status'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for vendor to accept your order',
            'accepted'=>'Preparing meal',
            'Cancelled'=>'Cancelled',
            'dispatch'=>'Food marked ready by vendor',
            'status_300'=>'',
            'status_301'=>'Rider dispatched',
            'status_302'=>'Food on the way',
            'status_303'=>'Delivered',
            'status_304'=>'Returned Order',
        ],
        'status_for_user'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for vendor to accept your order',
            'accepted'=>'Preparing your meal',
            'Cancelled'=>'Cancelled',
            'dispatch'=>'Meal prepared',
            'status_300'=>'',
            'status_301'=>'Rider dispatched',
            'status_302'=>'Food on the way',
            'status_303'=>'Delivered',
            'status_304'=>'Returned Order',
        ],
        'status_for_api'=>[
            'restaurant_get_order'=>[
                'pending'=>'Pending',
                'waiting'=>'Pending',
                'accepted'=>'Process',
                'Cancelled'=>'Cancelled',
                'dispatch'=>'Completed',
                'status_300'=>'Process',
                'status_301'=>'Process',
                'status_302'=>'Process',
                'status_303'=>'Process',
                'status_304'=>'Cancelled',
            ],
            'user_get_order'=>[
                'pending'=>'Pending',
                'waiting'=>'Waiting for vendor to accept your order',
                'accepted'=>'Preparing your order',
                'Cancelled'=>'Order cancelled',
                'dispatch'=>'Order completed',
                'status_300'=>'Preparing your order',
                'status_301'=>'Rider dispatched',
                'status_302'=>'Rider on the way to customer',
                'status_303'=>'Order completed',
                'status_304'=>'Order cancelled',
            ],
        ],
        'days'=>['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'status_for_manage'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for vendor to accept your order',
            'accepted'=>'Preparing meal',
            'Cancelled'=>'Cancelled',
            'dispatch'=>'Dispatched by vendor',
            'status_300'=>'',
            'status_301'=>'Rider dispatched',
            'status_302'=>'Transit',
            'status_303'=>'Delivered',
            'status_304'=>'Returned Order',
        ],
    ],
    'courier_order'=>[
        'order_from' => [
            1=>'Android',
            2=>'iOS',
            3=>'Web',
        ],
        'order_type' => [
            1 => 'Delivery',
            2 => 'Pick up',
            3 => 'Dine in',
        ],
        'schedule'=>[
            1=>'ASAP',
            2=>'Future',
        ],
        'payment'=>[
            1=>[
                'title'=>'CASH',
                'payment_status'=>'Unpaid',
                'payment_method'=>'COD'
            ],
        ],
        'status'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for Grab it dispatcher to accept task',
            'accepted'=>'Task Accepted by dispatcher',
            'Cancelled'=>'Task cancelled',
            'dispatch'=>'Food marked ready by restaurant',
            'status_300'=>'',
            'status_301'=>'Rider headed to pick up point',
            'status_302'=>'Rider in transit to delivery point',
            'status_303'=>'Rider delivered',
            'status_304'=>'Task aborted',
        ],
        'status_for_user'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for vendor to accept your order',
            'accepted'=>'Preparing your meal',
            'Cancelled'=>'Cancelled',
            'dispatch'=>'Meal prepared',
            'status_300'=>'',
            'status_301'=>'Rider dispatched',
            'status_302'=>'Food on the way',
            'status_303'=>'Delivered',
            'status_304'=>'Returned Order',
        ],
        'status_for_api'=>[
            'pending'=>'Pending',
            'waiting'=>'Waiting for Grab it dispatcher to accept task',
            'accepted'=>'Grab it dispatcher accepted the courier order',
            'Cancelled'=>'Courier order cancelled',
            'dispatch'=>'Meal prepared',
            'status_300'=>'',
            'status_301'=>'Rider en route to pick up point',
            'status_302'=>'Rider in transit to delivery location',
            'status_303'=>'Courier delivered',
            'status_304'=>'Returned Order',
        ],
        'days'=>['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
        ]
    ],
    'firebase'=>[
        'push_notification'=>[
            'title'=>'test title',
            'icon'=>'/logo',
        ],
    ],
    'dash_delivery'=>[
        'get'=>[
            'url'=>'https://app.dash.co.tz/oauth/token',
            'grant_type'=>'password',
            'client_id'=>'16',
            'client_secret'=>'84MWboK17VZrxDR4RHbtK7uxwDIfYt11U2RGIdAf',
            'username'=>'test1@computerland.co.tz',
            'password'=>'1234567890',
        ],
        'add'=>[
            'url'=>'https://app.dash.co.tz/api/tasks/add',
            'user_id'=>'94',
            'customer_email'=>'test@gmail.com',
            'weight'=>'1',
            'payment_method'=>'CASH',
            'payment_status'=>'NOT PAID',
            'token'=>'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijg1YzQ3ZTlhODE4ZTRmOTI4NThkMDExMTM0NmE2ZmE4MGI3ZGJkN2EyMjVlNzE4OTBlNjgzMTgwYmFiNTIzODAyMjAxMDE5MjMzMTVkNTY1In0.eyJhdWQiOiIxNiIsImp0aSI6Ijg1YzQ3ZTlhODE4ZTRmOTI4NThkMDExMTM0NmE2ZmE4MGI3ZGJkN2EyMjVlNzE4OTBlNjgzMTgwYmFiNTIzODAyMjAxMDE5MjMzMTVkNTY1IiwiaWF0IjoxNTgyNzIzMzEyLCJuYmYiOjE1ODI3MjMzMTIsImV4cCI6MTYxNDM0NTcxMiwic3ViIjoiMTAiLCJzY29wZXMiOltdfQ.iLFkeaQkqkM0aR5WJ3G2Wye4vugtTDyzQd0P54Td0MVFlaK8t0jUAAzahGGCtScyq1LcdrbpN02gAB43AAp9IAPjlF8H6SAVirCv6ld-JMYmQQ9DDFWViVrdCZJdmZhbvE9Xr_Mkp0On0d71nFJnpc05t19HIVSFfBW-c-CUQN8gxkIcSf4S6W-JdilX12j9iPSfjkKJuj0mZezUpadw5XFSI_lR8OXa-bWQz25iE_uxSv6ZEHfqbtQzvLeBKpQOHxY3L8R3CJPFJZNACVvaKovus7DAvLIXiytRFgWRJtw1_FGPvJnJbh9-2oOccqBwtsyIcZ73nVp0lMjwRmz-v-6ydu3kM1-7l6EDZs2G_wGoldf8BpQhjy1dAJF93V0sX92TGzn-NkRMmTBrUIXQv1xRMLS-j5s3r-q8yXlTSKJrlPwLE17GGYC6F3BiWXYrzm9bCoO3MSF6-4d4na_7PMvz29rBZQLpvf26O3UeTL6yHyN0CQk4Jq8Qwlwroym4E_6xK969Xv51u1OGrEJhSzI5E6-cwX0Ndk_VTeH40hFCeM8oiF0ctdCGFxvLS0a_PsIn5LrwsueKkLvjq0ITBQhUtTSeVfP6-m9EUng4xb36NTiSaNqekhsTMuOHOO55FrR56QrVm-XNb4Ho1c6t5hUpfU874trzx4a7uXIbs00',
        ],
        'get_task'=>[
            'url'=>'https://app.dash.co.tz/api/get-task?task_id=',
        ],
        'track_delivery'=>[
            'url'=>'https://app.dash.co.tz/api/track',
        ],

    ],
];
