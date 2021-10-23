<?php


namespace App\Traits;


use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemOptionValue;

trait OrderResources
{

    public function __construct()
    {

    }

    public function getMenu($orders){
        $newData=[];
        if (is_array($orders)) {
            foreach ($orders as $key => $order) {
                if (isset($order['price'])){
                    $newData['price']=$order['price'];
                }
                if (isset($order['category_id']) && $order['category_id']) {
                    $newData[$key]['category_name'] = Menu::find($order['category_id'])->name;
                    $newData[$key]['item_name'] = MenuItem::find($order['item_id'])->name;
                    if (isset($order['add_ons']) && $order['add_ons']) {
                        foreach ($order['add_ons'] as $add_on) {
                            $response = MenuItemOptionValue::with('menuOption')->find($add_on);
                            if ($response->count()) {
                                $newData[$key]['add_ons']['title'] = $response->menuOption->name;
                                $newData[$key]['add_ons'][] = $response->value;
                            }
                        }
                    }
                    if (isset($order['variants']) && $order['variants']) {
                        foreach ($order['variants'] as $variant) {
                            $response = MenuItemOptionValue::with('menuOption')->find($variant);
                            if ($response->count()) {
                                $newData[$key]['variants']['title'] = $response->menuOption->name;
                                $newData[$key]['variants'][] = $response->value;
                            }
                        }
                    }
                    $newData[$key]['quantity'] = $order['quantity'];
                    $newData[$key]['price'] = $this->getPrice($order);
                }

            }
        }
        return $newData;
    }

    /**
     * @param $order
     * @return float|int
     */
    private function getPrice($order)
    {
        if (is_array($order)) {
            $sum = 0;
            $simpleSum = 0;
            $menu = MenuItem::where('id', $order['item_id'])->first();
            if (!empty($menu)) {
                $simpleSum = $menu->price;
            }
            if (is_array($order['add_ons']) && !empty($order['add_ons'])) {
                foreach ($order['add_ons'] as $value) {
                    $item = MenuItemOptionValue::where('id', $value)->first();
                    if (!empty($item)) {
                        $simpleSum += $item->price;
                    }
                }
            } else {
                $item = MenuItemOptionValue::where('id', $order['add_ons'])->first();
                if (!empty($item)) {
                    $simpleSum += $item->price;
                }
            }
            if (is_array($order['variants']) && !empty($order['variants'])) {
                foreach ($order['variants'] as $value) {
                    $item = MenuItemOptionValue::where('id', $value)->first();
                    if (!empty($item)) {
                        $simpleSum += $item->price;
                    }
                }
            } else {
                $item = MenuItemOptionValue::where('id', $order['variants'])->first();
                if (!empty($item)) {
                    $simpleSum += $item->price;
                }
            }
            if (isset($order['order_type']) && $order['order_type'] == 2 && !empty($menu)) {
                $simpleSum += $menu->container_price;
            }
            $sum += $simpleSum * $order['quantity'];
            return $sum;
        }
        return false;
    }
}
