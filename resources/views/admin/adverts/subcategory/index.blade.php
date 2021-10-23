@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <a href="{{url('backend/add-sub-category')}}" class="btn btn-primary">Add Subcategories</a>
    <a href="{{url('backend/manage-categories')}}" class="btn btn-primary">Back</a>

    <hr>
    <div style="overflow-x: scroll;font-size: 11px">

        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Category name</th>
                <th>Subcategory name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($subcategory->count())
                @foreach ($subcategory as $item)
                <tr class="bg-info">
                    <td>{{$item->category?$item->category->name:'N/A'}}</td>
                    <td>{{$item->name}}</td>
                    <td><a href="{{url("/backend/subcategory/$item->id")}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>                            {{--<a href="{{route('cuisines.show',$cuisine->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}
                        <div style="display: inline-block; margin-left: 5px;">
                            <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$item->id}}"><i class="fa fa-trash-o"></i></button>
                        </div>
                        <div class="modal fade" id="modal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                            <div class="modal-dialog modal-m modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 10px;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="myModalLabel">Did you want Delete item?</h3>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['subcategory-delete', 'id'=>$item->id]]) !!}
                                        <button type="submit" class="btn btn-danger ">Yes</button>
                                        {!! Form::close() !!}
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            @else
                <tr><td colspan="6">No data available</td></tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
