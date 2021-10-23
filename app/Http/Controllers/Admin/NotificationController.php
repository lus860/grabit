<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\SSJUtils;
use App\Mail\SendCustomerEmail;
use App\Mail\SendAdminEmail;

class NotificationController extends Controller {
    public $order_id;
    public function __construct($order_id){
        $this->order_id = $order_id;
    }

    public function setOrderId($order_id){
        $this->order_id=$order_id;
    }
    public function getOrderId(){
        return $this->order_id;
    }
    public function send_admin(){
        $name = 'Said';
        $data['admin'] = "Administrator";
        $data['name'] = $name;
        $data['order'] = SSJUtils::get_order($this->order_id);
        try {
            Mail::to('said9923@gmail.com')->send(new SendAdminEmail($data));
        }catch(\Swift_TransportException $exception){

        }
        //return view('emails.admin_notification', ['data'=>$data]);
    }
    public function send_customer(){
        $order = SSJUtils::get_order($this->order_id);
        $name = 'Said';
        $data['admin'] = "Administrator";
        $data['name'] = $name;
        $data['order'] = $order;

        $data['sub_total'] = $order['amount'];
        $data['shipping_charges'] = 3000;
        $data['promotion'] = 0;
        $data['order_total'] = $order['amount']+5000;

        try{
            Mail::to($order['customer']->email)->send(new SendCustomerEmail($data));
        }catch(\Swift_TransportException $exception){

        }
        //return view('emails.admin_notification', ['data'=>$data]);
    }
}
