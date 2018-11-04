@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

<br/>
    <div class="row">

        {{--{!! Form::open(array('action' => 'Report\Statements\FinancialStatementController@createStatementData','method' => 'GET')) !!}--}}

        {!! Form::open(['url'=>'createStatementDataIndex', 'method' => 'GET']) !!}

        <div class = "col-md-10 col-md-offset-2">

            {!! Form::label('fileNo', 'Select Statement', array('class' => 'col-sm-3 control-label')) !!}
            {!! Form::select('fileNo', $fileList, array('id' => 'fileNo', 'class' => 'col-sm-7 form-controll')) !!}
        </div>
        <br>
        <br>
        <div class="col-md-6 col-md-offset-3">
            {!! Form::submit('Select',['class'=>'btn btn-primary form-control']) !!}
        </div>

        {!! Form::close() !!}
    </div>

    @if(!empty($fileNo))


    {{--<div style="height: 25px"></div>--}}

    <div class="row">
        <div class="col-sm-1 sidenav"></div>
        <div class="col-lg-10 margin-tb">
            <div class="pull-left">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-register">Add New Line</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Line</h3>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/fn.statement.line') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('fileNo') ? ' has-error' : '' }}">
                            <label for="fileNo" class="col-md-4 control-label">STATEMENT ID : </label>

                            <div class="col-md-6">
                                {!! Form::text('fileNo', Session::get('STATEMENT_FILE_NO') , array('id' => 'fileNo', 'class' => 'form-control','readonly' => 'true')) !!}
                                {{--<input id="fileNo" type="text" class="form-control" name="fileNo" value="" required autofocus>--}}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lineNo') ? ' has-error' : '' }}">
                            <label for="lineNo" class="col-md-4 control-label">LINE NO : </label>

                            <div class="col-md-6">
                                {!! Form::text('lineNo', $maxLineNo , array('id' => 'lineNo', 'class' => 'form-control','required' => 'required')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('textPosition') ? ' has-error' : '' }}">
                            <label for="textPosition" class="col-md-4 control-label">TEXT POSITION : </label>

                            <div class="col-md-6">
                                {!! Form::select('textPosition', array('' => 'Please Select', 5 => '5', 10 => '10',15 => '15'), null , array('id' => 'textPosition', 'class' => 'form-control', 'required' => 'required')) !!}
                                {{--{!! Form::text('textPosition', null , array('id' => 'textPosition', 'class' => 'form-control')) !!}--}}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('texts') ? ' has-error' : '' }}">
                            <label for="texts" class="col-md-4 control-label">TEXTS : </label>

                            <div class="col-md-6">
                                {!! Form::text('texts', null , array('id' => 'texts', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('font') ? ' has-error' : '' }}">
                            <label for="font" class="col-md-4 control-label">TEXT SIZE : </label>

                            <div class="col-md-6">
                                {!! Form::select('font', array('' => 'Please Select', 10 => '10', 12 => '12', 14 => '14'), null , array('id' => 'font', 'class' => 'form-control', 'required' => 'required')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                            <label for="note" class="col-md-4 control-label">NOTE TITLE: </label>

                            <div class="col-md-6">
                                {!! Form::text('note', null , array('id' => 'note', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('accType') ? ' has-error' : '' }}">
                            <label for="accType" class="col-md-4 control-label">ACC TYPE: </label>

                            <div class="col-md-6">
                                {!! Form::select('accType', array('' => 'Please Select', 'A' => 'ASSET', 'L' => 'LIABILITY'), null , array('id' => 'accType', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('ac11') ? ' has-error' : '' }}">
                            <label for="ac11" class="col-md-4 control-label">1. ACC FROM: </label>

                            <div class="col-md-6">
                                {!! Form::text('ac11', null , array('id' => 'ac11', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('ac12') ? ' has-error' : '' }}">
                            <label for="ac12" class="col-md-4 control-label">1. ACC TO: </label>

                            <div class="col-md-6">
                                {!! Form::text('ac12', null , array('id' => 'ac12', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('ac21') ? ' has-error' : '' }}">
                            <label for="ac21" class="col-md-4 control-label">2. ACC FROM: </label>

                            <div class="col-md-6">
                                {!! Form::text('ac21', null , array('id' => 'ac21', 'class' => 'form-control')) !!}
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('ac22') ? ' has-error' : '' }}">
                            <label for="ac22" class="col-md-4 control-label">2. ACC TO: </label>

                            <div class="col-md-6">
                                {!! Form::text('ac22', null , array('id' => 'ac22', 'class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('subTotal') ? ' has-error' : '' }}">
                            <label for="subTotal" class="col-md-4 control-label">SUB TOTAL : </label>

                            <div class="col-md-6">
                                {!! Form::text('subTotal', null , array('id' => 'subTotal', 'class' => 'form-control')) !!}
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('pFormula') ? ' has-error' : '' }}">
                            <label for="pFormula" class="col-md-4 control-label">FORMULA : </label>

                            <div class="col-md-6">
                                {!! Form::text('pFormula', null , array('id' => 'pFormula', 'class' => 'form-control')) !!}
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
                    <th>Line</th>
                    <th>Text</th>
                    <th>Ac1 Start</th>
                    <th>Ac1 End</th>
                    <th>Ac2 Start</th>
                    <th>Ac2 End</th>
                    <th>Sub Total</th>
                    <th>Formula</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


    </div>

    @endif

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
            ajax: 'fn.statement.data',
            columns: [
                { data: 'fileNo', name: 'fileNo' },
                { data: 'lineNo', name: 'lineNo' },
                { data: 'texts', name: 'texts' },
                { data: 'ac11', name: 'ac11' },
                { data: 'ac12', name: 'ac12' },
                { data: 'ac21', name: 'ac21' },
                { data: 'ac22', name: 'ac22' },
                { data: 'subTotal', name: 'subTotal' },
                { data: 'pFormula', name: 'pFormula' },
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
