@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    <a href="{{url('backend/add-category')}}" class="btn btn-primary">Add category</a>
    <a href="{{url('backend/sub-category')}}" class="btn btn-primary">Subcategories</a>
    <hr>
    <div style="overflow-x: scroll;font-size: 11px">
        <table class="ssj-table">
            <thead>
            <tr class="bg-info">
                <th>Name</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($categories->count())
            @foreach ($categories as $category)
                <tr class="bg-info">
                    <td>{{$category->name}}</td>
                    <td><img src="{{$category->image}}" alt="" width="50"></td>
                    <td> <a href="{{url("/backend/category/$category->id")}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>                            {{--<a href="{{route('cuisines.show',$cuisine->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>--}}
                        <div style="display: inline-block; margin-left: 5px;">
                            <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$category->id}}"><i class="fa fa-trash-o"></i></button>
                        </div>
                        <div class="modal fade" id="modal_{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                            <div class="modal-dialog modal-m modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 10px;">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h3 class="modal-title" id="myModalLabel">Did you want Delete item?</h3>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['category-delete', 'id'=>$category->id]]) !!}
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
