@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
            @if(isset($data))
                @foreach($data as $menu)
                        <div class="ssj-form-wrapper">
            <div class="col-lg-12 col-md-12 form-wrapper">
                <h3>Restaurant: {{$menu['restaurant_name']}}</h3>
                <div class="row form-row">
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12">
                            <p>Category: {{$menu['name']}}</p>
                        </div>
                        <div class="form-group col-lg-12 col-md-12">
                            <p>
                                <span>Start time:{{$menu['end_time']}}</span>
                                <span style="margin-left: 150px;">End time:{{$menu['start_time']}}</span>
                            </p>
                        </div>
                        <div class="form-group col-lg-12 col-md-12">
                            <p>Availability:
                                @if(isset($menu['day_id']))
                                    @foreach($menu['day_id'] as $key=>$menuDay)
                                    @foreach($days as $day)
                                        @if($menuDay == $day['id'])
                                        {{($key+1 == count($menu['day_id']))?$day['long_name']:$day['long_name'].', '}}
                                        @endif
                                    @endforeach
                                    @endforeach
                                @else
                                {{config('menu.availability.all_days')}}
                                @endif
                            </p>
                        </div>
                        <table class="ssj-table">
                            <thead>
                            <tr class="bg-info">
                                <th>Type</th>
                                <th>Item Name</th>
                                <th>Max order quantity</th>
                                <th>Container price</th>
                                <th>Offer price</th>
                                <th>Special offer</th>
                                <th>Popular item</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody style="text-align:center">
                            @if(count($menu['menu_items'])>0)
                                @foreach ($menu['menu_items'] as $item)
                                    <tr class="bg-info">
                                        <td>
                                            @if($item['type'] == 1)
                                                <img width="20px" src="{{asset('admin/images/veg.png')}}" alt="Veg image">
                                            @else
                                                <img width="20px" src="{{asset('admin/images/non_veg.png')}}" alt="Veg image">
                                            @endif
                                        </td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['max_quantity']}}</td>
                                        <td>{{$item['container_price']}}</td>
                                        <td>{{$item['offer_price']}}</td>
                                        <td>{{$item['special_offer']}}</td>
                                        <td>{{$item['popular_item']}}</td>
                                        <td>{{$item['price']}}</td>
                                    </tr>
                                    <tr style="text-align:left">
                                        <td></td>
                                        <td colspan="7"><small>{{$item['description']}}</small></td>
                                    </tr >
                                    @if(count($item['options'])>0)
                                        @foreach($item['options'] as $option)
                                            <tr style="text-align:left">
                                                <td colspan="8">
                                                <p>{{$option['name'].' ('.config('menu.types.'.$option['type'].'.name').')'}}
                                                    @if($option['type'] =='addon')
                                                        <span style="margin-left: 20px;"> ={{$option['item_maximum']}} maximum</span>
                                                    @endif
                                                </p>
                                                    <ul style="list-style-type:none">
                                                        @foreach($option['values'] as $value)
                                                        <li>+ {{$value['value']}} <span style="margin-left: 150px;">{{$value['price']}}</span></li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                <tr><td colspan="6">No data available</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
                @endforeach
            @endif

    {{--    @include('errors.error_layout')--}}
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
