@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <div style="height: 25px"></div>

    {{--<div class="container">--}}
        {{--<div class="row">--}}

            {{--{!! Form::open(['route'=>'report.dailytrans.rpt', 'method' => 'GET']) !!}--}}
            {{--<div class="col-md-5 col-md-offset-2 form-inline">--}}
                {{--{!! Form::label('fromDate','Date:', array('class' => 'col-sm-1 control-label')) !!}--}}
                {{--{!! Form::text('transDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'transDate', 'class' => 'col-sm-4 control-text','readonly', 'required')) !!}--}}
                {{--{!! Form::submit('VIEW',['class'=>'btn btn-primary form-control']) !!}--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}

            {{--<br>--}}
            {{--<br>--}}
        {{--</div>--}}
    {{--</div>--}}
    <br>
    <br>


    @if(!empty($registerData))
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default padding-left">
            <div class="panel-heading"><h3>Cash Register</h3></div>
            <table class="table table-bordered table-hover" >
                <colgroup>
                    <col style="width: 50px"/>
                    <col style="width: 150px"/>
                    <col style="width: 60px"/>
                    <col style="width: 60px"/>
                    <col style="width: 60px"/>
                    <col style="width: 60px"/>
                </colgroup>

                <thead style="background-color: #8c8c8c">
                <th>BANK CODE</th>
                <th>NAME</th>
                <th style="text-align:right">OPENING</th>
                <th style="text-align:right">DEBIT</th>
                <th style="text-align:right">CREDIT</th>
                <th style="text-align:right">BALANCE</th>
                </thead>
                <tbody>
                @foreach($registerData as  $i=> $regData)
                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                        <td>{{ $regData->accNo }}</td>
                        <td>{{ $regData->accName }}</td>
                        <td align="right">{{ number_format($regData->year_open,2) }}</td>
                        <td align="right">{{ number_format($regData->dr00,2) }}</td>
                        <td align="right">{{ number_format($regData->cr00,2) }}</td>
                        <td align="right">{{ number_format($regData->balance,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr style="background-color: #e3e3e3;">
                    <td>Total</td>
                    <td></td>
                    <td align="right">{{ number_format($registerSum->year_open,2) }}</td>
                    <td align="right">{{ number_format($registerSum->dr_00,2) }}</td>
                    <td align="right">{{ number_format($registerSum->cr_00,2) }}</td>
                    <td align="right">{{ number_format($registerSum->balance,2) }}</td>
                </tr>
                </tfoot>
            </table>
            <br>

            {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

            <div class="col-md-4">
                {!! Form::submit('EXIT',['class'=>'btn btn-primary form-control']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        </div>
    @endif


@endsection

@push('scripts')

    <script>

        $(function() {
            $("#transDate").datepicker({dateFormat: "dd/mm/yy"}).val();
        });

    </script>

@endpush