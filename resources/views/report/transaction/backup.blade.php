<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Application Users</title>


    <link href="/css/app.css" rel="stylesheet">
    <link href="../src/css/main.css" rel="stylesheet" type="text/css" />
    <link href="../src/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../src/bootstrap-3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

@if(!empty($vouchers))
    @foreach($vouchers as  $i=> $voucher)
        @php($Total = 0)

        @if($voucher->jCode != 'JOURNAL VOUCHER')

            <div class="col-md-10 col-md-offset-1">
                {!! $voucher->company->compName !!}
            </div>
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default padding-left">
                    <div class="col-md-6 panel-heading">Voucher No : {!! $voucher->voucherNo !!} <br/>
                        Voucher Type : {!! $voucher->jCode !!}</div>
                    <div class="col-md-6 panel-heading" style="text-align: right">Voucher No : {!! $voucher->transDate !!} <br/>
                        User : {!! $voucher->userCreated !!}</div>
                    <table class="table table-bordered table-hover" width="100%">


                        <thead style="background-color: #3278b3">
                            <th colspan="2" width="50%">Head of Accounts</th>
                            <th>Description</th>
                            <th style="text-align:right">Debit</th>
                            <th style="text-align:right">Credit</th>
                        </thead>
                        <tbody>

                        @foreach($data as  $i=> $row)
                            @if($voucher->voucherNo == $row->voucherNo)
                                <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                                    <td>{{ $row->dr_acc->accNo }}</td>
                                    <td>{{ $row->dr_acc->accName }}</td>
                                    <td>{{ $row->transDesc1 }}</td>
                                    <td align="right">{{ number_format($row->dr_amt,2) }}</td>
                                    <td align="right">{{ number_format($row->cr_amt,2) }}</td>
                                    @php($Total = $Total + $row->dr_amt )

                                </tr>
                            @endif

                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr style="background-color: {{ $Total % 2 == 0 ? '#aaaaaa': '#afffff' }};">
                            <th>Total</th>
                            <td colspan="3">{!! convert_number_to_words($Total).' '.get_currency(Auth::user()->compCode) !!}</td>
                            <td align="right">{{ number_format( $Total,2) }}</td>
                        </tr>
                        </tfoot>

                    </table>

                    <br>

                </div>
            </div>
        @endif
    @endforeach
@endif
</body>
</html>