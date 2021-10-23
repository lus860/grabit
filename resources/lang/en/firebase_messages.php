<?php return array (
    'admin'=>[
        'about_order'=>[
            'title'=>'Pending order for “schedule = :schedule” not accepted by vendor',
            'message'=>'“:vendor_name” has not accepted an order of transaction ID “:transaction_id” posted at “:created_at”'
        ],
        'vendor_offline'=>[
            'title'=>'Offline vendor for over 24 hours',
            'message'=>'“:vendor_name” has been offline for over 24 hours, kindly contact the vendor to check if all is good'
        ],
        'product_not_item'=>[
            'title'=>'Menu item not available for over 48 hours',
            'message'=>'“:vendor_name” has not got “:item_name” on their items menu'
        ],
        'product_not'=>[
            'title'=>'Menu item not available for over 48 hours',
            'message'=>'“:vendor_name” has not got “:option_name” on their “:item_name” menu'
        ],
        'overdue_order'=>[
            'title'=>'An order is overdue',
            'message'=>'An order of “:order_type” from “:vendor_name” has been overdue now, please help speed up completion to avoid unhappy customers.'
        ],
        'add_loyalty_amount'=>[
            'title'=>'Loyalty awarded by “:vendor_name”',
            'message'=>'You have spent Tzs “:amount” and collected loyalty points.'

        ]

    ],
  'messages_user_from_admin' =>
  array (
    'Cancelled' =>
    array (
      'title' => 'Order cancelled!',
      'message' => 'We\'re sorry to inform you that :name has cancelled your order due to :accept_message.',
    ),
    'status_303' =>
    array (
      'title' => 'Your order was delivered!',
      'message' => 'Rate your delivery from :restaurant_name by :rider_name.',
    ),
  ),
  'courier_messages' =>
  array (
    'notification' =>
    array (
      'cancelled' =>
      array (
        'title' => 'Order cancelled!',
        'message' => 'We’re sorry to inform you that our in house dispatcher has cancelled your courier order of transaction ID :transaction_id due to :cancellation_message',
      ),
      'accepted' =>
      array (
        'title' => 'Order accepted',
        'message' => 'Your order is accepted of transaction ID #:transaction_id. Our rider is being dispatched to your provided pick up location!',
      ),
      'status_301' =>
      array (
        'title' => 'Rider en route to pick up point',
        'message' => 'Good news! :rider_name is en route to the pick up location to collect your parcel. Please make all neccessary arrangements so the rider does not have to wait any longer',
      ),
      'status_302' =>
      array (
        'title' => 'Rider in transit to delivery location',
        'message' => 'Good news! :rider_name is en route to your delivery location to drop off your parcel. Please make all neccessay arrangements so the rider does not have to wait any longer',
      ),
      'status_303' =>
      array (
        'title' => 'Your courier was delivered!',
        'message' => 'Rate our courier service by :rider_name of transaction ID #:transaction_id',
      ),
      'status_304' =>
      array (
        'title' => 'Pending',
        'message' => '',
      ),
    ),
    'sms' =>
    array (
      'cancelled' => 'We\'re sorry to inform you that our in house dispatcher has cancelled your courier order of transaction ID :transaction_id due to :cancellation_message',
    ),
  ),
  'food' =>
  array (
    'user' =>
    array (
      'pending' =>
      array (
        'title' => 'Pending',
        'message' => NULL,
      ),
      'waiting' =>
      array (
        'title' => 'Pending',
        'message' => NULL,
      ),
      'accepted' =>
      array (
        'title' => 'Order accepted',
        'message' => ':restaurant_name has accepted your order of transaction ID #:transaction_id and has started preparing your food. Our delivery rider will pick it up soon.',
      ),
      'accepted_pick_up' =>
      array (
        'title' => 'Order accepted',
        'message' => ':restaurant_name has accepted your order of transaction ID #:transaction_id and has started preparing your food. Make sure you get just on time for your tasty meal!',
      ),
      'Cancelled' =>
      array (
        'title' => 'Order cancelled!',
        'message' => 'We\'re sorry to inform you that :restaurant_name has cancelled your order of transaction ID #:transaction_id due to :accept_message.',
      ),
      'dispatch' =>
      array (
        'title' => 'Your meal is prepared',
        'message' => ':restaurant_name has already prepared your delicious meal just the way you like it. Get ready to feast on it!',
      ),
      'status_301' =>
      array (
        'title' => 'Rider dispatched',
        'message' => 'Good news! :rider_name is en route to :restaurant_name to collect your food when its ready.',
      ),
      'status_302' =>
      array (
        'title' => 'Food incoming',
        'message' => 'Your order has been picked up by :rider_name and is on the way. Tasty meal is en route!',
      ),
      'status_303' =>
      array (
        'title' => 'Your order was delivered!',
        'message' => 'Rate your delivery from :restaurant_name by :rider_name of transaction ID #:transaction_id',
      ),
      'status_304' =>
      array (
        'title' => 'Title for status 304',
        'message' => 'test message for status 304',
      ),
    ),
    'vendor' =>
    array (
      'waiting' =>
      array (
        'title' => 'New :order',
        'message' => 'You have received a new order #:transaction_id for :order_type to be prepared for :time. The customer is excited and waiting for your to accept the order!',
      ),
      'reminder' =>
      array (
        'title' => 'Scheduled order reminder',
        'message' => 'Reminder to prepapre meal for a scheduled order #:transaction_id for :order_type to be prepared by :time.',
      ),
    ),
  ),
);
