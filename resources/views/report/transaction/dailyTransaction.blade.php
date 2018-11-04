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
                <div><h3>Print Preview Daily Transactions</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['route'=>'report.dailytrans.rpt', 'method' => 'GET']) !!}

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



    {{--<div style="height: 25px"></div>--}}

    {{--<div class="container">--}}
        {{--{!! Form::open(['route'=>'report.dailytrans.rpt', 'method' => 'GET']) !!}--}}

        {{--<div class="form-group">--}}
            {{--{!! Form::label('date','Date:', array('class' => 'col-md-1 control-label')) !!}--}}

            {{--<div class="col-md-2">--}}
                {{--{!! Form::text('transDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'transDate', 'class' => 'form-control','readonly', 'required')) !!}--}}
            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--{!! Form::submit('VIEW',['class'=>'btn btn-primary form-control']) !!}--}}
            {{--</div>--}}

        {{--</div>--}}

        {{--{!! Form::close() !!}--}}

    {{--</div>--}}
    <br>
    <br>

    @php($drTotal = 0)
    @php($crTotal = 0)


<!-- error was below is fixed @1:57am-->
    @if(count($data=[]) >0 )
        <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default padding-left">
            <div class="panel-heading">Transaction List For The Date: {!! $data[0]->transDate !!}</div>

                <table class="table table-bordered table-hover" >
                    <colgroup>
                        <col style="width: 65px"/>
                        <col style="width: 50px"/>
                        <col style="width: 40px"/>
                        <col style="width: 150px"/>
                        <col style="width: 50px"/>
                        <col style="width: 200px"/>
                        <col style="width: 80px"/>
                        <col style="width: 80px"/>
                        <col style="width: 50px"/>
                        {{--<col style="width: 50px"/>--}}
                    </colgroup>

                    <thead style="background-color: #3278b3">
                        <th>Date</th>
                        <th>Type</th>
                        <th>Voucher No</th>
                        <th>Description</th>
                        <th>Acc No</th>
                        <th>Acc Name</th>
                        <th style="text-align:right">Debit</th>
                        <th style="text-align:right">Credit</th>
                        <th style="text-align:right">User</th>
                    </thead>
                    <tbody>

                    @foreach($data as  $i=> $row)
                        {{--@if(!is_null($row->accDr ))--}}
                            <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                                <td>{{ $row->transDate }}</td>
                                <td>{{ $row->jCode }}</td>
                                <td>{{ $row->voucherNo }}</td>
                                <td>{{ $row->transDesc1 }}</td>
                                <td>{{ $row->acc_no }}</td>
                                <td>{{ $row->dr_acc->accName }}</td>
                                <td align="right">{{ number_format($row->dr_amt,2) }}</td>
                                <td align="right">{{ number_format($row->cr_amt,2) }}</td>
                                <td align="right">{{ $row->userCreated }}</td>

                                @php($crTotal = $crTotal + $row->cr_amt )
                                @php($drTotal = $drTotal + $row->dr_amt)

                            </tr>
                        {{--@endif--}}
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr style="background-color: {{ $drTotal % 2 == 0 ? '#aaaaaa': '#afffff' }};">
                        <th>Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <td align="right">{{ number_format( $drTotal,2) }}</td>
                        <td align="right">{{ number_format( $crTotal,2) }}</td>
                        <td></td>
                    </tr>
                    </tfoot>

                </table>
                <br>

                {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

                <div class="col-md-4 pull-right">
                    {!! Form::submit('EXIT',['class'=>'btn btn-primary form-control']) !!}
                </div>
                {!! Form::close() !!}
            {{--</div>--}}
        </div>
        </div>
    @endif


@endsection

@push('scripts')

<script>

    $(function() {
        $("#transDate").datepicker({dateFormat: "dd/mm/yy"}).val();
        $("#printDate").datepicker({dateFormat: "dd/mm/yy"}).val();
    });

</script>

@endpush