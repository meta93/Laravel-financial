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


    <div class="row">
        <div class="col-md-10 col-md-offset-1 margin-tb">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-register">New Account Head</button>
            </div>
        </div>
    </div>

    @include('partials.flashmessage')

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">New Account Head</h3>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/account.head.add') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('ldgrCode') ? ' has-error' : '' }}">
                            <label for="ldgrCode" class="col-md-3 control-label">Group</label>

                            <div class="col-md-8">
                                {!! Form::select('ldgrCode',$ldgrCode, null , array('id' => 'ldgrCode', 'class' => 'form-control')) !!}
                                @if ($errors->has('ldgrCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ldgrCode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('accName') ? ' has-error' : '' }}">
                            <label for="accName" class="col-md-3 control-label">Account Name</label>

                            <div class="col-md-8">
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
            <table class="table table-bordered table-hover" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Group</th>
                    <th>Acc No</th>
                    <th>Account Name</th>
                    <th>Type Desc</th>
                    <th>Sub Type</th>
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
            ajax: 'account.head.data',
            columns: [
                { data: 'ldgrCode', name: 'ldgrCode' },
                { data: 'accNo', name: 'accNo' },
                { data: 'accName', name: 'accName' },
                { data: 'TypeDesc', name: 'TypeDesc' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#users-table').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
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
            data: {method: '_DELETE', submit: true}
        }).always(function (data) {
            $('#users-table').DataTable().draw(false);
        });
    });

</script>

@endpush
