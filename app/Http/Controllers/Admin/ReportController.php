<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Csv2;
use App\Http\Controllers\Controller;
use App\Models\Carrier;
use App\Models\City;
use App\Models\CourierOrders;
use App\Models\CreditHistory;
use App\Models\Restaurant;
use App\Models\Riders;
use App\Models\VendorBranch;
use App\Models\VendorTypes;
use App\Services\StatusesHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Excel;
use App\User;
use function GuzzleHttp\Promise\all;

class ReportController extends Controller
{
    public function index()
    {
        $riders = Riders::all();
        $cities = City::all();
        $carriers = Carrier::all();
        $couriers_orders = CourierOrders::paginate(10);
        $data = [
            'title' => 'Reports',
            'riders' => $riders,
            'cities' => $cities,
            'carriers' => $carriers,
            'orders' => $couriers_orders,
        ];
        return view('admin.reports.couriers.index', $data);
    }

    public function filter(Request $request)
    {
        $riders = Riders::all();
        $cities = City::all();
        $carriers = Carrier::all();
        $filer_data = $this->filter_courier_orders($request);
        $orders = $this->filter_courier_orders($request, false);
        $envelope_deliveries = 0;
        $total_distance = 0;
        $total_price = 0;
        if (isset($orders['orders']) && $orders['orders']->count()) {
            foreach ($orders['orders'] as $order) {
                if ($order->envelope) {
                    $envelope_deliveries++;
                }
                $total_distance += $order->price;
                $total_price += $order->distance;
            }
        }
        $data = [
            'title' => 'Reports',
            'riders' => $riders,
            'cities' => $cities,
            'total_price' => number_format($total_price, 2),
            'envelope_deliveries' => $envelope_deliveries,
            'total_distance' => $total_distance,
            'carriers' => $carriers,
            'orders' => $filer_data['orders'],
            'filters' => $filer_data['filters'],
            'statuses' => $filer_data['statuses']
        ];
        return view('admin.reports.couriers.index', $data);
    }

    public function credits(Request $request)
    {
        $credits = CreditHistory::paginate(10);

        $data = [
            'title' => 'Report about credits',
            'credits' => $credits,
            'users' => User::where('name', '!=', null)->get()->all(),
            'vendors' => Restaurant::all(),
            'vendors_type' => VendorTypes::all(),
            'txnType' => ['Shopped', 'Redeemed']
        ];

        return view('admin.reports.credit.index', $data);
    }

    public function filter_credit(Request $request)
    {
        $filer_data = $this->filter_credits_data($request);

        $data = [
            'title' => 'Report about credits',
            'users' => User::where('name', '!=', null)->get()->all(),
            'vendors' => Restaurant::all(),
            'vendors_type' => VendorTypes::all(),
            'txnType' => ['Shopped', 'Redeemed'],
            'credits' => $filer_data['credits'],
            'filters' => $filer_data['filters'],
            'branches' => $filer_data['branches']??null,
        ];
        return view('admin.reports.credit.index', $data);
    }

    public function export_to_csv($filters)
    {
        $filters = json_decode($filters);
        $orders = $this->filter_courier_orders(($filters), false)['orders'];
        foreach ($orders as $key => $value) {
            $value->user_id = $value->user ? $value->user->name : $value->user_id;
            $value->pick_up_city = $value->pickUpCity ? $value->pickUpCity->name : 'N/A';
            $value->delivery_area = $value->deliveryArea ? $value->deliveryArea->name : 'N/A';
            if ($value->deliveryCity) {
                $value->delivery_city = $value->deliveryCity->name;
            }
            $value->rider_name = $value->rider ? $value->rider->name : $value->rider;
            $value->carrier = $value->carrierRelation ? $value->carrierRelation->carrier_name : $value->carrier;
            $value->parcel_type = $value->parcelType ? $value->parcelType->parcel_name : $value->parcel_type;
            $value->payment = $value->payment ? config('api.courier_order.payment')[$value->payment]['title'] : 'N/A';
            $value->status = config('api.courier_order.status')[$value->status];
            $value->status_text = $value->status_text ? $value->status_text : 'N/A';
            unset($value['user']);
            unset($value['pickUpCity']);
            unset($value['deliveryArea']);
            unset($value['rider']);
            unset($value['deliveryCity']);
            unset($value['carrierRelation']);
            unset($value['parcelType']);
        }
        $headers = array_keys($orders->toArray()[0]);

        $data = [$headers];
        foreach ($orders->toArray() as $order) {
            $data[] = $order;
        }
        return (new Csv2($data))->download('invoices.xlsx');
    }

    /**
     * @param $request
     * @param bool $paginate
     * @return array
     */
    private function filter_courier_orders($request, $paginate = true)
    {
        $data = [];
        $where = [];
        if (isset($request->rider_name) && $request->rider_name) {
            $where['rider_name'] = $request->rider_name;
        }
        if (isset($request->delivery_city) && $request->delivery_city) {
            $where['delivery_city'] = $request->delivery_city;
        }
        if (isset($request->pick_up_city) && $request->pick_up_city) {
            $where['pick_up_city'] = $request->pick_up_city;
        }
        if (isset($request->status) && $request->status) {
            $where['status'] = $request->status;
        }
        if (isset($request->carrier) && $request->carrier) {
            $where['carrier'] = $request->carrier;
        }
        if ((isset($request->created_from) && $request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdFrom = $request->created_from;
            $createdTo = $request->created_to;
            $orders = CourierOrders::where($where)
                ->whereBetween('created_at', [$createdFrom, $createdTo])
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }

            if ($orders->count()) {
                foreach ($orders as $order) {
                    $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id, 'courier');
                }
            }
            $where['created_from'] = $createdFrom;
            $where['created_to'] = $createdTo;

            $data = [
                'orders' => $orders,
                'filters' => $where,
                'statuses' => $statuses ?? []
            ];
            return $data;
        } else if ((isset($request->created_from) && $request->created_from) && (!isset($request->created_to) || !$request->created_to)) {
            $createdFrom = $request->created_from;
            $orders = CourierOrders::where($where)
                ->where('created_at', '>=', $createdFrom)
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            if ($orders->count()) {
                foreach ($orders as $order) {
                    $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id, 'courier');
                }
            }
            $where['created_from'] = $createdFrom;
            $data = [
                'orders' => $orders,
                'filters' => $where,
                'statuses' => $statuses ?? []
            ];
            return $data;
        } elseif ((!isset($request->created_from) || !$request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdTo = $request->created_to;
            $orders = CourierOrders::where($where)
                ->where('created_at', '<=', $createdTo)
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            if ($orders->count()) {
                foreach ($orders as $order) {
                    $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id, 'courier');
                }
            }
            $where['created_to'] = $createdTo;

            $data = [
                'orders' => $orders,
                'filters' => $where,
                'statuses' => $statuses ?? []
            ];
            return $data;
        }


        if ((isset($request->created_from) && $request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdFrom = $request->created_from;
            $createdTo = $request->created_to;

            $orders = CourierOrders::where($where)
                ->whereBetween('created_at', [$createdFrom, $createdTo])
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            if ($orders->count()) {
                foreach ($orders as $order) {
                    $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id, 'courier');
                }
            }

            $data = [
                'orders' => $orders,
                'filters' => $where,
                'statuses' => $statuses ?? []
            ];
            return $data;
        }
        $orders = CourierOrders::where($where)
            ->orderBy('created_at', 'desc');
        if ($paginate) {
            $orders = $orders->paginate(10);
        } else {
            $orders = $orders->get();
        }
        if ($orders->count()) {
            foreach ($orders as $order) {
                $statuses[$order->id] = StatusesHistoryService::get_history_statuses($order->id, 'courier');
            }
        }

        $data = [
            'orders' => $orders,
            'filters' => $where,
            'statuses' => $statuses ?? []
        ];
        return $data;
    }

    public function export(array $data)
    {

        $keys = array_keys($data[0]);

        $filename = "tweets.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, $keys);

        foreach ($data as $row) {
            fputcsv($handle, array_values($row));
        }

        fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download($filename, 'tweets.csv', $headers);
    }

    private function filter_credits_data($request, $paginate = true)
    {
        $data = [];
        $where = [];
        if (isset($request->user_id) && $request->user_id) {
            $where['user_id'] = $request->user_id;
        }
        if (isset($request->vendor_id) && $request->vendor_id) {
            $where['vendor_id'] = $request->vendor_id;
        }
        if (isset($request->type) && $request->type) {
            $where['txn_type'] = $request->type;
        }
        if (isset($request->transaction_id) && $request->transaction_id) {
            $where['transaction_id'] = $request->transaction_id;
        }
        if (isset($request->vendor_type_id) && $request->vendor_type_id) {
            $where['vendor_type_id'] = $request->vendor_type_id;
        }
        if (isset($request->branch_id) && $request->branch_id) {
            $where['branch_id'] = $request->branch_id;
            $branch = VendorBranch::find($request->branch_id);
            if ($branch){
                $brances = VendorBranch::where('vendor_id',$branch->vendor_id)->get();
            }
        }
        if ((isset($request->created_from) && $request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdFrom = $request->created_from;
            $createdTo = $request->created_to;
            $orders = CreditHistory::where($where)
                ->whereBetween('created_at', [$createdFrom, $createdTo])
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            $where['created_from'] = $createdFrom;
            $where['created_to'] = $createdTo;

            $data = [
                'credits' => $orders,
                'filters' => $where,
                'branches' => $brances??null,
            ];
            return $data;
        } else if ((isset($request->created_from) && $request->created_from) && (!isset($request->created_to) || !$request->created_to)) {
            $createdFrom = $request->created_from;
            $orders = CreditHistory::where($where)
                ->where('created_at', '>=', $createdFrom)
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            $where['created_from'] = $createdFrom;
            $data = [
                'credits' => $orders,
                'filters' => $where,
                'branches' => $brances??null,

            ];
            return $data;
        } elseif ((!isset($request->created_from) || !$request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdTo = $request->created_to;
            $orders = CreditHistory::where($where)
                ->where('created_at', '<=', $createdTo)
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            $where['created_to'] = $createdTo;

            $data = [
                'credits' => $orders,
                'filters' => $where,
                'branches' => $brances??null,

            ];
            return $data;
        }
        if ((isset($request->created_from) && $request->created_from) && (isset($request->created_to) && $request->created_to)) {
            $createdFrom = $request->created_from;
            $createdTo = $request->created_to;

            $orders = CreditHistory::where($where)
                ->whereBetween('created_at', [$createdFrom, $createdTo])
                ->orderBy('created_at', 'desc');
            if ($paginate) {
                $orders = $orders->paginate(10);
            } else {
                $orders = $orders->get();
            }
            $data = [
                'credits' => $orders,
                'filters' => $where,
                'branches' => $brances??null,

            ];
            return $data;
        }
        $orders = CreditHistory::where($where)
            ->orderBy('created_at', 'desc');
        if ($paginate) {
            $orders = $orders->paginate(10);
        } else {
            $orders = $orders->get();
        }
        $data = [
            'credits' => $orders,
            'filters' => $where,
            'branches' => $brances??null,

        ];
        return $data;
    }

    public function filter_credit_get_branches(Request $request){
        if (isset($request->id)){
            $vendor = VendorBranch::where('vendor_id',$request->id)->select(['id as branch_id','name'])->get();
            if ($vendor->count()){
                return response()->json($vendor->toArray());
            }
            return response()->json();
        }
        return response()->json();
    }
}
