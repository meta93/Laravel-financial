<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Basic Report</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

    {{--<style>--}}
    {{--th{--}}
    {{--background-color: #ffffff;--}}
    {{--color: #000000;--}}
    {{--}--}}
    {{--</style>--}}

    <style>
        table.order-bank {
            width:100%;
            margin:0;
        }
        table.order-bank th{
            padding:5px;
        }
        table.order-bank td {
            padding:5px;
        }
        tr.row-line th {
            border-bottom-width:1px;
            border-top-width:1px;
            border-right-width:1px;
            border-left-width:1px;
        }
        tr.row-line td {
            border-bottom:1px solid red;
        }
        th.first-cell {
            text-align:left;
            border:1px solid red;
            color:blue;
        }
        div.order-field {
            width:100%;
            backgroundr: #ffdab9;
            border-bottom:1px dashed black;
            color:black;
        }
        div.blank-space {
            width:100%;
            height: 50%;
            margin-bottom: 100px;
            line-height: 10%;
        }
    </style>


</head>
<body>

@if(!empty($vouchers))
    @foreach($vouchers as  $i=> $voucher)
        @php($Total = 0)

        @if($voucher->jCode != 'JOURNAL VOUCHER')

            <div style="text-align:center"><span style="font-family:times;font-weight:bold; padding-left: 100px; line-height: 40%; height: 300%; font-size:30pt;color:black;">{!! get_company_name() !!}</span></div>
            <div style="text-align:center"><span style="line-height: 40%; text-align:center; font-family:times;font-weight:bold;font-size:15pt;color:black;">{!! get_company_address() !!}</span></div>
            <div class="row"></div>
            <table>

                <tr>

                    <td width="50%" style="text-align: left">
                        <address>
                            <strong></strong>Voucher No : {!! $voucher->voucherNo !!}<br>
                            Voucher Type : {!! $voucher->jCode !!}<br>
                        </address>
                    </td>

                    <td width="50%" style="text-align: right">
                        <address>
                            <strong></strong>Date : {!! $voucher->transDate !!}<br>
                            User : {!! $voucher->userCreated !!}<br>
                        </address>
                    </td>
                </tr>

            </table>

            <table class="table order-bank">

                <thead>
                <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                    <th width="40%" colspan="2">Head of Accounts</th>
                    <th width="30%">Description</th>
                    <th width="15%" style="text-align:right">Debit</th>
                    <th width="15%" style="text-align:right">Credit</th>
                </tr>

                </thead>
                <tbody>

                @foreach($data as  $i=> $row)
                    @if($voucher->voucherNo == $row->voucherNo)
                        <tr style="line-height: 200%;">
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->dr_acc->accNo }}</td>
                            <td width="30%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->dr_acc->accName }}</td>
                            <td width="30%" style="border-bottom-width:0.2px; font-size:10pt;">{{ $row->transDesc1 }}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ number_format($row->dr_amt,2) }}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ number_format($row->cr_amt,2) }}</td>
                            @php($Total = $Total + $row->dr_amt )

                        </tr>
                    @endif

                @endforeach
                </tbody>

                <tfoot>
                <tr style="line-height: 200%;">
                    <th style="font-size:10pt;">Total</th>
                    <td style="border-bottom-width:0.2px; font-size:10pt;" colspan="3">{!! convert_number_to_words($Total).' '.get_currency() !!}</td>
                    <td style="border-bottom-width:0.2px; font-size:10pt;" align="right">{{ number_format( $Total,2) }}</td>
                </tr>
                </tfoot>

            </table>

            <br pagebreak="true">

                {{--</div>--}}
            {{--</div>--}}
        @endif
    @endforeach
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>