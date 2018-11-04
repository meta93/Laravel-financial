@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')

    <script type='text/javascript'>

        {{-- Your JavaScript code goes here! --}}
        
    </script>

    <div class="col-sm-10 text-left col-sm-offset-1">

        {!! Form::open(['url'=>'compConfig']) !!}

        <div class="well">
            <div class="form-group">
                {!! Form::label('compCode','Company Code: '.$comp->compCode, array('class' => 'col-sm-6 control-label')) !!}
                {!! Form::label('compName','Company Name: '.$comp->compName, array('class' => 'col-sm-6 control-label')) !!}
            </div>
        </div>

        <div class="well col-md-6">

                <div class="form-group{{ $errors->has('cashAcc') ? ' has-error' : '' }}">

                    <label for="cashAcc" class="col-md-6 control-label pull-left">Cash Account Group:</label>

                    <div class="col-md-4">
                        {!! Form::text('cashAcc', $basic->cash, array('id' => 'cashAcc', 'class' => 'form-control','readonly')) !!}
                        @if ($errors->has('cashAcc'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('cashAcc') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>

        </div>
        <div class="well col-md-6">
                <div class="form-group{{ $errors->has('bankAcc') ? ' has-error' : '' }}">

                    <label for="bankAcc" class="col-md-6 control-label pull-left">Cash Account Group:</label>

                    <div class="col-md-4">
                        {!! Form::text('bankAcc', $basic->bank, array('id' => 'bankAcc', 'class' => 'form-control','readonly')) !!}
                        @if ($errors->has('bankAcc'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('bankAcc') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>
        </div>

        <div class="well col-md-6">

            <div class="form-group{{ $errors->has('salesAcc') ? ' has-error' : '' }}">

                <label for="salesAcc" class="col-md-6 control-label pull-left">Sales Account Group:</label>

                <div class="col-md-4">
                    {!! Form::text('salesAcc', $basic->sales, array('id' => 'salesAcc', 'class' => 'form-control','readonly')) !!}
                    @if ($errors->has('salesAcc'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('salesAcc') }}</strong>
                                </span>
                    @endif
                </div>
            </div>

        </div>
        <div class="well col-md-6">
            <div class="form-group{{ $errors->has('purchaseAcc') ? ' has-error' : '' }}">

                <label for="purchaseAcc" class="col-md-6 control-label pull-left">Purchase Account Group:</label>

                <div class="col-md-4">
                    {!! Form::text('purchaseAcc', $basic->purchase, array('id' => 'purchaseAcc', 'class' => 'form-control','readonly')) !!}
                    @if ($errors->has('purchaseAcc'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('purchaseAcc') }}</strong>
                                </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="well col-md-6">

            <div class="form-group{{ $errors->has('project') ? ' has-error' : '' }}">

                <label for="project" class="col-md-6 control-label pull-left">Has Project ?</label>

                <div class="col-md-4">
                    {!! Form::checkbox('project', $basic->compCode, $basic->project) !!}
                    {!! Form::hidden('id', $basic->id, array('id' => 'id',)) !!}
                    @if ($errors->has('project'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('project') }}</strong>
                                </span>
                    @endif
                </div>
            </div>

        </div>
        <div class="well col-md-6">
            <div class="form-group{{ $errors->has('inventory') ? ' has-error' : '' }}">

                <label for="inventory" class="col-md-6 control-label pull-left">Has Inventory ?</label>

                <div class="col-md-4">
                    {!! Form::checkbox('inventory', $basic->compCode, $basic->inventory) !!}
                    @if ($errors->has('inventory'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('inventory') }}</strong>
                                </span>
                    @endif
                </div>
            </div>
        </div>


        <div class="well col-md-6">

            <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }}">

                <label for="currency" class="col-md-6 control-label pull-left">Select Currency</label>

                <div class="col-md-4">
                    {!! Form::select('currency', \App\Util\GenUtil::get_currency_list(), $basic->currency , array('id' => 'currency', 'class' => 'form-control')) !!}
                    @if ($errors->has('currency'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('currency') }}</strong>
                                </span>
                    @endif
                </div>
            </div>
        </div>


        <div class="well col-md-6">

            <div class="form-group{{ $errors->has('fpStart') ? ' has-error' : '' }}">

                <label for="fpStart" class="col-md-6 control-label pull-left">Start Date Of Fiscal Period</label>

                <div class="col-md-4">
                    {!! Form::text('fpStart',\Carbon\Carbon::parse($basic->fpStart)->format('d/m/Y'), array('id' => 'fpStart', 'class' => 'form-control','readonly')) !!}
                    @if ($errors->has('fpStart'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('fpStart') }}</strong>
                                </span>
                    @endif
                </div>
            </div>
        </div>

        @if($basic->posted == false)

            <div class="col-md-6">
                {!! Form::submit('NEW',['class'=>'btn btn-primary button-control','name'=>'action', 'value'=>'NEW']) !!}
            </div>
        @endif

        @if($basic->posted == true)

            <div class="col-md-6">
                {!! Form::submit('UPDATE',['class'=>'btn btn-primary button-control','name'=>'action', 'value'=>'UPDATE']) !!}
            </div>
        @endif
            {!! Form::close() !!}

            {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

            <div class="col-md-6">
                {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-right']) !!}
            </div>
            {!! Form::close() !!}
        {{--</div>--}}

    </div>

    @include('partials.flashmessage')

@endsection

@push('scripts')

<script>

    $(function() {
        $("#fpStart").datepicker({dateFormat: "dd/mm/yy"}).val();
    });

</script>

@endpush