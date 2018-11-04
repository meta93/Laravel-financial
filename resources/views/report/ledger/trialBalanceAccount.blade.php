@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <div class="container">
        {{--<div class="row">--}}
            {{--<br>--}}
            {{--<div class="col-xs-2 sidenav"></div>--}}

            {{--{!! Form::open(['url'=>'openingBalanceIndex', 'method' => 'GET']) !!}--}}
            {{--<div class="col-xs-11">--}}
                {{--{!! Form::label('accHead', 'Account Head', array('class' => 'col-xs-2 control-label')) !!}--}}
                {{--{!! Form::text('accHead', null , array('id' => 'accHead', 'class' => 'col-xs-5 control-text')) !!}--}}
                {{--{!! Form::submit('Submit',['class'=>'col-xs-3 btn btn-primary button-control']) !!}--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}
        {{--</div>--}}
    </div>
    <br>
    <br>
@if(!empty($data))
    <div class="container" style="overflow-x:auto;">
        <div class="panel panel-default padding-left">
            <div class="panel-heading">Set Opening Balance</div>
            <table class="table table-bordered table-hover" >

                {{--<colgroup>--}}
                    {{--<col style="width: 40px"/>--}}
                    {{--<col style="width: 100px"/>--}}
                    {{--<col style="width: 80px"/>--}}
                    {{--<col style="width: 80px"/>--}}
                {{--</colgroup>--}}

                <thead style="background-color: #66afe9">
                    <th>GL Head</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Opn Debit</th>
                    <th>Opn Credit</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </thead>
                <tbody>
                @foreach($data as $i => $row)
                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                        <td>{{ $row->accNo }}</td>
                        <td>{{ $row->accName }}</td>
                        <td>{!! $row->accType !!}</td>
                        <td>{!! $row->startDr !!}</td>
                        <td>{!! $row->startCr !!}</td>
                        <td>{!! $row->dr00 !!}</td>
                        <td>{!! $row->cr00 !!}</td>
                        <td>{!! $row->currBal !!}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr style="background-color: #a598aa">
                    <td colspan="3">Total</td>
                    <td>{!! $footer->opnDr !!}</td>
                    <td>{!! $footer->opnCr !!}</td>
                    <td>{!! $footer->dr00 !!}</td>
                    <td>{!! $footer->cr00 !!}</td>
                    <td>{!! $footer->currBal !!}</td>
                </tr>

                </tfoot>
            </table>
        </div>
    </div>

    @include('partials.flashmessage')

    {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

    <div class="col-md-6 col-md-offset-3">
        {!! Form::submit('EXIT',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}

@endif

@endsection