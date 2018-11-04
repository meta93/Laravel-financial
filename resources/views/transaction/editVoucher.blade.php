@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
{{--@section('header')--}}
{{--@include('layouts.header')--}}
{{--@endsection--}}
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    @include('partials.flashmessage')

    <div style="height: 25px"></div>

    <div class="form-inline" id="inputclass">
        {!! Form::open(['route'=>'transaction.edit.index', 'method'=>'GET']) !!}
        <div class="col-md-10 col-md-offset-4">
            {!! Form::label('voucherNo','Voucher No:', array('class' => 'col-sm-1 control-label')) !!}
            {!! Form::text('voucherNo',null , array('id' => 'voucher_no', 'class' => 'col-sm-4 form-control', 'data-mg-required' => 'true')) !!}
            {!! Form::submit('Submit',['class'=>'btn btn-primary col-sm-3 form-control']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <div style="height: 25px"></div>
@if(!empty($trans))

    <div class="row">

        <div class = "col-md-10 col-md-offset-1">
            {!! Form::open(['route'=>'updatev.oucher.post']) !!}

            <table class="table table-responsive table-striped table-bordered padding" >

                <colgroup>
                    <col style="width: 50px"/>
                    <col style="width: 80px"/>
                    <col style="width: 150px"/>
                    <col style="width: 150px"/>
                    <col style="width: 50px"/>
                </colgroup>
                <thead style="background-color: #7ba9c8">
                    <th>Voucher No</th>
                    <th>Date</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                @foreach ($trans  as $i => $tran)
                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                        {!! Form::hidden('id[]', $tran->id, array('id' => 'id')) !!}
                        {!! Form::hidden('oldCrAcc[]', $tran->accCr, array('id' => 'oldCrAcc')) !!}
                        {!! Form::hidden('oldDrAcc[]', $tran->accDr, array('id' => 'oldDrAcc')) !!}
                        {!! Form::hidden('oldAmt[]', $tran->transAmt, array('id' => 'oldAmt')) !!}
                        {!! Form::hidden('jCode[]', $tran->jCode, array('id' => 'jCode')) !!}

                        <td>{!! Form::label('voucherNo[]',$tran->voucherNo, array('class' => 'col-sm-2 control-label')) !!}</td>
                        @if($i>0)
                        <td>{!! Form::text('transDate[]', Carbon\Carbon::parse($tran->transDate)->format('d/m/Y'), array('id' => 'transDate', 'class' => 'form-control', 'disabled')) !!}</td>
                        @else
                            <td>{!! Form::text('transDate[]',Carbon\Carbon::parse($tran->transDate)->format('d/m/Y'), array('id' => 'transDate', 'class' => 'form-control', 'data-mg-required' => '')) !!}</td>
                        @endif
                        <td>{!! Form::select('accDr[]',is_null($tran->accDr) ? ([''=>'Journal Voucher'] + $accountList->toArray()) : $accountList, $tran->accDr, array('id' => 'accDr', 'class' => 'form-control', is_null($tran->accDr) ? 'disabled' :'')) !!}</td>
                        <td>{!! Form::select('accCr[]',is_null($tran->accCr) ? ([''=>'Journal Voucher'] + $accountList->toArray()) : $accountList, $tran->accCr, array('id' => 'accCr', 'class' => 'form-control', is_null($tran->accCr) ? 'disabled' :'')) !!}</td>

                        <td>{!! Form::text('transAmt[]', $tran->transAmt , array('id' => 'transAmt', 'class' => 'form-control', 'data-mg-required' => '')) !!}</td>
                    </tr>
                @endforeach

                </tbody>

            </table>

            <div class="col-md-6">
                {!! Form::submit('UPDATE',['class'=>'btn btn-primary btn-submit pull-left']) !!}
                {!! Form::close() !!}
            </div>

            {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

            <div class="col-md-6">
                {!! Form::submit('EXIT',['class'=>'btn btn-primary pull-right']) !!}
            </div>
            {!! Form::close() !!}
        </div>

    </div>
@endif

@endsection
@push('scripts')

<script>

    $(document).ready(function() {
        $('input[name="transDate[]"]').eq(0).on('change', function () {
            var arr = document.getElementsByName('transDate[]');

            for( var x = 1; x < arr.length; x++ ) {
//                document.getElementsByName("unitPrice1").value = 10;
                document.getElementsByName("transDate[]")[x].value = document.getElementsByName("transDate[]")[0].value
            }
        });
    });

    $(document).on('click', '.btn-submit', function (e) {

        $(this).attr("disabled", true);

    });

        $('input[name="transDate[]"]').eq(0).datepicker({ dateFormat: "dd/mm/yy" }).val();
</script>

@endpush