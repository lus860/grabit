@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <div class="filter-row">
        <script type="text/javascript">
            $(function(){
                $('#restaurant_id').change(function(){
                    var id = $(this).val();
                    if(id !== '') {
                        window.location.href = '{!! route('menu.index') !!}?vendor_id=' + id;
                    }
                });
            });
        </script>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <select name="restaurant_id" class="form-control" id="restaurant_id">
                <option value="" disabled selected>Select Vendor</option>
                @foreach($restaurants as $restaurant)
                    @if($current_restaurant_id == $restaurant->id)
                        <option selected value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                    @else
                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <a href="{{route('menu.create', $current_restaurant_id?['vendor_id'=>$current_restaurant_id]:[])}}" class="btn btn-primary pull-right">Add New Menu</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>ID</th>
            <th>Sort Number</th>
            <th>Menu Name</th>
            <th>Vendor Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th colspan="1" style="width: 160px">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($menus)>0)
            @foreach ($menus as $menu)

                <tr class="bg-info">
                    <td>{{$menu->id}}</td>
                    <td>{{$menu->sort_id}}</td>
                    <td>{{$menu->name}}</td>
                    <td>{{$menu->get_vendor->name}}</td>
                    <td>{{$menu->getStartTimeAttribute()}}</td>
                    <td>{{$menu->getEndTimeAttribute()}}</td>
                    <td>
                        <a href="{{route('menu.edit',['menu'=>$menu['id']])}}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                        <div style="display: inline-block; margin-left: 5px;">
                            <form action="{{route('menu.destroy', $menu->id)}}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit" class= 'btn btn-danger'><i class="fa fa-trash-o"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="6">No data available</td></tr>
        @endif
        </tbody>
    </table>
@endsection
