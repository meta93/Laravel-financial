@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.inventoryMenu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
        <div class="well text-center" style="height: 50%; background-color: #f0f0ef">
            <p><strong>ADD EDIT UPDATE DELETE PRODUCT TAXES</strong></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New TAXES</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New TAXES</h3>
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/product.tax.new') }}">
                    {{ csrf_field() }}

                <div class="modal-body">



                        <div class="form-group{{ $errors->has('taxName') ? ' has-error' : '' }}">
                            <label for="taxName" class="col-md-4 control-label">TAX Name</label>

                            <div class="col-md-6">
                                <input id="taxName" type="text" class="form-control" name="taxName" value="" required autofocus>

                                @if ($errors->has('taxName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('taxName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('applicableOn') ? ' has-error' : '' }}">
                            <label for="applicableOn" class="col-md-4 control-label">Applicable On</label>

                            <div class="col-md-6">
                                {!! Form::select('applicableOn', array('0' => 'Please Select', 'S' => 'Sales', 'P' => 'Purchase', 'B' => 'Both'), null , array('id' => 'applicableOn', 'class' => 'form-control')) !!}
                                @if ($errors->has('applicableOn'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('applicableOn') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                            <label for="rate" class="col-md-4 control-label">Fixed Amt / Rate (%)</label>

                            <div class="col-md-6">
                                <input id="rate" type="text" class="form-control" name="rate" value="" required>

                                @if ($errors->has('rate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('calculatingMode') ? ' has-error' : '' }}">
                            <label for="calculatingMode" class="col-md-4 control-label">Calculating Mode</label>

                            <div class="col-md-6">
                                {!! Form::select('calculatingMode', array('0' => 'Please Select', 'P' => 'Purcentage', 'F' => 'Fixed'), null , array('id' => 'calculatingMode', 'class' => 'form-control')) !!}
                                @if ($errors->has('calculatingMode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('calculatingMode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" value="" required>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
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

    @include('partials.flashmessage')

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Code</th>
                    <th>Tax Name</th>
                    <th>Applicable On</th>
                    <th>Rate</th>
                    <th>Mode</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1" style="overflow-x:auto;">
            <p>Applicable On : S = Sales; P = Purchase; B = Both</p>
            <p style="text-align: left">Calculating Mode: P = Percentage; F = Fixed </p>
        </div>
    </div>

@stop

@push('scripts')

<script>
    $(function() {
        var table= $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'product.tax.data',
            columns: [
                { data: 'tax_id', name: 'tax_id' },
                { data: 'taxName', name: 'taxName' },
                { data: 'applicableOn', name: 'applicableOn' },
                { data: 'rate', name: 'rate' },
                { data: 'calculatingMode', name: 'calculatingMode' },
                { data: 'description', name: 'description' },
                { data: 'status', name: 'status',orderable: false, searchable: false, printable: false},
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#users-table').on('click', '.btn-delete[data-remote]', function (e) {
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
            $('#users-table').DataTable().draw(false);
        });
    });

    $('#users-table').on('click', '.btn-edit', function (e) {
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
