<?php

namespace App\Models;

use App\Http\Requests\Request;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use GuzzleHttp\Client;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Whoops\Exception\ErrorException;

class SSJUtils
{
    public static $default_image = 'https://app.simbadesign.co.tz/mamboz/images/logo.png';
    private static $dash_url = "https://app.dash.co.tz";
    private static $client_id = '18';
    private static $client_secret = 'tB013YE2eY1Uf7uVXLFJ0gbjwiDCELWsx6vHeftP';

    public function __construct()
    {
        //self::$default_image = 'https://app.simbadesign.co.tz/mamboz/images/logo.png';
    }

    public static function load_user_sections($user_role)
    {
        //Load roles for current user
        //$current_user_role = 0;
        $sections = array();
        if ($user_role > 0) {
            $user_sections = DB::select("SELECT * FROM sections WHERE section_id IN(SELECT section_id FROM roles_sections WHERE role_id=:rid)", ['rid' => $user_role]);
            foreach ($user_sections as $section) {
                $sections[] = $section->section_url;
            }
        }
        return $sections;
    }

    public static function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public static function add255($number)
    {
        $trimmed_number = self::trim_spaces($number);
        $new_number = substr($trimmed_number, -9);
        $new_number = '255' . $new_number;
        return $new_number;
    }

    public static function trim_spaces($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    public static function json_to_csv($json, $csvFilePath = false, $boolOutputFile = false, $file_name = '')
    {

        // See if the string contains something
        if (empty($json)) {
            die("The JSON string is empty!");
        }

        // If passed a string, turn it into an array
        if (is_array($json) === false) {
            $json = json_decode($json, true);
        }

        // If a path is included, open that file for handling. Otherwise, use a temp file (for echoing CSV string)
        if ($csvFilePath !== false) {
            $f = fopen($csvFilePath, 'w+');
            if ($f === false) {
                die("Couldn't create the file to store the CSV, or the path is invalid. Make sure you're including the full path, INCLUDING the name of the output file (e.g. '../save/path/csvOutput.csv')");
            }
        } else {
            $boolEchoCsv = true;
            if ($boolOutputFile === true) {
                $boolEchoCsv = false;
            }
            $strTempFile = '/var/www/app.simbadesign.co.tz/html/mamboz/csvupload/' . $file_name . '_csv_download_' . date("U") . ".csv";
            $f = fopen($strTempFile, "w+");
        }

        $firstLineKeys = false;
        foreach ($json as $line) {
            if (empty($firstLineKeys)) {
                $firstLineKeys = array_keys($line);
                fputcsv($f, $firstLineKeys);
                $firstLineKeys = array_flip($firstLineKeys);
            }

            // Using array_merge is important to maintain the order of keys acording to the first element
            fputcsv($f, array_merge($firstLineKeys, $line));
        }
        fclose($f);

        // Take the file and put it to a string/file for output (if no save path was included in function arguments)
        if ($boolOutputFile === true) {
            if ($csvFilePath !== false) {
                $file = $csvFilePath;
            } else {
                $file = $strTempFile;
            }

            // Output the file to the browser (for open/save)
            if (file_exists($file)) {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        } elseif ($boolEchoCsv === true) {
            if (($handle = fopen($strTempFile, "r")) !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    echo implode(",", $data);
                    echo "<br />";
                }
                fclose($handle);
            }
        }
        // Delete the temp file
        unlink($strTempFile);
    }

    public static function download_csv($data, $file_name)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $file_name . '_csv_download_' . date("U") . '.csv"');
        $fp = fopen('php://output', 'wb');
        fwrite($fp, $data);
        fclose($fp);
    }

    public static function get_number_network($number)
    {
        $tigo_codes = array('71', '65', '67');
        $voda_codes = array('76', '75', '74');
        $airtel_codes = array('78', '68', '69');
        $string = '';
        $code = '';

        if (substr($number, 0, 3) == '255') {
            $code = substr($number, 3, 2);
        } elseif (substr($number, 0, 3) != '255') {
            $code = substr($number, 1, 2);
        }

        if (in_array($code, $tigo_codes)) {
            $string = 'TIGO';
        } elseif (in_array($code, $voda_codes)) {
            $string = 'VODACOM';
        } elseif (in_array($code, $airtel_codes)) {
            $string = 'AIRTEL';
        } else {
            $string = 'UNKNOWN';
        }
        return $string;
    }


    public static function get_user($id)
    {
        $data = array();
        $user_data = DB::table('users')->where('id', $id)->get();
        foreach ($user_data as $value) {
            $data['id'] = $value->id;
            $data['name'] = $value->name;
            $data['email'] = $value->email;
            $data['token'] = $value->token;
            $data['imei'] = $value->imei;
        }
        return $data;
    }

    public static function FormatDate($date, $format = 'M d, Y')
    {
        $date_format = new \DateTime($date);
        $formated_date = $date_format->format($format);
        return $formated_date;
    }

    public static function get_user_apartment($user_id)
    {

    }

    public static function get_category_id_from_keyword($keyword)
    {

    }

    public static function app_expire_date()
    {
        $data = array(
            'expires' => '2019-1-30',
            'warning' => '2019-1-30',
            'update_url' => 'https://google.com',
            'version' => '1.20'
        );
        return $data;
    }

    public static function send_notification_OLD($message, $token)
    {
        if (!isset($message) || !isset($token)) {
            return null;
        }
        $data = json_encode(
            array(
                "notification" => $message,
                "priority" => 10,
                "to" => $token,
            )
        );
        $url = "https://fcm.googleapis.com/fcm/send";
        $key = "key=AIzaSyAAPTTvyTbXGC09YvGrp-MaJolKgHXvf80";
        $options = array(
            'http' => array(
                'header' => [
                    "Content-Type: application/json",
                    "Authorization: " . $key, "Content-Length: " . strlen($data)],
                'method' => 'POST',
                'content' => $data
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public static function send_notification($data = [], $to = [], $device_type = 'android')
    {
        $fcmNotification = null;
        if (!empty($data)) {
            $legacy_key = "AIzaSyAAPTTvyTbXGC09YvGrp-MaJolKgHXvf80";
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $notification = [
                'title' => $data['title'],
                'body' => $data['description'],
                'message' => $data['description'],
                'type' => isset($data['type']) ? $data['type'] : null,
                'order_id' => isset($data['order_id']) ? $data['order_id'] : null,
                'sound' => 'default'
            ];

            if ($device_type == 'android') {
                //$extraNotificationData = ["message" => $notification];
                $fcmNotification = [
                    'registration_ids' => $to, //multple token array
                    'sound' => 'default',
                    'data' => $notification,
                    'notification' => $notification,
                    //'data' => $extraNotificationData
                ];
            } elseif ($device_type == 'ios') {
                $fcmNotification = [
                    'registration_ids' => $to, //multple token array
                    'sound' => 'default',
                    'notification' => $notification,
                    'data' => $notification,
                    'content_available' => true,
                    'mutable_content' => true,
                    //'data' => $extraNotificationData
                ];
            }

            $headers2 = [
                'Authorization' => 'key=' . $legacy_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];

            $client = new \GuzzleHttp\Client(
                ['headers' => $headers2]
            );
            $result = $client->post($fcmUrl, [
                'body' => \GuzzleHttp\json_encode($fcmNotification)
            ]);

            return ['code' => $result->getStatusCode(), 'body' => $result->getBody()->getContents()];
        } else {
            return null;
        }
    }

    public static function send_sms($to, $msg)
    {
        $sender_id = 'sms.co.tz';
        $url = "http://www.sms.co.tz/api.php?do=sms&username=boxinema&password=box2019aa!&senderid=$sender_id&dest=$to&
msg=$msg";

        $result = file_get_contents($url);
        return $result;
    }

    public static function RandomString($length = 4)
    {
        $characters = '1234567890';
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }


    public static function get_user_orders($user_id)
    {
        $order_data = array();
        $orders = Order::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $shipping = Shipping::where('id', $order->shipping_id)->get();
                $payment = Payment::where('id', $order->payment_id)->get();
                $address = Address::where('id', $order->address_id)->get();
                $statuses = OrderStatus::where('order_id', $order->id)->get();
                $order_products = OrderProduct::where('order_id', $order->id)->get();

                $products = array();
                foreach ($order_products as $key2 => $pp) {
                    $product = MenuItem::where('id', $pp->product_id)->first();
                    $products[$key2]['id'] = $pp->product_id;
                    $products[$key2]['price'] = $product->price;
                    $products[$key2]['name'] = !empty($product) ? $product->name : null;
                    $products[$key2]['image'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['imageUri'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['quantity'] = $pp->quantity;
                }

                $order_data[$key]['id'] = $order->id;
                $order_data[$key]['status'] = $order->status;
                $order_data[$key]['img'] = $order->image != null ? $order->image : self::$default_image;
                $order_data[$key]['imageUri'] = $order->image != null ? $order->image : self::$default_image;
                $order_data[$key]['date'] = SSJUtils::FormatDate($order->order_date);
                $order_data[$key]['time'] = SSJUtils::FormatDate($order->order_date, 'H:i');
                $order_data[$key]['size'] = $order->size;
                $order_data[$key]['quantity'] = $order->quantity;
                $order_data[$key]['amount'] = $order->amount;

                $order_data[$key]['restaurant'] = $order->restaurant;
                $order_data[$key]['rider'] = $order->rider;
                $order_data[$key]['notes'] = $order->delivery_note;
                $order_data[$key]['instruction'] = $order->instruction;
                $order_data[$key]['source'] = $order->source;
                $order_data[$key]['feedback'] = $order->feedback;
                $order_data[$key]['type'] = $order->order_type;
                $order_data[$key]['delivery_rating'] = $order->delivery_rating;
                $order_data[$key]['food_rating'] = $order->food_rating;
                $order_data[$key]['delivery_method'] = $order->delivery_method;

                $order_data[$key]['shipping'] = $shipping;
                $order_data[$key]['payment'] = $payment;
                $order_data[$key]['address'] = $address;
                $order_data[$key]['statuses'] = $statuses;
                $order_data[$key]['products'] = $products;
                $order_data[$key]['restaurant'] = $order->restaurant ? $order->restaurant->getBasicData() : null;
            }
        }
        return $order_data;
    }

    public static function get_restaurant_orders($restaurant_id, $order_status = '1')
    {
        $order_data = array();
        $orders = Order::where(['restaurant_id' => $restaurant_id, 'status' => $order_status])->orderBy('id', 'desc')->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $customer = User::where('id', $order->user_id)->first();
                $shipping = Shipping::where('id', $order->shipping_id)->get();
                $payment = Payment::where('id', $order->payment_id)->get();
                $address = Address::where('id', $order->address_id)->get();
                $statuses = OrderStatus::where('order_id', $order->id)->get();
                $order_products = OrderProduct::where('order_id', $order->id)->get();
                $products = array();
                foreach ($order_products as $key2 => $pp) {
                    $product = MenuItem::where('id', $pp->product_id)->first();
                    $products[$key2]['id'] = $pp->product_id;
                    $products[$key2]['price'] = $product->price;
                    $products[$key2]['name'] = !empty($product) ? $product->name : null;
                    $products[$key2]['image'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['imageUri'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['quantity'] = $pp->quantity;
                }
                $order_data[$key]['id'] = $order->id;
                $order_data[$key]['status'] = $order->status;
                $order_data[$key]['img'] = $order->image != null ? $order->image : self::$default_image;
                $order_data[$key]['imageUri'] = $order->image != null ? $order->image : self::$default_image;
                $order_data[$key]['date'] = SSJUtils::FormatDate($order->order_date);
                $order_data[$key]['time'] = SSJUtils::FormatDate($order->order_date, 'H:i');
                $order_data[$key]['size'] = $order->size;
                $order_data[$key]['quantity'] = $order->quantity;
                $order_data[$key]['amount'] = $order->amount;
                $order_data[$key]['restaurant'] = $order->restaurant;
                $order_data[$key]['rider'] = $order->rider;
                $order_data[$key]['notes'] = $order->delivery_note;
                $order_data[$key]['instruction'] = $order->instruction;
                $order_data[$key]['source'] = $order->source;
                $order_data[$key]['feedback'] = $order->feedback;
                $order_data[$key]['type'] = $order->order_type;
                $order_data[$key]['delivery_rating'] = $order->delivery_rating;
                $order_data[$key]['food_rating'] = $order->food_rating;
                $order_data[$key]['shipping'] = $shipping;
                $order_data[$key]['payment'] = $payment;
                $order_data[$key]['delivery_method'] = $order->delivery_method;
                $order_data[$key]['address'] = $address;
                $order_data[$key]['statuses'] = $statuses;
                $order_data[$key]['products'] = $products;
                $order_data[$key]['customer'] = $customer;
                $order_data[$key]['restaurant'] = $order->restaurant->getBasicData();
            }
        }
        return $order_data;
    }

    public static function get_user_order_stats($user_id)
    {
        $sql = DB::select("select max(amount) as amount from orders where user_id=$user_id");
        $data['highest_order_value'] = 0;
        $data['average_order_value'] = 0;
        $total = 0;

        if (!empty($sql)) {
            $data['highest_order_value'] = $sql[0]->amount;
        }

        $orders = Order::where('user_id', $user_id)->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $total += $order->amount;
            }
            $data['average_order_value'] = $total / count($orders);
        }
        return $data;
    }

    public static function get_order($order_id)
    {
        $order_data = array();
        $orders = Order::where('id', $order_id)->get();
        if (!empty($orders)) {
            foreach ($orders as $key => $order) {
                $shipping = Shipping::where('id', $order->shipping_id)->first();
                $payment = Payment::where('id', $order->payment_id)->first();
                $address = Address::where('id', $order->address_id)->first();
                $statuses = OrderStatus::where('order_id', $order->id)->get();
                $order_products = OrderProduct::where('order_id', $order->id)->get();
                $customer = User::where('id', $order->user_id)->first();

                $products = array();
                foreach ($order_products as $key2 => $pp) {
                    $product = MenuItem::where('id', $pp->product_id)->first();
                    $products[$key2]['id'] = $pp->product_id;
                    $products[$key2]['price'] = $product->price;
                    $products[$key2]['name'] = !empty($product) ? $product->name : null;
                    $products[$key2]['image'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['imageUri'] = $product->image != null ? $product->image : self::$default_image;
                    $products[$key2]['quantity'] = $pp->quantity;
                }

                $order_data['id'] = $order->id;
                $order_data['status'] = $order->status;
                $order_data['date'] = SSJUtils::FormatDate($order->order_date);
                $order_data['time'] = SSJUtils::FormatDate($order->order_date, 'H:i');
                $order_data['size'] = $order->size;
                $order_data['quantity'] = $order->quantity;
                $order_data['delivery_method'] = $order->delivery_method;
                $order_data['amount'] = $order->amount;
                $order_data['shipping'] = $shipping;
                $order_data['payment'] = $payment;
                $order_data['address'] = $address;
                //$order_data['statuses'] = $statuses;
                $order_data['statuses'] = self::order_status($order->id);
                $order_data['products'] = $products;
                $order_data['customer'] = $customer;
                $order_data['created_at'] = $order->created_at;
                $order_data['restaurant'] = $order->restaurant->getBasicData();
            }
        }
        return $order_data;
    }

    public static function order_status($id)
    {
        $task = OrderStatus::where('order_id', $id)->get();
        /*$next_status = '';
        $button_label = '';*/

        $status_array = [];

        foreach ($task as $key => $status) {
            $status_array[$key]['id'] = $status->id;
            $status_array[$key]['title'] = $status->statuses->title;
            $status_array[$key]['status'] = $status->statuses->title;
            $status_array[$key]['status_code'] = $status->statuses->code;
            $status_array[$key]['created_at'] = self::FormatDate($status->created_at);
            $status_array[$key]['updated_at'] = self::FormatDate($status->updated_at, 'Y-m-d H:i:s');
            $status_array[$key]['created_time'] = self::FormatDate($status->created_at, 'H:i:s');
        }

        /* //array('New','Dispatched','Transit','Delivered');
         if(in_array($this->statuses[0], $status_array)
             && !in_array($this->statuses[1], $status_array)
             && !in_array($this->statuses[2], $status_array)
             && !in_array($this->statuses[3], $status_array)){

             $next_status = $this->statuses[1];
             $button_label = "Dispatch";

         }elseif(in_array($this->statuses[0], $status_array)
             && in_array($this->statuses[1], $status_array)
             && !in_array($this->statuses[2], $status_array)
             && !in_array($this->statuses[3], $status_array)){
             $next_status = $this->statuses[2];
             $button_label = "Send to transit";

         }elseif(in_array($this->statuses[0], $status_array)
             && in_array($this->statuses[1], $status_array)
             && in_array($this->statuses[2], $status_array)
             && !in_array($this->statuses[3], $status_array)){
             $next_status = $this->statuses[3];
             $button_label = "Mark as delivered";
         }*/

        return $status_array;
    }

    public static function get_all_data()
    {
        $data = array();
        $data['home_sections'] = array();
        $data['products_home'] = array();
        $data['products'] = array();
        $data['categories'] = array();

        $products = Product::paginate(10)->get();
        $product_links = $products->links();

    }

    public static function get_drivers()
    {
        $drivers_id = DB::table('role_user')->where('role_id', 4)->get(['user_id']);
        $drivers = array();
        foreach ($drivers_id as $key => $item) {
            $user_id = $item->user_id;
            $driver = User::where('id', $user_id)->first();
            $drivers[$key]['id'] = $driver->id;
            $drivers[$key]['name'] = $driver->name;
            $drivers[$key]['email'] = $driver->email;
            $drivers[$key]['phone'] = $driver->phone;
        }
        return $drivers;
    }

    public function get_deliveries()
    {
        $all_delivery = Delivery::all();
        $delivery = array();
        foreach ($all_delivery as $key => $item) {
            $driver_id = $item->driver_id;
            $order_id = $item->order_id;
            $delivery[$key]['order'] = self::get_order($order_id);
            $delivery[$key]['driver'] = User::where('id', $driver_id)->first();
        }
        return $delivery;
    }

    public function get_delivery($id)
    {
        $all_delivery = Delivery::where('id', $id->first);
        $delivery = array();
        foreach ($all_delivery as $key => $item) {
            $driver_id = $item->driver_id;
            $order_id = $item->order_id;
            $delivery[$key]['order'] = self::get_order($order_id);
            $delivery[$key]['driver'] = User::where('id', $driver_id)->first();
        }
        return $delivery;
    }

    public static function send_order_to_delivery($order_id)
    {
        header("Content-type: application/json");
        $body = [];
        $token_sql = DB::table('delivery_login_tokens')->where(['id' => 1])->first();
        $token = $token_sql->token;

        $token_response = \GuzzleHttp\json_decode(SSJUtils::get_login_token('mamboz@dash.co.tz', 'Mamboz@2019!'));
        $access_token = $token_response->access_token;

        $this_order = self::get_order($order_id);

        /*echo "Order is:";
        echo "<pre>";
        print_r($access_token);
        echo "</pre>";

        die();*/

        $destination_lat = $this_order['address']['latitude'];
        $destination_lng = $this_order['address']['longitude'];
        $destination_address = $this_order['address']['address'];
        $customer_name = $this_order['customer']['name'];
        $customer_tel = $this_order['customer']['phone'] != '' ? $this_order['customer']['phone'] : 0;
        $customer_email = $this_order['customer']['email'];

        $origin_lat = $this_order['restaurant']['latitude'];
        $origin_lng = $this_order['restaurant']['longitude'];
        $origin_address = $this_order['restaurant']['address'];

        $payment_method = $this_order['payment']['method'];
        $payment_amount = $this_order['amount'];
        $payment_status = 'UNPAID';
        $notify_url = 'https://app.simbadesign.co.tz/mamboz/api/order-update';

        $product_data = '';

        if (!empty($this_order['products'])) {
            foreach ($this_order['products'] as $product) {
                $product_data .= $product['name'] . ', ';
            }
        }
        /*$product_data = \GuzzleHttp\json_encode($this_order['products']);*/

        $url = self::$dash_url . "/api/tasks/add";

        $update_token = md5(time());
        $order = Order::find($order_id);
        $order->update_token = $update_token;
        $order->save();

        $request = array(
            'date' => self::FormatDate($this_order['created_at'], 'Y-m-d H:i:s'),
            'destination_lat' => $destination_lat,
            'destination_lng' => $destination_lng,
            'destination_address' => $destination_address,

            'origin_lat' => $origin_lat,
            'origin_lng' => $origin_lng,
            'origin_address' => $origin_address,

            'customer_name' => $customer_name,
            'customer_tel' => $customer_tel,
            'customer_email' => $customer_email,
            //'products'  => $product_data,
            'user_id' => 40, //This is the mamboz user from Delivery App
            'order_id' => $order_id,

            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'payment_amount' => $payment_amount,
            'notify_url' => $notify_url,
            'weight' => 1,
            'type' => 1,
            'request_token' => $update_token
        );

        /*print_r($request);
        die();*/

        $context = stream_context_create(
            array('http' =>
                array(
                    'method' => "POST",
                    'header' => "Authorization: Bearer $access_token\r\n" .
                        "Content-type: application/json\r\n",
                    "Accept: application/json",
                    'content' => json_encode($request)
                )));

        //$response = file_get_contents($url, FALSE, $context);
        try {
            $response = file_get_contents($url, FALSE, $context);
        } catch (\Exception $exception) {
            $response = $exception->getMessage();
        }

        //print_r($response);
        return $response;
    }

    public function upload($key, $destination, $request)
    {
        $url = '';
        $name = '';
        $data = [];
        if (isset($request->$key)) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $extension = $file->extension();
                $name = bcrypt(time()) . '.' . $extension;
                $file->move($destination, $name);
                $url = url('/') . '/uploads/' . $name;
            }
            $data = [
                'url' => $url,
                'name' => $name
            ];
        } else {
            $data = [
                'url' => null,
                'name' => null
            ];
        }
        return $data;
    }


    public static function send_sms_message_bongolive($to, $sms, $network = '', $sender)
    {

        //======================REQUIRED INFORMATION ============================

        date_default_timezone_set('Africa/Nairobi');
        $sendername = $sender;
        $username = "kilihost";
        $password = "kili2019aa!";
        $apikey = "e5d7f7b9-b764-11e9-af97-06cba1bf0ce7";
        $mob = $to;
        $message = $sms;

        //==========================END OF REQUIRED INFORMATION ====================


        //==================OPTIONAL REQUIREMENTS =========================================

        $senddate = ""; //leave blank if you want an sms to be sent immediately or eg 31/03/2014 12:54:00 or 2014-03-31 12:54:00
        $proxy_ip = ""; //leave blank if your network environment does not support proxy
        $proxy_port = ""; //set your network port, leave black if your network environment does not support proxy

        //===================== END OF OPTIONAL REQUIREMENT ===========================


        //===============================DO NOT EDIT ANYTHING BELOW ===================

        $sendername = urlencode($sendername);
        $apiKey = urlencode($apikey);
        $destnum = urlencode($mob);
        $message = urlencode($message);


        if (!empty($senddate)) {
            $senddate = strtotime("2014-05-03 13:50:00");
        }

        $posturl = "http://www.bongolive.co.tz/api/sendSMS.php?sendername=$sendername&username=$username&password=$password&apikey=$apiKey&destnum=$destnum&message=$message&senddate=$senddate";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $posturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500); //tim

        if ($proxy_ip != "") {
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
        }
        $response = curl_exec($ch);
    }

    public function verify_client($client_id, $client_secret)
    {

    }


    public static function get_login_token_from_dash_delivery($args = [])
    {
        //header("Content-type: application/json");
        $guzzle = new Client();

        try {
            $response = $guzzle->post(self::$dash_url . '/oauth/token', [
                'body' => '{"grant_type": "password", "client_id":"' . self::$client_id . '", "client_secret": "' . self::$client_secret . '", "username": "' . $args['username'] . '", "password": "' . $args['password'] . '"}',

                'headers' => [
                    'Content-type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            return (string)$response->getBody();
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            return (string)$response->getBody();
        } catch (UnauthorizedException $e) {
            $response = $e->getResponse();
            return (string)$response->getBody();
        } catch (NotFoundHttpException $e) {
            $response = $e->getResponse();
            return (string)$response->getBody();
        }

    }


    public static function get_login_token($username, $password)
    {
        $response = self::get_login_token_from_dash_delivery(['username' => $username, 'password' => $password]);
        return $response;
    }
}
