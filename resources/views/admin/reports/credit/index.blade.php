@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
<!--<div class="row">-->
<!--    <div class="col-md-4 card-custom">-->
<!--        <div class="card-header-custom">-->
<!--            <p>Total envelope deliveries</p>-->
<!--        </div>-->
<!--        <div class="clearfix"></div>-->
<!--        <div class="card-body-custom">-->
<!--            <p>-->
<!--                @if(isset($envelope_deliveries)) {{$envelope_deliveries}} @endif-->
<!--            </p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4 card-custom">-->
<!--        <div class="card-header-custom">-->
<!--            <p>Total distance</p>-->
<!--        </div>-->
<!--        <div class="card-body-custom">-->
<!--            <p>-->
<!--                @if(isset($total_distance)) {{$total_distance}} @endif-->
<!--            </p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-md-4 card-custom">-->
<!--        <div class="card-header-custom">-->
<!--            <p>Total price</p>-->
<!--        </div>-->
<!--        <div class="card-body-custom">-->
<!--            <p>-->
<!--                @if(isset($total_price)) {{$total_price}} @endif-->
<!--            </p>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="row">
    <div class="col-lg-12 col-md-7 form-wrapper">
        <h3>Filter</h3>
        <form action="{{ url('/backend/reports/credit/filter') }}" method="get">
            <div class="row form-row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select User</label>
                        <select name="user_id" class="form-control">
                            <option value="" selected>Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    @if(isset($filters) && isset($filters['user_id']) && $user->id == $filters['user_id']) selected @endif>
                            {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Vendor</label>
                        <select name="vendor_id" class="form-control vendor" onchange="changeVendor()">
                            <option value="" selected>Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}"
                                        @if(isset($filters) && isset($filters['vendor_id']) && $vendor->id == $filters['vendor_id'] ) selected @endif>
                                    {{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Vendor Type</label>
                        <select name="vendor_type_id" class="form-control">
                            <option value="" selected>Select Vendor Type</option>
                            @foreach($vendors_type as $vendor_type)
                            <option value="{{ $vendor_type->id }}"
                                    @if(isset($filters) && isset($filters['vendor_type_id']) && $vendor_type->id == $filters['vendor_type_id'] ) selected @endif>
                            {{ $vendor_type->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Txn Type</label>
                        <select name="type" class="form-control">
                            <option value="" selected>Select Txn Type</option>
                            @foreach($txnType as $txn_type)
                            <option value="{{$txn_type}}"
                                @if(isset($filters) && isset($filters['txn_type']) && $txn_type == $filters['txn_type']) selected @endif>
                                {{ $txn_type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>from</label>
                                <input name="created_from" type="date" class="form-control"
                                       @if(isset($filters) && isset($filters['created_from']) && $filters['created_from']) value="{{$filters['created_from']}}" @endif>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>to</label>
                                <input name="created_to" type="date" class="form-control"
                                       @if(isset($filters) && isset($filters['created_to']) && $filters['created_to']) value="{{$filters['created_to']}}" @endif>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Select Transaction Id</label>
                                <input name="transaction_id" type="text" class="form-control" @if(isset($filters) && isset($filters['transaction_id'])) value="{{$filters['transaction_id']}}" @endif>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-row">
                    <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group branch-column">
                            <label>Select branch</label>
                            <select name="branch_id" class="form-control branch-select">
                                <option value="" selected>Select branch name</option>
                            @if(isset($branches) && $branches->count())
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}"
                                                @if(isset($filters) && isset($filters['branch_id']) && $branch->id == $filters['branch_id']) selected @endif>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
{{--                    @if(isset($filters) && count($filters))--}}
{{--                    <a href="/backend/reports/report-for-couriers-export-csv/{{json_encode($filters)}}" target="_blank" class="btn btn-primary">Export</a>--}}
{{--                    @else--}}
{{--                    <a href="/backend/reports/report-for-couriers-export-csv/{{json_encode([])}}" target="_blank" class="btn btn-primary">Export</a>--}}
{{--                    @endif--}}
                </div>
            </div>
        </form>
    </div>
</div>

<div style="overflow-x: scroll;font-size:11px">
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Transaction ID</th>
            <th>User Name</th>
            <th>Vendor Name</th>
            <th>Vendor Type</th>
            <th>Txn type </th>
            <th>Branches</th>
            <th>Amount</th>
            <th>Created At</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($credits) && $credits->count())
        @foreach ($credits as $credit)
        <tr>
            <td>{{$credit->transaction_id ? $credit->transaction_id : 'N/A'}}</td>
            <td>{{$credit->user ? $credit->user->name : 'N/A'}}</td>
            <td>{{$credit->vendor ? $credit->vendor->name : 'N/A'}}</td>
            <td>{{$credit->vendor ? $credit->vendor->vendor_type->vendor_name : 'N/A'}}</td>
            <td>{{$credit->txn_type ? $credit->txn_type  : 'N/A'}}</td>
            <td>{{ $credit->branche ? $credit->branche->name:'N/A' }}</td>
            <td>{{$credit->amount ? $credit->amount : 'N/A'}}</td>
            <td>{{$credit->created_at  ? $credit->created_at : 'N/A'}}</td>


        </tr>
        @endforeach
        @else
        <tr><td colspan="6">No Credits orders</td></tr>
        @endif
        </tbody>
    </table>
</div>
<div>
    <nav>
        @if(isset($filters))
        {{isset($credits)?$credits->appends($filters)->links():''}}
        @else
        {{isset($credits)?$credits->links():''}}
        @endif
    </nav>
</div>
<div class="row">
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        function changeStatus(status, orderId) {
            document.getElementById('status-input-' + orderId).value = status;
            document.getElementById("status-form-" + orderId).submit();
        }
        let changeVendor=()=>{
            let vendor = document.getElementsByClassName('vendor')[0];
            sendAjax(vendor.value);
            // console.log()
        }

        let sendAjax = (id) =>{
            $.ajax({
                url:'/backend/reports/credit/get-vendor-branches',
                method:'get',
                data:{id:id},
                success: function(response) {
                    let option= '';
                    if(response.length){
                        option='<option value selected >Select branch name</option>';
                        for (let i in response){
                            option += '<option value="'+response[i].branch_id+'">'+response[i].name+'</option>';
                        }
                    }else{
                        option='<option value selected disabled>Branches not found</option>';
                    }
                    document.getElementsByClassName('branch-column')[0].classList.remove("hidden");
                    let select = document.getElementsByClassName('branch-select')[0];
                    select.innerHTML = option;
                    select.removeAttribute('disabled');
                },
                error: function(xhr) {
                    //Do Something to handle error
                }
            });

        }
    </script>
@endpush

