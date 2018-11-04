@extends('report.layout.pdfmaster')
@section('report_title')
    <div style="text-align:center"><span style="line-height: 40%; text-align:center; font-family:times;font-weight:bold;font-size:15pt;color:black;">Trial Balance as on: {!! $ason->monthName !!} {!! $ason->year !!}</span></div>
    {{--<title>Invoice</title>--}}
@endsection


@section('content')

    @php($opDrTotal = 0)
    @php($opCrTotal = 0)
    @php($drTotal = 0)
    @php($crTotal = 0)
    @php($gTotal = 0)

    @if(!empty($trans))
        <div class="container" style="overflow-x:auto;">
                <table class="table order-bank" >

                    <thead>
                    <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                            <th width="8%" style="font-size:10pt;">GL Head</th>
                            <th width="23%" style="font-size:10pt;">Name</th>
                            <th width="7%" style="font-size:10pt; text-align:center">Type</th>
                            <th width="13%" style="font-size:10pt; text-align:right">Opn Debit</th>
                            <th width="13%" style="font-size:10pt; text-align:right">Opn Credit</th>
                            <th width="13%" style="font-size:10pt; text-align:right">Debit</th>
                            <th width="12%" style="font-size:10pt; text-align:right">Credit</th>
                            <th width="11%" style="font-size:10pt; text-align:right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trans as $i => $row)
                        <tr style="line-height: 200%;">
                            <td width="8%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $row->accNo }}</td>
                            <td width="23%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $row->accName }}</td>
                            <td width="6%" style="border-bottom-width:0.2px; font-size:8pt;" align="center">{!! $row->accType !!}</td>
                            <td width="13%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($row->opndr,2) }}</td>
                            <td width="13%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($row->opncr,2) }}</td>
                            <td width="13%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($row->prdr,2) }}</td>
                            <td width="12%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($row->prcr,2) }}</td>
                            <td width="12%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format(($row->prdr + $row->opndr) - ($row->prcr + $row->opncr),2) }}</td>

                            @php($crTotal = $crTotal + $row->prcr )
                            @php($drTotal = $drTotal + $row->prdr)

                            @php($opCrTotal = $opCrTotal + $row->opncr )
                            @php($opDrTotal = $opDrTotal + $row->opndr)

                            @php($gTotal = $gTotal + (($row->prdr + $row->opndr) - ($row->prcr + $row->opncr)) )

                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr style="line-height: 200%;">
                        <td style="font-size:8pt;" colspan="3">Total</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;"align="right">{{ number_format($opDrTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($opCrTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($drTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($crTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($gTotal,2) }}</td>

                    </tr>

                    </tfoot>
                </table>
        </div>

    @endif

@stop
