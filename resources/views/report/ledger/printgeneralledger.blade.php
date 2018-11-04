@extends('report.layout.pdfmaster')
@section('report_title')
    <div style="text-align:center"><span style="line-height: 40%; text-align:center; font-family:times;font-weight:bold;font-size:15pt;color:black;">General Ledger</span></div>
    {{--<title>Invoice</title>--}}
@endsection


@section('content')


    <table>

        <tr>

            <td width="35%" style="font-size:10pt;"><strong>Account No:</strong>
                {!! $accdata->accNo !!} : {!! $accdata->accName !!}<br>
            </td>

            <td width="65%" style="font-size:10pt; text-align: right">
                    <strong>Transaction Between : </strong> {!! $fromDate->format('d-m-Y') !!} and {!! $toDate->format('d-m-Y') !!}

            </td>
        </tr>
    </table>



    @php($opnBal = 0)
    @php($drTotal = 0)
    @php($crTotal = 0)

    @if(!empty($transData))

        {{--<div class="col-md-10 panel panel-default padding-left col-md-offset-1">--}}
            {{--<div class="panel-heading">TRANSACTIONS FROM {!! $fromDate !!} TO {!! $toDate !!}</div>--}}
            <div class="w3-margin-left w3-margin-right">
                <table class="table order-bank">
                    <thead>
                        <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                            <th width="15%" style="font-size:10pt;">Date</th>
                            <th width="15%" style="font-size:10pt;">Type</th>
                            <th width="10%" style="font-size:10pt;">Voucher#</th>
                            <th width="30%" style="font-size:10pt;">Description</th>
                            <th width="15%" style="font-size:10pt;" style="text-align:right">Debit</th>
                            <th width="15%" style="font-size:10pt;" style="text-align:right">Credit</th>
                        </tr>

                    {{--<th style="text-align:right">Opening Balance</th>--}}
                    </thead>
                    <tbody>
                    @foreach($transData as  $i=> $tData)
                        <tr style="line-height: 200%;">
                            <td width="15%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $tData->transDate }}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $tData->jCode }}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $tData->voucherNo }}</td>
                            <td width="30%" style="border-bottom-width:0.2px; font-size:8pt;">{{ $tData->transDesc1 }}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($tData->dr_amt,2) }}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format($tData->cr_amt,2) }}</td>

                            @php ($drTotal = $drTotal + $tData->dr_amt)
                            @php ($crTotal = $crTotal + $tData->cr_amt)

                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr style="line-height: 200%;">
                        <th style="font-size:10pt;" colspan="4">Periodic Total</th>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format( $drTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:8pt;" align="right">{{ number_format( $crTotal,2) }}</td>
                    </tr>
                    </tfoot>

                </table>

                @if( ($opnBal + $drTotal - $crTotal) > 0 )
                    <div class="col-sm-12 text-left">
                        <p style="font-size: 8px">CLOSING BALANCE : DEBIT {{ number_format(($opnBal + $drTotal - $crTotal ),2) }}</p>
                    </div>

                @else

                    <div class="col-sm-12 text-left">
                        <p style="font-size: 8px">CLOSING BALANCE : CREDIT {{ number_format(abs(($opnBal + $drTotal - $crTotal)),2) }}</p>
                    </div>
                @endif

            </div>
        {{--</div>--}}
    @endif

@stop
