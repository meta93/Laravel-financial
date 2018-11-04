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
                <div><h3>Print Preview Vouchers</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['route'=>'report.dailyvoucher.rpt', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        {{--<tr>--}}
                        {{--<td width="5%"><label for="type" class="control-label">Customer Name</label></td>--}}
                        {{--<td width="10%">{!! Form::select('relationship_id',(['' => 'Please Select'] + $customers), null , array('id' => 'relationship_id', 'class' => 'form-control')) !!}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td width="5%"><label for="transDate" class="control-label" >Transaction Date</label></td>
                            <td width="10%">{!! Form::text('transDate', null, array('id' => 'transDate', 'class' => 'form-control','required','readonly')) !!}</td>

                        </tr>
                        {{--<tr>--}}
                        {{--<td width="5%"><label for="challanno" class="control-label">Challan No</label></td>--}}
                        {{--<td width="10%">{!! Form::text('challanno', null, array('id' => 'challanno', 'class' => 'challanno form-control','required','placeholder'=>'Enter 8 and select')) !!}</td>--}}
                        {{--{!! Form::hidden('id', null, array('id' => 'id')) !!}--}}
                        {{--</tr>--}}

                        <tr>
                            <td width="10%"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width="10%"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

            {{--<div class="col-md-2 col-md-offset-1">--}}
            {{--<article>--}}
            {{--<h1>Help Tips</h1>--}}
            {{--<p>Select <strong>Customer Name </strong> from dropdown. Then select date. Then enter <strong> 8 </strong> at the challan no field. You will find all posibble challan no numbers in the dropdown list.</p>--}}
            {{--</article>--}}

            {{--<p><strong>Note:</strong> To view the preview of the delivery challan submit Preview Button. To print the Challan No submit Print button.</p>--}}
            {{--</div>--}}

        </div>
    </div>


    @if(!empty($vouchers))
        @foreach($vouchers as  $i=> $voucher)
            @php($Total = 0)

            {{--@if($voucher->jCode != 'JOURNAL VOUCHER')--}}

                {{--<div class="col-md-10 col-md-offset-1" style="text-align: center">--}}
                    {{--{!! $voucher->company->compName !!}--}}
                {{--</div>--}}

            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default padding-left">
                    <div class="col-md-6 panel-heading">Voucher No : {!! $voucher->voucherNo !!} <br/>
                        Voucher Type : {!! $voucher->jCode !!}</div>
                    <div class="col-md-6 panel-heading" style="text-align: right">Voucher No : {!! $voucher->transDate !!} <br/>
                        User : {!! $voucher->userCreated !!}</div>
                    <table class="table table-bordered table-hover" >


                        <thead style="background-color: #3278b3">
                        <th colspan="2">Head of Accounts</th>
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
                            <td colspan="3">{!! convert_number_to_words($Total).' '.get_currency() !!}</td>
                            <td align="right">{{ number_format( $Total,2) }}</td>
                        </tr>
                        </tfoot>

                    </table>

                    <br pagebreak="true">

                </div>
            </div>

        {{--@endif--}}
        @endforeach
    @endif


@endsection

@push('scripts')

<script>

    $(function() {
        $("#transDate").datepicker({dateFormat: "dd/mm/yy"}).val();
    });

</script>

@endpush