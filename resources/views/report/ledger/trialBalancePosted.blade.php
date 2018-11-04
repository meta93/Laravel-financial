@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-6 col-md-offset-2" >
                <br/>
                <div><h3>Trial Balance</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'postedtb', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="type" class="control-label">Select Month</label></td>
                            <td width="10%">{!! Form::selectMonth('month', 7, ['class' => 'col-md-4 form-control']) !!}</td>
                        </tr>
                        <tr>
                            <td width="10%"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width="10%"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>


    @php($opDrTotal = 0)
    @php($opCrTotal = 0)
    @php($drTotal = 0)
    @php($crTotal = 0)
    @php($gTotal = 0)

    @if(!empty($trans))
        <div class="container" style="overflow-x:auto;">
            <div class="panel panel-default padding-left">
                <div class="panel-heading"><strong>Trial Balance (Approved Vouchers Only) as on : {!! $ason->monthName !!} {!! $ason->year !!}</strong> </div>
                <table class="table table-bordered table-hover" >

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
                    @foreach($trans as $i => $row)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                            <td>{{ $row->accNo }}</td>
                            <td>{{ $row->accName }}</td>
                            <td>{!! $row->accType !!}</td>
                            <td align="right">{{ number_format($row->opndr,2) }}</td>
                            <td align="right">{{ number_format($row->opncr,2) }}</td>
                            <td align="right">{{ number_format($row->prdr,2) }}</td>
                            <td align="right">{{ number_format($row->prcr,2) }}</td>
                            <td align="right">{{ number_format(($row->prdr + $row->opndr) - ($row->prcr + $row->opncr),2) }}</td>

                            @php($crTotal = $crTotal + $row->prcr )
                            @php($drTotal = $drTotal + $row->prdr)

                            @php($opCrTotal = $opCrTotal + $row->opncr )
                            @php($opDrTotal = $opDrTotal + $row->opndr)

                            @php($gTotal = $gTotal + (($row->prdr + $row->opndr) - ($row->prcr + $row->opncr)) )

                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #a598aa">
                        <td colspan="3">Total</td>
                        <td align="right">{{ number_format($opDrTotal,2) }}</td>
                        <td align="right">{{ number_format($opCrTotal,2) }}</td>
                        <td align="right">{{ number_format($drTotal,2) }}</td>
                        <td align="right">{{ number_format($crTotal,2) }}</td>
                        <td align="right">{{ number_format($gTotal,2) }}</td>

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