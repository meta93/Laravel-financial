@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

    <script type="text/javascript">

        $(document).ready(function() {
            $('select[name="grpCode"]').on('change', function() {
                var groupID = $(this).val();
                if(groupID) {
                    $.ajax({
                        url: 'generalLedgerIndex/' + groupID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="accNo"]').empty();
                            $.each(data, function(key, value) {

                                $('select[name="accNo"]').append('<option value="'+ key +'">'+ value +'</option>');

                            });
                        }
                    });
                }else{

                    $('select[name="accNo"]').empty();

                }
            });
        });

    </script>


    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-6 col-md-offset-2">
                <br/>
                <div><h3>General Ledger</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'general.ledger.index', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >


                        <tr>
                            <td width="5%"><label for="fromDate" class="control-label" >From Date</label></td>
                            <td width="10%">{!! Form::text('fromDate', Carbon\Carbon::now()->format('d/m/Y'), array('id' => 'fromDate', 'class' => 'form-control','required','readonly')) !!}</td>

                        </tr>

                        <tr>
                            <td width="5%"><label for="toDate" class="control-label" >To Date</label></td>
                            <td width="10%">{!! Form::text('toDate', Carbon\Carbon::now()->format('d/m/Y'), array('id' => 'toDate', 'class' => 'form-control','required','readonly')) !!}</td>

                        </tr>

                        <tr>
                            <td width="5%"><label for="grpCode" class="control-label">Group Head</label></td>
                            <td width="10%">{!! Form::select('grpCode',(['' => 'Please Select'] + $groupList), null , array('id' => 'grpCode', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td width="5%"><label for="accNo" class="control-label">Account Head</label></td>
                            <td width="10%">{!! Form::select('accNo',array(''=>'Please Select'), null , array('id' => 'accNo', 'class' => 'form-control')) !!}</td>
                        </tr>


                        <tr>
                            <td width="10%"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width="10%"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>
            {{--<div style="width: 5px"></div>--}}

            {{--<div class="col-md-2 col-md-offset-1">--}}
                {{--<article>--}}
                    {{--<h1>Help Tips</h1>--}}
                    {{--<p>Google Chrome is a free, open-source web browser developed by Google, released in 2008.</p>--}}
                {{--</article>--}}

                {{--<p><strong>Note:</strong> The article tag is not supported in Internet Explorer 8 and earlier versions.</p>--}}
            {{--</div>--}}
        </div>
    </div>



    {{--<div class="container">--}}

        {{--{!! Form::open(['url'=>'general.ledger.index','method'=>'GET']) !!}--}}

        {{--<div class="form-group col-md-offset-2">--}}


            {{--<div class="col-md-12">--}}
                {{--{!! Form::label('dateFormat','Date Format', array('class' => 'col-sm-3 control-label')) !!}--}}
                {{--{!! Form::label('dateFormat','DD/MM/YYYY', array('class' => 'col-sm-3 control-label')) !!}--}}
            {{--</div>--}}

            {{--<br/>--}}

            {{--{!! Form::label('date','From Date:', array('class' => 'col-md-2 control-label')) !!}--}}

            {{--<div class="col-md-3">--}}

                {{--{!! Form::text('fromDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'fromDate', 'class' => 'form-control', 'data-mg-required' => '')) !!}--}}
            {{--</div>--}}
            {{--<br>--}}
            {{--<br>--}}

            {{--{!! Form::label('date','To Date:', array('class' => 'col-sm-2 control-label')) !!}--}}
            {{--<div class="col-md-3">--}}

                {{--{!! Form::text('toDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'toDate', 'class' => 'form-control', 'data-mg-required' => '')) !!}--}}
            {{--</div>--}}
            {{--<br>--}}
            {{--<br>--}}
            {{--{!! Form::label('grpCode','GL Head:', array('class' => 'col-md-2 control-label')) !!}--}}
            {{--<div class="col-md-5">--}}

                {{--{!! Form::select('grpCode', $groupList, null, array('id' => 'grpCode', 'class' => 'form-control')) !!}--}}
            {{--</div>--}}
            {{--<br>--}}
            {{--<br>--}}
            {{--{!! Form::label('accNo','Account Head:', array('class' => 'col-md-2 control-label')) !!}--}}
            {{--<div class="col-md-5">--}}

                {{--{!! Form::select('accNo', array(''=>'Please Select'), null, array('id' => 'accNo', 'class' => 'form-control')) !!}--}}
            {{--</div>--}}
            {{--<br>--}}
            {{--<br>--}}
            {{--<br>--}}
            {{--<div class="col-md-8">--}}
                {{--{!! Form::submit('VIEW',['class'=>'btn btn-primary pull-left']) !!}--}}
                {{--</div>--}}

                {{--{!! Form::close() !!}--}}

                {{--{!! Form::open(['url'=>'home', 'method' => 'GET']) !!}--}}

                {{--<div class="col-md-4">--}}
                {{--{!! Form::submit('EXIT',['class'=>'btn btn-danger pull-right']) !!}--}}

                {{--{!! Form::close() !!}--}}
            {{--</div>--}}



        {{--</div>--}}
    {{--</div>--}}
    <br>
    <br>

    @php($opnBal = 0)
    @php($drTotal = 0)
    @php($crTotal = 0)

    @if(!empty($openingData))

        <div class="col-md-10 panel panel-default padding-left col-md-offset-1">
            <div class="panel-heading">OPENING BALANCE AS ON {!! $fromDate !!}</div>
            <div class="w3-margin-left w3-margin-right">
                <table class="table table-bordered table-hover" >
                    <colgroup>
                        <col style="width: 50px"/>
                        <col style="width: 150px"/>
                        <col style="width: 50px"/>
                    </colgroup>

                    <thead>
                    <th>Acc No</th>
                    <th>Acc Name</th>
                    <th style="text-align:right">Opening Balance</th>
                    </thead>
                    <tbody>
                    @foreach($openingData as  $i=> $data)
                        <tr style="background-color: #afffff">
                            <td>{{ $data->accNo }}</td>
                            <td>{{ $data->accName }}</td>

                            @if($data->opnBal >= 0)
                                <td align="right">{{ number_format($data->opnBal,2) }} (Dr)</td>
                            @else
                                <td align="right">{{ number_format($data->opnBal,2) }} (Cr)</td>
                            @endif

                            @php($opnBal= $data->opnBal)

                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <br>

            </div>
        </div>
    @endif


    @if(!empty($transData))

        <div class="col-md-10 panel panel-default padding-left col-md-offset-1">
            <div class="panel-heading">TRANSACTIONS FROM {!! $fromDate !!} TO {!! $toDate !!}</div>
            <div class="w3-margin-left w3-margin-right">
                <table class="table table-bordered table-hover" >
                    <colgroup>
                        <col style="width: 70px"/>
                        <col style="width: 50px"/>
                        <col style="width: 50px"/>
                        <col style="width: 150px"/>
                        <col style="width: 50px"/>
                        <col style="width: 50px"/>
                    </colgroup>

                    <thead>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Voucher No</th>
                    <th>Description</th>
                    <th style="text-align:right">Debit</th>
                    <th style="text-align:right">Credit</th>
                    {{--<th style="text-align:right">Opening Balance</th>--}}
                    </thead>
                    <tbody>
                    @foreach($transData as  $i=> $tData)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#afffff' }};">
                            <td>{{ $tData->transDate }}</td>
                            <td>{{ $tData->jCode }}</td>
                            <td>{{ $tData->voucherNo }}</td>
                            <td>{{ $tData->transDesc1 }}</td>
                            <td align="right">{{ number_format($tData->dr_amt,2) }}</td>
                            <td align="right">{{ number_format($tData->cr_amt,2) }}</td>

                            @php ($drTotal = $drTotal + $tData->dr_amt)
                            @php ($crTotal = $crTotal + $tData->cr_amt)

                        </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr style="background-color: {{ $drTotal % 2 == 0 ? '#aaaaaa': '#afffff' }};">
                        <th colspan="4">Periodic Total</th>
                        <td align="right">{{ number_format( $drTotal,2) }}</td>
                        <td align="right">{{ number_format( $crTotal,2) }}</td>
                    </tr>
                    </tfoot>

                </table>
                <br>

                @if( ($opnBal + $drTotal - $crTotal) > 0 )
                    <div class="col-sm-12 text-left">
                        <div class="well text-center" style="background-color: #0ecf78;">
                            <p>CLOSING BALANCE : DEBIT {{ number_format(($opnBal + $drTotal - $crTotal ),2) }}</p>
                        </div>
                    </div>

                @else

                    <div class="col-sm-12 text-left">
                        <div class="well text-center" style="background-color: #0ecf78;">
                            <p>CLOSING BALANCE : CREDIT {{ number_format(abs(($opnBal + $drTotal - $crTotal)),2) }}</p>
                        </div>
                    </div>
                @endif

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
        $("#fromDate").datepicker({dateFormat: "dd/mm/yy"}).val();
        $("#toDate").datepicker({dateFormat: "dd/mm/yy"}).val();
    });

</script>

@endpush