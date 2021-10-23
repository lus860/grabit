@extends('admin.backend.tblTemplate')
@section('title',$title)
@section('body')
    @include('messages.flash_message')
    @include('errors.error_layout')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <form action="{{url('/backend/loyalty/branches/'.$loyalty->id)}}"  method="post">
                @csrf
                <div class="ssj-form-wrapper ">
                    <div class="col-lg-12 col-md-12 form-wrapper">
                        <div class="row form-row">
                            <div class="row" >
                                <div class="form-group col-lg-12 col-md-12" id="branches">
                                    <div class="col-md-8" id="branch">
                                        <label class="">Branche name</label>
                                        <input placeholder="Write Branch name" value="" name="name[]" type="text" class="form-control "/>
                                        <input name="vendor_id" type="hidden" value="{{$loyalty->vendor_id}}" class="form-control"/>
                                    </div>
                                    <div class="col-md-4 text-right" >
                                        <label class="">Add branch name</label>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="clone">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-xs-12">
            <table class="ssj-table">
                <thead>
                <tr class="bg-info">
                    <th class="text-center">Branch names</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($branches)>0)
                    @foreach ($branches as $branch)
                        <tr class="bg-info text-center">
                            <td>{{$branch->name?:'N/A'}}</td>
                            <td>

                                <div style="display: inline-block; margin-left: 5px;">
                                    <button type="button" class= 'btn btn-danger' style="background: red !important;" data-toggle="modal" data-target="#modal_{{$branch->id}}"><i class="fa fa-trash-o"></i></button>
                                </div>

                                <div class="modal fade" id="modal_{{$branch->id}}" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                                    <div class="modal-dialog modal-m modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 10px;">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h3 class="modal-title" id="myModalLabel">Did you want Delete item?</h3>
                                            </div>
                                                <div class="modal-footer">
                                                     {!! Form::open(['method' => 'DELETE','class'=>'form-accept-modal' ,'route'=>['branch-destroy',$branch->id]]) !!}
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
                    <tr><td colspan="6">No branches defined.</td></tr>
                @endif
                </tbody>
            </table>
        </div>

    </div>
  @endsection

@push('head')
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
@endpush
@push('js')
    <script>
        $("#clone").click(function(){
            var itm = document.getElementById("branch");
            var cln = itm.cloneNode(true);
            document.getElementById("branches").appendChild(cln);
        })
    </script>
@endpush
