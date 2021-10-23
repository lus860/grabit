@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    @include('errors.error_layout')
    <div style="overflow-x: scroll;font-size:11px">
        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Transaction ID</th>
                <th>User Name</th>
                <th>Vendor type</th>
                <th>Vendor Name</th>
                <th>Vendor rating</th>
                <th>Vendor rating message</th>
                <th>Delivery rating</th>
                <th>Delivery rating message</th>
                <th>Order time</th>
                <th>Delivery_time</th>
                <th>Created_at</th>
                <th>Action</th>
                {{--<th>Created At</th>--}}
    {{--            <th colspan="1" width="160">Actions</th>--}}
            </tr>
            </thead>
            <tbody>
            @if(count($ratings)>0)
                @foreach ($ratings as $key=>$rating)
                    @php if ($rating->seen == null) $seen[]=$rating->id @endphp
                    <tr class="bg-info @if($rating->seen == null) bg-seen @endif">
                        <td>{{$rating->transaction_id??'N/A'}}</td>
                        <td>{{!empty($rating->user)?$rating->user->name??'N/A': 'N/A'}}</td>
                        <td>{{!empty($rating->vendor)?(isset($rating->vendor->vendor_type)?$rating->vendor->vendor_type->vendor_name:'N/A'): 'N/A'}}</td>
                        <td>{{!empty($rating->vendor)?$rating->vendor->name: 'N/A'}}</td>
                        <td>{{$rating->vendor_rating??'N/A'}}</td>
                        <td>{{$rating->vendor_rating_name??'N/A'}}</td>
                        <td>{{$rating->delivery_rating??'N/A'}}</td>
                        <td>{{$rating->delivery_rating_name??'N/A'}}</td>
                        <td>{{!empty($rating->order)?$rating->order->created_at:'N/A'}}</td>
                        <td>{{$rating->deliveried}}</td>
                        <td>{{$rating->created_at}}</td>
                        <td>
                            <a href="{{url("/backend/orders/ratings/$rating->id")}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>                            {{--<a href="{{route('cuisines.show',$cuisine->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}
                            <div style="display: inline-block; margin-left: 5px;">
                                <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$rating->id}}"><i class="fa fa-trash-o"></i></button>
                            </div>
                            <div class="modal fade" id="modal_{{$rating->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                                <div class="modal-dialog modal-m modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 10px;">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h3 class="modal-title" id="myModalLabel">Did you want Delete item?</h3>
                                        </div>
                                        <div class="modal-footer">
                                            {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['ratings-delete', 'id'=>$rating->id]]) !!}
                                            <button type="submit" class="btn btn-danger ">Yes</button>
                                            {!! Form::close() !!}
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php if (isset($seen) && $ratings->count()-1 == $key){
                        \App\Models\RatingsVendors::whereIn('id',$seen)->update(['seen'=>1]);
                    }
                    @endphp
                @endforeach
            @else
                <tr><td colspan="6">No data available</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div>
        <nav>
            {{$ratings->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
