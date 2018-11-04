@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" class="init">

        //When a row is clicked the edit and delete button will be enabled.

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


    </script>


    <div class="row" id="message">
        <div class="col-md-10 col-md-offset-1 margin-tb">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-success btn-new">New Account Group</button>
            </div>
        </div>
    </div>

    @include('partials.flashmessage')
    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Account Group</h3>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/account.group.add') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('accType') ? ' has-error' : '' }}">
                            <label for="accType" class="col-md-4 control-label">Type</label>

                            <div class="col-md-6">
                                {!! Form::select('accType', array('' => 'Please Select', 'A' => 'ASSET', 'L' => 'LIABILITY', 'I' => 'INCOME', 'E' => 'EXPENSE','C' => 'CAPITAL'), null , array('id' => 'accType', 'class' => 'form-control')) !!}
                                {!! Form::hidden('id', null, array('id' => 'id')) !!}
                                @if ($errors->has('accType'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accType') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('typeCode') ? ' has-error' : '' }}">
                            <label for="typeCode" class="col-md-4 control-label">Sub Type</label>

                            <div class="col-md-6">
                                {!! Form::select('typeCode',$subType, null , array('id' => 'typeCode', 'class' => 'form-control')) !!}
                                @if ($errors->has('typeCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('typeCode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('accName') ? ' has-error' : '' }}">
                            <label for="accName" class="col-md-4 control-label">Group Name</label>

                            <div class="col-md-6">
                                <input id="accName" type="text" class="form-control" name="accName" required autofocus>

                                @if ($errors->has('accName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-info" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Group Code</th>
                    <th>Group Name</th>
                    <th>Type</th>
                    <th>Type Desc</th>
                    <th>Sub Type</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
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
            ajax: 'account.group.data',
            columns: [
                { data: 'ldgrCode', name: 'ldgrCode' },
                { data: 'accName', name: 'accName' },
                { data: 'accType', name: 'accType' },
                { data: 'TypeDesc', name: 'TypeDesc' },
                { data: 'description', name: 'description' },
                { data: 'currBal', name: 'currBal' },
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
        }).always(function (data, status) {
            alert(status);
            $('#users-table').DataTable().draw(false);
        })
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
