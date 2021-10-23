@extends('admin.backend/tblTemplate')
@section('title',$title)
@section('body')
    @include('messages/flash_message')
    <a href="{{url('backend/loyalty/create')}}" class="btn btn-primary">Add loyalty</a>
    <hr>
    <table class="ssj-table">
        <thead>
        <tr class="bg-info">
            <th>Business name</th>
            <th>Business type</th>
            <th>Total spend</th>
            <th>Branches</th>
            <th>Status</th>
            <th>Redemption amount</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($loyalties)>0)
            @foreach ($loyalties as $loyalty)
                <tr class="bg-info">
                    <td>{{$loyalty->vendor?$loyalty->vendor->name:($loyalty->spend?\App\Models\VendorTypes::find(1)->vendor_name:'N/A')}}</td>
                    <td>{{$loyalty->vendor?$loyalty->vendor->vendor_type->vendor_name:($loyalty->spend?\App\Models\VendorTypes::find(1)->vendor_name:'N/A')}}</td>
                    <td>{{$loyalty->spend}}</td>
                    <td>{{ $loyalty->branches? implode(" , " ,$loyalty->branches_name()):'N/A' }}</td>
                    <td>{{ $loyalty->status?'Active':'Disabled' }}</td>

                    <td>{{$loyalty->redemption}}</td>
                    <td>

                    <a href="{{url('/backend/loyalty/edit/'.$loyalty->id)}}" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <div style="display: inline-block; margin-left: 5px;">
                        <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$loyalty->id}}"><i class="fa fa-trash-o"></i></button>
                    </div>
                    <a href="{{url('/backend/loyalty/branches/'.$loyalty->id)}}" class="btn btn-primary"><i class="fa fa-code-fork" aria-hidden="true"></i></a>
{{--                    <div style="display: inline-block; margin-left: 5px;">--}}
{{--                         <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$loyalty->id}}"><i class="fa fa-code-fork" aria-hidden="true"></i></button>--}}
{{--                    </div>--}}

                    <div class="modal fade" id="modal_{{$loyalty->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                        <div class="modal-dialog modal-m modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 10px;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h3 class="modal-title" id="myModalLabel">Did you want Delete item?</h3>
                                </div>
                                <div class="modal-footer">
                                    {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['loyalty-destroy',$loyalty->id]]) !!}
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
            <tr><td colspan="6">No loyalty defined.</td></tr>
        @endif
        </tbody>
    </table>
    <div>
        <nav>
            {{$loyalties->links()}}
        </nav>
    </div>
    <div class="row">
    </div>
@endsection
@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/custom.css')}}" >
@endpush
