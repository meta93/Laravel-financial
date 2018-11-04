@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<div style="height: 25px"></div>--}}

    <div class="row">
        <div class="col-sm-1 sidenav"></div>
        <div class="col-lg-10 margin-tb">
            <div class="pull-left">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-register">Add Budget</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Budget</h3>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/fn.statement.save') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('fileNo') ? ' has-error' : '' }}">
                            <label for="fileNo" class="col-md-4 control-label">Budget ID : </label>

                            <div class="col-md-6">
                                {!! Form::text('fileNo', null , array('id' => 'fileNo', 'class' => 'form-control','required' => 'required')) !!}
                                {{--<input id="fileNo" type="text" class="form-control" name="fileNo" value="" required autofocus>--}}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('fileDesc') ? ' has-error' : '' }}">
                            <label for="fileDesc" class="col-md-4 control-label">TITLE : </label>

                            <div class="col-md-6">
                                {!! Form::text('fileDesc', null , array('id' => 'fileDesc', 'class' => 'form-control','required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('importFile') ? ' has-error' : '' }}">
                            <label for="importFile" class="col-md-4 control-label">FROM FILE : </label>

                            <div class="col-md-6">
                                {!! Form::text('importFile', null , array('id' => 'importFile', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('importLine') ? ' has-error' : '' }}">
                            <label for="importLine" class="col-md-4 control-label">FROM LINE : </label>

                            <div class="col-md-6">
                                {!! Form::text('importLine', null , array('id' => 'importLine', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('intoLine') ? ' has-error' : '' }}">
                            <label for="intoLine" class="col-md-4 control-label">TO LINE : </label>

                            <div class="col-md-6">
                                {!! Form::text('intoLine', null , array('id' => 'intoLine', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
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
                    <th>File No</th>
                    <th>Name</th>
                    <th>Import From</th>
                    <th>Import Line</th>
                    <th>Put Line</th>
                    <th>Value</th>
                    <th>Value Date</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


    </div>

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p style="text-align: center" class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->


    <script>
        $(function() {
            setInterval(function(){
                $('.alert').slideUp(500);
            }, 10000);
        });
    </script>

@stop

@push('scripts')

<script>
    $(function() {
        var table= $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'fn.statement.list',
            columns: [
                { data: 'fileNo', name: 'fileNo' },
                { data: 'fileDesc', name: 'fileDesc' },
                { data: 'importFile', name: 'importFile' },
                { data: 'importLine', name: 'importLine' },
                { data: 'intoLine', name: 'intoLine' },
                { data: 'importValue', name: 'importValue' },
                { data: 'valueDate', name: 'valueDate' },
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
