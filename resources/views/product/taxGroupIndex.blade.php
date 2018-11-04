@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
{{--@section('header')--}}
{{--@include('layouts.header')--}}
{{--@endsection--}}
@section('menu')
    @include('layouts.inventoryMenu')
@endsection

@section('content')
    @include('partials.flashmessage')

    <div style="height: 25px"></div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New Group</button>
            </div>
        </div>
    </div>


    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Tax Group</h3>
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/tax.group.new') }}">
                    {{ csrf_field() }}

                <div class="modal-body">

                        <div class="form-group{{ $errors->has('taxGroupName') ? ' has-error' : '' }}">
                            <label for="taxGroupName" class="col-md-4 control-label">Tax Group Name</label>

                            <div class="col-md-6">
                                <input id="taxGroupName" type="text" class="form-control" name="taxGroupName" value="" required autofocus>

                                @if ($errors->has('taxGroupName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('taxGroupName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax_id') ? ' has-error' : '' }}">
                            <label for="tax_id" class="col-md-4 control-label">1. TAX Name</label>

                            <div class="col-md-6">
                                {!! Form::select('tax_id',([''=>'Please Select'] + $taxes ), null , array('id' => 'tax_id', 'class' => 'form-control','required')) !!}
                                @if ($errors->has('tax_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tax_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax1_id') ? ' has-error' : '' }}">
                            <label for="tax1_id" class="col-md-4 control-label">2. TAX Name</label>

                            <div class="col-md-6">
                                {!! Form::select('tax1_id',([NULL=>'Please Select'] + $taxes ), null , array('id' => 'tax1_id', 'class' => 'form-control')) !!}
                                @if ($errors->has('tax1_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tax1_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax2_id') ? ' has-error' : '' }}">
                            <label for="tax2_id" class="col-md-4 control-label">3. TAX Name</label>

                            <div class="col-md-6">
                                {!! Form::select('tax2_id',([NULL=>'Please Select'] + $taxes ), null , array('id' => 'tax2_id', 'class' => 'form-control')) !!}
                                @if ($errors->has('tax2_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tax2_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-1">
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="taxgrp-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Tax Grp ID</th>
                    <th>Tax Grp Name</th>
                    <th>TAX : 1</th>
                    <th>TAX : 2</th>
                    <th>TAX : 3</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


@endsection

@push('scripts')

<script>
    $(function() {
        var table= $('#taxgrp-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'tax.group.data',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'taxGroupName', name: 'taxGroupName' },
                { data: 'taxid.taxName', name: 'taxid.taxName' },
                { data: 'tax1id.taxName', name: 'tax1id.taxName', defaultContent: ''},
                { data: 'tax2id.taxName', name: 'tax2id.taxName', defaultContent: ''},
                { data: 'status', name: 'status', orderable: false, searchable: false, printable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#taxgrp-table').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();

        if(document.getElementById('deleteData').value == 0)
        {
            alert('You Do Not Have Permission. Please Contact Administrator')
            return false
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = $(this).data('remote');
        // confirm then
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true},

            error: function (request, status, error) {
                alert(request.responseText);
            }

        }).always(function (data) {
            $('#taxgrp-table').DataTable().draw(false);
        });
    });

    $('#taxgrp-table').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        if(document.getElementById('editData').value == 0)
        {
            alert('You Do Not Have Edit Permission. Please Contact Administrator')
            return false
        }
    });

    $(document).ready ( function () {
        //replace document below with enclosing container but below will work too
        $(document).on('click', '.btn-new', function () {
            if(document.getElementById('addData').value == 1)
            {
                $("#modal-register").modal()
            }else {
                alert('You Do Not Have Permission. Please Contact Administrator')
                return false
            }
        });
    });


</script>

@endpush