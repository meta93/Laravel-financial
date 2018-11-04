@extends('report.layout.pdfmaster')
@section('report_title')
    <div style="text-align:center"><span style="line-height: 40%; text-align:center; font-family:times;font-weight:bold;font-size:15pt;color:black;">Transaction List For The Date: {!! \Carbon\Carbon::parse($data[0]->transDate)->format('d-m-Y') !!}</span></div>
    {{--<title>Invoice</title>--}}
@endsection


@section('content')

    @php($drTotal = 0)
    @php($crTotal = 0)

    @if(count($data) >0 )
        <div class="col-md-10 col-md-offset-1">

                <table class="table order-bank" >


                    <thead style="background-color: #3278b3">
                    <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                        <th width="14%">Type</th>
                        <th width="8%">Voucher #</th>
                        <th width="20%">Description</th>
                        <th width="8%">Acc No</th>
                        <th width="20%">Acc Name</th>
                        <th width="10%" style="text-align:right">Debit</th>
                        <th width="10%" style="text-align:right">Credit</th>
                        <th width="10%" style="text-align:right">User</th>
                    </thead>
                    </tr>

                    <tbody>

                    @foreach($data as  $i=> $row)
                        {{--@if(!is_null($row->accDr ))--}}
                        <tr style="line-height: 200%;">
                            <td width="14%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->jCode }}</td>
                            <td width="8%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->voucherNo }}</td>
                            <td width="20%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->transDesc1 }}</td>
                            <td width="8%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->acc_no }}</td>
                            <td width="20%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->dr_acc->accName }}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ number_format($row->dr_amt,2) }}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ number_format($row->cr_amt,2) }}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ $row->userCreated }}</td>

                            @php($crTotal = $crTotal + $row->cr_amt )
                            @php($drTotal = $drTotal + $row->dr_amt)

                        </tr>
                        {{--@endif--}}
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr style="line-height: 200%">
                        <th>Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td style="border-bottom-width:0.2px; font-size:10pt;" align="right" align="right">{{ number_format( $drTotal,2) }}</td>
                        <td style="border-bottom-width:0.2px; font-size:10pt;" align="right" align="right">{{ number_format( $crTotal,2) }}</td>
                        <td></td>
                    </tr>
                    </tfoot>

                </table>
                <br>

            </div>
        {{--</div>--}}
    @endif

@stop
