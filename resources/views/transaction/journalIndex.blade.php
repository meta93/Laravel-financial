@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <script type='text/javascript'>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var counter = 0;
        var debit = 0;
        var credit = 0;

        $(function () {
            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

//                var controlForm = $('.controls form:first'),

                var controlForm = $('.controls form:first'),

                        currentEntry = $(this).parents('.entry:first'),
                        newEntry = $(currentEntry.clone(true)).appendTo(controlForm);

                counter++;
                currentEntry.find('select').each(function () { this.disabled=true

                });

                newEntry.find('input').val('');
                newEntry.find('select').val('');

                controlForm.find('.entry:not(:last) .btn-add')
                        .removeClass('btn-add').addClass('btn-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            }).on('click','.btn-save', function (event) {

                $("#transForm").find('select').each(function () { this.disabled=false });

                $("#transForm").find('input.debitamount').each(function () {
                    debit += parseInt($(this).val()||0); });

                $("#transForm").find('input.creditamount').each(function () {
                    credit += parseInt($(this).val()||0); });
                if(debit != credit)
                {
                    alert('Transaction Debit & Credit Amount are not same');
                    debit  = 0;
                    credit = 0;
                    return false;
                }
                else
                {
                    debit  = 0;
                    credit = 0;
                    return true;
                }
            });
        });



        jQuery(document).ready(function ($) {
            var $select = $('[name="grpDebit[]"]');

            $('select[name="grpDebit[]"]').eq(counter).change(function () {

                $.get("{!! url('bp.debit.head')  !!}", {option: $('select[name="grpDebit[]"]').eq(counter).val()},
                        function (data) {
                            var accDr = $('select[name="accDr[]"]').eq(counter);
                            accDr.empty();
                            $.each(data, function (key, value) {
                                accDr.append($("<option></option>")
                                        .attr("value", key)
                                        .text(value));
                            });
                        });
            });
        });

        jQuery(document).ready(function ($) {
            var $select = $('[name="grpCredit[]"]');

            $('select[name="grpCredit[]"]').eq(counter).change(function () {

                $.get("{!! url('br.credit.head')  !!}", {option: $('select[name="grpCredit[]"]').eq(counter).val()},
                        function (data) {
                            var accCr = $('select[name="accCr[]"]').eq(counter);
                            accCr.empty();
                            $.each(data, function (key, value) {
                                accCr.append($("<option></option>")
                                        .attr("value", key)
                                        .text(value));
                            });
                        });
            });
        });



        function showInput() {
            document.getElementById('crAmt').value = document.getElementById('drAmt').value;
        }

        jQuery(document).ready(function($) {
            $('#dr_acc').change(function(){
                $.get("{!! url('newJournal/getDebitBall')  !!}", { option: $('#dr_acc').val() },
                        function(data) {
                            var debitBall = $('#debitBall');
                            debitBall.empty();
                            $.each(data, function(key, value) {
                                debitBall.val(value);
                            });
                        });
            });
        })

        jQuery(document).ready(function($) {
            $('#cr_acc').change(function(){
                $.get("{!! url('newJournal/getCreditBall')  !!}", { option: $('#cr_acc').val() },
                        function(data) {
                            var creditBall = $('#creditBall');
                            creditBall.empty();
                            $.each(data, function(key, value) {
                                creditBall.val(value);
                            });
                        });
            });
        })

        $(function(){
            $('#cr_acc').change(function(e) {
                document.getElementById('creditBall').style.color="magenta";
            });
        });

        $(function(){
            $('#dr_acc').change(function(e) {
                document.getElementById('debitBall').style.color="magenta";
            });
        });

        {{-- Your JavaScript code goes here! --}}
    </script>

 @include('partials.flashmessage')

    <br>
<div class="col-md-offset-1">
    <div class="col-sm-11 text-left">
        <div class="well text-center" style="background-color: #8eb4cb">
            <p>{!! Form::label('voucher_no','Payment No: '.$voucherNo , array('class' => 'col-sm-12 control-label')) !!}
            </p>
        </div>
    </div>
    <br>

    <div class="controls col-sm-11">

        {!! Form::open(['id'=>'transForm', 'url'=>'jv.trans.save']) !!}


        <div class="form-group">

            @if(Projects() == true)

            {!! Form::label('projCode','Project :', array('class' => 'col-md-1 control-label')) !!}
            <div class="col-md-3">
                {!! Form::select('projCode',([''=>'Please Select'] + $projList) , null, array('id' => 'projCode', 'class' => 'form-control')) !!}
            </div>
            @else
                {!! Form::hidden('projCode[]', '999999', array('id' => 'projCode')) !!}
            @endif

            <div class="col-md-4">
                {!! Form::text('transDesc1', null , array('id' => 'transDesc1', 'class' => 'form-control','placeholder'=>'Description')) !!}
            </div>

            {!! Form::label('date','Date:', array('class' => 'col-md-1 control-label')) !!}
            <div class="col-md-3">
                {!! Form::text('transDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'transdatepicker', 'class' => 'form-control','readonly' => 'true', 'data-mg-required' => '')) !!}
            </div>

        </div>



        {{--<div class="form-group">--}}
            {{--{!! Form::label('date','Date:', array('class' => 'col-xs-1 control-label')) !!}--}}
            {{--{!! Form::text('transDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'transdatepicker', 'class' => 'col-xs-3 control-text','readonly' => 'true')) !!}--}}
            {{--{!! Form::text('transDesc1', null , array('id' => 'transDesc1', 'class' => 'col-sm-8 control-text','placeholder'=>'Description')) !!}--}}
        {{--</div>--}}

        {{--@if(\App\Util\GenUtil::hasProjects(Session::get('comp_code')) == true)--}}
            {{--<div class="col-xs-12">--}}
                {{--{!! Form::label('projCode','Project:', array('class' => 'col-xs-1 control-label')) !!}--}}
                {{--{!! Form::select('projCode',$projList, null, array('id' => 'projCode', 'class' => 'col-sm-4 control-text')) !!}--}}
            {{--</div>--}}
        {{--@else--}}
            {{--{!! Form::hidden('projCode[]', '999999', array('id' => 'projCode')) !!}--}}
        {{--@endif--}}
        <br>
        <br>

        <div class="entry">
            <div class="col-sm-12 panel panel-default padding-left" style="overflow-x:auto;">
                {{--<div class="panel-heading">Debit."                 ". Credit</div>--}}
                <table class="table table-bordered table-hover padding" >

                    <th colspan="3" style="background-color: #66afe9"><strong>DEBIT ENTRY</strong></th>
                    <th colspan="3" style="background-color: #8eb4cb"><strong>CREDIT ENTRY</strong></th>
                    <tr>
                        <td style="background-color: #66afe9">{!! Form::select('grpDebit[]', $groupList, null,array('id' => 'grpDebit', 'class' => 'form-control')) !!}</td>
                        <td style="background-color: #66afe9">{!! Form::select('accDr[]', array('' => 'Please Select'), null,array('id' => 'accDr', 'class' => 'form-control')) !!}</td>
                        <td style="background-color: #66afe9">{!! Form::text('drAmt[]', null , array('id' => 'drAmt','onKeyUp'=>'showInput()', 'class' => 'form-control debitamount', 'placeholder'=>'Debit Amount')) !!}</td>

                        <td style="background-color: #8eb4cb">{!! Form::select('grpCredit[]', $groupList, null,array('id' => 'grpCredit', 'class' => 'form-control')) !!}</td>
                        <td style="background-color: #8eb4cb">{!! Form::select('accCr[]', array('' => 'Please Select'), null,array('id' => 'accCr', 'class' => 'form-control')) !!}</td>
                        <td style="background-color: #8eb4cb">{!! Form::text('crAmt[]',null , array('id' => 'crAmt', 'class' => 'form-control creditamount','placeholder'=>'Credit Amount')) !!}</td>
                        {!! Form::hidden('id[]', null, array('id' => 'id')) !!}


                    <td>
                        <span class="input-group-btn col-xs-1">
                        <button style="margin: 0 auto" class="btn btn-success btn-add" type="button">
                          <span class="glyphicon glyphicon-plus"></span>
                      </button>
                    </span>
                    </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    <br>
    <br>
    <div class="col-xs-4">
        {!! Form::hidden('voucherNo', $voucherNo, array('id' => 'voucherNo')) !!}
        {!! Form::submit('SUBMIT',['class'=>'btn btn-primary btn-save button-control pull-left']) !!}
    </div>

    {!! Form::close() !!}

    {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

    <div class="col-xs-3">
        {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-center']) !!}
    </div>
    {!! Form::close() !!}
    {!! Form::open(array('url' => 'printJVoucher')) !!}

    <div class="col-xs-3">
        {!! Form::submit('PRINT',['class'=>'btn btn-primary button-control pull-right']) !!}
    </div>
    {!! Form::close() !!}
    {{--</div>--}}
</div>

@stop