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
        var transAmount = 0;
        var cashbal = 0;

        $(function () {

            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var controlForm = $('.controls form:first'),
                        currentEntry = $(this).parents('.entry:first'),
                        newEntry = $(currentEntry.clone(true)).appendTo(controlForm);
                counter++;
                currentEntry.find('select').each(function () { this.disabled=true });

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
            }).on('click','.btn-primary', function (event) {


                $("#transForm").find('select').each(function () { this.disabled=false });

            });
        });

        //START DROPDOWN DT

        jQuery(document).ready(function ($) {
            var $select = $('[name="grpCredit[]"]');

            $('select[name="grpCredit[]"]').eq(counter).change(function () {

//                alert("You have selected the - "+ $(this));

                $.get("{!! url('cr.credit.head')  !!}", {option: $('select[name="grpCredit[]"]').eq(counter).val()},
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

    </script>

    @include('partials.flashmessage')

    <br>

    <div class="col-sm-10 col-md-offset-1 text-left">
        <div class="well text-center" style="background-color: #8eb4cb">
            <p>{!! Form::label('voucher_no','CASH RECEIVE VOUCHER: '.$voucherNo.' CASH BALANCE: '.number_format(App\Util\GenUtil::get_account_balance(Session::get('comp_code'),'10112102'),2), array('class' => 'col-sm-12 control-label')) !!}
            </p>
        </div>
    </div>


    <div>
        <div class="well col-md-10 col-md-offset-1" style="background-color: rgba(157, 208, 240, 0.34)">
            <div class="controls">
                {!! Form::open(array('id'=>'transForm','url'=>'cr.trans.save')) !!}

                <div class="form-group">

                    {!! Form::label('acc_dr','Debit:', array('class' => 'col-md-1 control-label')) !!}
                    <div class="col-md-4">
                        {!! Form::select('accDr',$cashHead, null, array('id' => 'accDr', 'class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::text('crBall', number_format(App\Util\GenUtil::get_account_balance('10112102'),2) , array('id' => 'crBall', 'class' => 'form-control', 'readonly' => 'true')) !!}
                    </div>

                    {!! Form::label('date','Date:', array('class' => 'col-md-1 control-label')) !!}
                    <div class="col-md-3">
                        {!! Form::text('transDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'transdatepicker', 'class' => 'form-control','readonly' => 'true', 'data-mg-required' => '')) !!}
                    </div>

                </div>

                <br/>

                <div class="col-md-10 col-md-offset-1 text-left">
                    <div class="well text-center" style="background-color: #d8d8d7">
                        <p>{!! Form::label('credit','CREDIT DETAILS', array('class' => 'col-sm-12 control-label')) !!}
                        </p>
                    </div>
                </div>

                <br>
                <br>

                <table class="entry table table-responsive table-bordered padding table-hover" >

                    <tr>
                        @if(\App\Util\GenUtil::hasProjects(Session::get('comp_code')) == true)
                            <td>{!! Form::select('projCode[]',$projList, null, array('id' => 'projCode', 'class' => 'col-md-12 form-control', 'placeholder'=>'Select Project')) !!}</td>
                        @else
                            {!! Form::hidden('projCode[]', '999999', array('id' => 'projCode')) !!}
                        @endif

                        <td>{!! Form::select('grpCredit[]', $groupList, null,array('id' => 'grpCredit', 'class' => 'col-md-12 form-control')) !!}</td>
                        <td>{!! Form::select('accCr[]', array('' => 'Please Select'), null,array('id' => 'accCr', 'class' => 'col-md-12 form-control')) !!}</td>
                        <td>{!! Form::text('transAmt[]', null , array('id' => 'transAmt', 'class' => 'col-md-12 form-control transaction', 'required' => 'true','placeholder'=>'Amount')) !!}</td>
                        <td>{!! Form::text('transDesc1[]', null , array('id' => 'transDesc1', 'class' => 'col-md-12 form-control', 'placeholder'=>'Description')) !!}</td>
                        {!! Form::hidden('id[]', null, array('id' => 'id')) !!}
                        <td>
                                <span style="margin: 0 auto" class="input-group-btn col-xs-1">
                                  <button class="btn btn-success btn-add" type="button">
                                      <span class="glyphicon glyphicon-plus"></span>
                                  </button>
                                </span>
                        </td>
                    </tr>

                </table>

                <br>
            </div>

            <div class="col-md-6">
                {!! Form::hidden('voucherNo', $voucherNo, array('id' => 'voucherNo')) !!}
                {!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control']) !!}
            </div>

            {!! Form::close() !!}

            {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

            <div class="col-md-6">
                {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-right']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>


@endsection