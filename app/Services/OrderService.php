<?php


namespace App\Services;


use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemOptionValue;
use App\Models\PendingOrders;

class OrderService
{
    /**
     * @param $order
     * @param $action
     * @return float|int|mixed|string
     */
    public static function getOrderCollectionAmount($order,$action){
        if (gettype($order) == 'array'){
            if (isset($order['id'])){
                $order = PendingOrders::find($order['id']);
            }else{
                return 'order must be collection or must included id attribute';
            }
        }
        $price=0;
        if ($action){
            $price = isset($action['discounted_price'])?$action['discounted_price']:($order->order_total);
        }
        if($price) {
            if ($order->order_type == 1) {
                return $price - (($price * $order->get_vendor->delivery_commission) / 100);
            } elseif ($order->order_type == 2) {
                return $price - (($price * $order->get_vendor->collection_commission) / 100);
            } elseif ($order->order_type == 3) {
                return $price - (($price * $order->get_vendor->dine_commission) / 100);
            }
        }
        return 0;
    }

    public static function changeOrderActionToStings($action){
        if (is_array($action)){
            foreach ($action as $key=>$value){
                $action[$key]['category_id'] = Menu::where('id',$value['category_id'])->first()->name;
                $action[$key]['item_id'] = MenuItem::where('id',$value['item_id'])->first()->name;
                if (isset($value['add_ons']) && is_array($value['add_ons'])){
                    foreach ($value['add_ons'] as $key1=>$add_on){
                        $action[$key]['add_ons'][$key1] = MenuItemOptionValue::find($add_on)->value;
                    }
                }
                if (isset($value['variants']) && is_array($value['variants'])){
                    foreach ($value['variants'] as $key2=>$variant){
                        $action[$key]['variants'][$key2] = MenuItemOptionValue::find($variant)->value;
                    }
                }
            }
        }
       return $action;
    }

}
