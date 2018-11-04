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
    <div class="container">
        <div class="row">
            <br>

            <div style="height: 15px"></div>

            {!! Form::open(['url'=>'openingBalanceIndex', 'method' => 'GET']) !!}
            <div class="form-inline col-xs-11 col-md-offset-2">
                {!! Form::label('accHead', 'Account Head', array('class' => 'col-xs-2 control-label')) !!}
                {!! Form::text('accHead', null , array('id' => 'accHead', 'class' => 'col-xs-5 form-control')) !!}
                {!! Form::submit('Submit',['class'=>'col-xs-3 btn btn-primary button-control']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <br>
    <br>

    <div class="container" style="overflow-x:auto;">
        {!! Form::open(['$id'=>'saveForm', 'url'=>'saveOpenbalance']) !!}
        <div class="panel panel-default padding-left">
            <div class="panel-heading">Set Opening Balance</div>
                <table class="table table-bordered table-hover" >


                    <thead style="background-color: #66afe9">
                        <th>GL Head</th>
                        <th>Name</th>
                        <th>Opening Debit</th>
                        <th>Opening Credit</th>
                    </thead>
                    <tbody>
                    @foreach($glGroup as $i => $glGroups)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                            <td>{{ $glGroups->accNo }}</td>
                            <td>{{ $glGroups->accName }}</td>
                            <td>{!! Form::text('opnDr[]', $glGroups->opnDr , array('id' => 'opnDr','onfocus'=>'this.select();','onmouseup'=>'return false;', 'class' => 'form-control debitamount', 'data-mg-required' => '')) !!}</td>
                            <td>{!! Form::text('opnCr[]', $glGroups->opnCr , array('id' => 'opnCr', 'onfocus'=>'this.select();','onmouseup'=>'return false;','class' => 'form-control creditamount', 'data-mg-required' => '')) !!}</td>
                            {!! Form::hidden('id[]', $glGroups->id, array('id' => 'id')) !!}
                            {!! Form::hidden('accNo[]', $glGroups->accNo, array('id' => 'accNo')) !!}
                        </tr>
                    @endforeach
                    {!! $glGroup->render() !!}
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #8c8c8c">
                        <td colspan="2">Total</td>
                        <td align="right">{!! number_format($checkSum[0]->opnDr,2) !!}</td>
                        <td align="right">{!! number_format($checkSum[0]->opnCr,2) !!}</td>
                    </tr>
                    </tfoot>
                </table>
        </div>
    </div>

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->

    <div class="col-xs-2 sidenav"></div>
    <div class="col-xs-4">
        {!! Form::submit('SAVE OPEN BALANCE',['class'=>'btn btn-primary btn-save button-control']) !!}
    </div>
    {!! Form::close() !!}

    {!! Form::open(['url'=>'postOpenbalance']) !!}
    <div class="col-xs-4">
        {!! Form::submit('POST OPEN BALANCE',['class'=>'btn btn-primary button-control pull-right']) !!}
    </div>
    {!! Form::close() !!}




    {{--<script>--}}

        {{--var debit = 0;--}}
        {{--var credit = 0;--}}

        {{--$(document).on('click', '.btn-save', function (e) {--}}

            {{--$(this).attr("disabled", true);--}}

            {{--$("#saveForm").find('input.debitamount').each(function () {--}}
                {{--debit += parseInt($(this).val()||0);--}}
            {{--});--}}

            {{--$("#saveForm").find('input.creditamount').each(function () {--}}
                {{--credit += parseInt($(this).val()||0);--}}
            {{--});--}}

            {{--if(debit != credit)--}}
            {{--{--}}
                {{--alert('Transaction Debit & Credit Amount are not same');--}}
                {{--debit  = 0;--}}
                {{--credit = 0;--}}
                {{--return false;--}}
            {{--}--}}
            {{--else--}}
            {{--{--}}
                {{--debit  = 0;--}}
                {{--credit = 0;--}}
                {{--return true;--}}
            {{--}--}}

        {{--});--}}


        {{--$(function() {--}}
            {{--setInterval(function(){--}}
                {{--$('.alert').slideUp(500);--}}
            {{--}, 2000);--}}
        {{--});--}}

    {{--</script>--}}
@endsection