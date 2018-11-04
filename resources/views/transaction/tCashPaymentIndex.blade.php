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

    <script>
        var dateToday = new Date();
        $(function() {
            $( "#transdatepicker" ).datepicker({
                numberOfMonths: 1,
                showButtonPanel: true,
                minDate: dateToday,
                dateFormat: "dd/mm/yy"
            });
        });
    </script>

    <script type='text/javascript'>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function () {
            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var controlForm = $('.controls form:first'),

                        currentEntry = $(this).parents('.entry:first'),
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);

                newEntry.find('input').val('');

                controlForm.find('.entry:not(:last) .btn-add')
                        .removeClass('btn-add').addClass('btn-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });
        });

        jQuery(document).ready(function ($) {
            $('#grpDebit').change(function(){
                alert("You have selected the country - " + $('#grpDebit').val());
                $.get("{!! url('cp.debit.head')  !!}", { option: $('#grpDebit').val() },
                        function(data) {
                            var accDr = $('#accDr')||[$i];
                            accDr.empty();
                            $.each(data, function(key, value) {
                                accDr.append($("<option></option>")
                                        .attr("value",key)
                                        .text(value));
                            });
                        });
            });
        })


        {{--jQuery(document).ready(function($) {--}}
        {{--$('#grpDebit').change(function(){--}}
        {{--$.get("{!! url('cp.debit.head')  !!}", { option: $('#grpDebit').val() },--}}
        {{--function(data) {--}}
        {{--var accDr = $('#accDr');--}}
        {{--accDr.empty();--}}
        {{--$.each(data, function(key, value) {--}}
        {{--accDr.append($("<option></option>")--}}
        {{--.attr("value",key)--}}
        {{--.text(value));--}}
        {{--});--}}
        {{--});--}}
        {{--});--}}
        {{--})--}}

    </script>


    <div class="col-sm-12 text-left">
        <div class="well text-center">
            <p>{!! Form::label('voucher_no','CASH PAYMENT VOUCHER: '.$voucherNo.' CASH BALANCE: '.number_format(App\Util\GenUtil::get_account_balance(Session::get('comp_code'),'10112102'),2), array('class' => 'col-sm-12 control-label')) !!}
            </p>
        </div>
    </div>


    <div>
        <div class="well col-md-10 col-md-offset-1">
            <div class="controls">
                {!! Form::open(['url'=>'saveCPVoucher']) !!}
                <div class="col-md-12">
                    {!! Form::label('date','Date:', array('class' => 'col-sm-2 control-label')) !!}
                    {!! Form::text('transDate', Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'transdatepicker', 'class' => 'col-sm-3 control-text','readonly' => 'true', 'data-mg-required' => '')) !!}
                </div>
                <br>
                <div class="col-md-12">
                    {!! Form::label('acc_cr','Credit:', array('class' => 'col-sm-2 control-label')) !!}
                    {!! Form::select('accCr',$cashHead, null, array('id' => 'accCr', 'class' => 'col-sm-6 control-text')) !!}
                    {!! Form::text('crBall', number_format(App\Util\GenUtil::get_account_balance(Session::get('comp_code'),'10112102'),2) , array('id' => 'crBall', 'class' => 'col-sm-3 control-text', 'readonly' => 'true')) !!}
                </div>
                <br>
                <br>
                <br>
                {{--<div class="well-sm"></div>--}}
                <div class="entry input-group col-sm-offset-1 well-sm">

                    @if(\App\Util\GenUtil::hasProjects(Session::get('comp_code')) == true)
                        <div class="col-sm-11">
                            {!! Form::label('projCode','Project:', array('class' => 'col-sm-3 control-label')) !!}
                            {!! Form::select('projCode',$cashHead, null, array('id' => 'projCode', 'class' => 'col-sm-8 control-text')) !!}
                        </div>
                    @endif

                    <div class="col-md-11">
                        {!! Form::label('grpDr','Debit Group:', array('class' => 'col-sm-3 control-label')) !!}
                        {!! Form::select('grpDebit[]', $groupList, null,array('id' => 'grpDebit', 'class' => 'col-sm-5 control-text')) !!}
                    </div>
                    <br>
                    <div class="col-md-11">
                        {!! Form::label('accDr','Debit Head:', array('class' => 'col-sm-3 control-label')) !!}
                        {!! Form::select('accDr[]', array('' => 'Please Select'), null,array('id' => 'accDr', 'class' => 'col-sm-5 control-text')) !!}
                        {!! Form::text('drBall', null , array('id' => 'drBall', 'class' => 'col-sm-3 control-text', 'readonly' => 'true')) !!}
                    </div>
                    <br>
                    <div class="col-md-11">
                        {!! Form::label('trans_amt','Trans Amt:', array('class' => 'col-sm-3 control-label')) !!}
                        {!! Form::text('trans_amt[]', null , array('id' => 'trans_amt', 'class' => 'col-sm-4 control-text', 'data-mg-required' => '')) !!}
                        {!! Form::hidden('id[]', null, array('id' => 'id')) !!}
                    </div>
                    <br>
                    <div class="col-md-11">
                        {!! Form::label('ref_no','Ref/Chq No:', array('class' => 'col-sm-3 control-label')) !!}
                        {!! Form::text('ref_no[]', 'N/A' , array('id' => 'ref_no', 'class' => 'col-sm-6 control-text', 'data-mg-required' => '')) !!}
                    </div>
                    <br>

                    <div class="col-md-11">
                        {!! Form::label('desc','Desc:', array('class' => 'col-sm-3 control-label')) !!}
                        {!! Form::text('trans_desc1[]', 'N/A' , array('id' => 'trans_desc1', 'class' => 'col-sm-6 control-text', 'data-mg-required' => '')) !!}
                    </div>

                    <span class="input-group-btn col-xs-1">
                      <button class="btn btn-success btn-add" type="button">
                          <span class="glyphicon glyphicon-plus"></span>
                      </button>
                    </span>
                </div>
                <br>
            </div>
        </div>
    </div>

@endsection