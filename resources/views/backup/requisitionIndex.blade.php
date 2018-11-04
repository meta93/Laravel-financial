@extends('layouts.backend.master')
@section('banner')
    @include('layouts.backend.banner')
@endsection

@section('menu')
    @include('layouts.inventoryMenu')
@endsection
@section('content')

    <script type='text/javascript'>

        var counter = 0;
        var id = null;
        var value = null;


        $( function() {
            $( "#productCode" ).autocomplete({
                source: "requisition.productlist",

                select: function(event, ui) {

                    id = ui.item.id;
                    value = ui.item.value;
//
//                    var controlForm = $('.controls form:first'),
//                            currentEntry = $(this).parents('.entry:first'),
//                            newEntry = $(currentEntry.clone(true)).appendTo(controlForm);
//
//                    counter++;
//
//
//                    newEntry.find('input[name="productCode[]"]').val(ui.item.value);
//                    newEntry.find('input[name="id[]"]').val(ui.item.id);
//                    newEntry.find('input[name="productCode[]"]').focus();
//                    newEntry.find('input[name="productCode[]"]').each(function () { this.disabled=true });
//
//                    controlForm.find('input[name="productCode[]"]').eq(0).val('');
//                    return false;


                }
            });


        } );



        function new_row() {


            var controlForm = $('.controls form:first'),
                currentEntry = $('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            counter++;
            alert('here')
            newEntry.find('input[name="productCode[]"]').val(value);

            newEntry.find('input[name="id[]"]').val(id);
            newEntry.find('input[name="productCode[]"]').focus();

            newEntry.find('input[name="quantity[]"]').each(function () { this.disabled=true });


        }


    </script>



    <div class="controls" id="test">
        {!! Form::open(['url'=>'requisition.create.post']) !!}
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="form-group">
                    <label for="rack_id" class="control-label">Type</label>
                        {!! Form::select('reqType', (['P' => 'Purchase','R'=>'Requisition']) , null , array('id' => 'reqType', 'class' => 'form-control')) !!}
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="form-group">
                    <label for="reqDate" class="control-label">Date</label>
                    {!! Form::text('reqDate', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'reqDate', 'class' => 'form-control','required','disabled')) !!}
                </div>
            </div>

            {{--<div class="col-sm-6 col-md-4 col-lg-2">--}}
                {{--<div class="form-group">--}}
                    {{--<label for="refNo" class="control-label">Ref No</label>--}}
                    {{--{!! Form::text('refNo', $reqnumber , array('id' => 'refNo', 'class' => 'form-control','required','disabled')) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="clearfix visible-md-block"></div>

            <div class="col-sm-12 col-md-8 col-lg-4">
                <h2>Requisition</h2>
                <p>This screen is used to <strong>Requisition Products</strong>  for Both Purchase & Consumption</p>
            </div>

        </div>
        <div class="row">

            <table class="entry table table-responsive table-bordered padding table-hover" >

                <tr>
                    <td>{!! Form::text('productCode[]', null, array('id' => 'productCode', 'class' => 'productCode form-control','placeholder'=>'Enter Product')) !!}</td>
                    <td>{!! Form::text('quantity[]', null, array('id' => 'quantity', 'class' => 'form-control','onblur'=>'new_row()','placeholder'=>'Enter Quantity')) !!}</td>
                    <td>{!! Form::text('remarks[]', null, array('id' => 'remarks', 'class' => 'form-control','placeholder'=>'Remarks')) !!}</td>
                    {!! Form::hidden('id[]', null, array('id' => 'id')) !!}
                    {{--<td>--}}
                            {{--<span class="input-group-btn col-xs-1">--}}
                            {{--<button class="btn btn-danger btn-remove" type="button">--}}
                            {{--<span class="glyphicon glyphicon-remove">del</span>--}}
                            {{--</button>--}}
                            {{--</span>--}}
                    {{--</td>--}}
                </tr>

            </table>


            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">Add Products</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="entry">--}}

                                {{--<div class="col-sm-10 col-md-4 col-lg-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--{!! Form::text('productCode', null , array('id' => 'productCode', 'class' => 'form-control','required','placeholder'=>'Product')) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-10 col-md-4 col-lg-3">--}}
                                    {{--<div class="form-group">--}}
                                        {{--{!! Form::text('quantity', null , array('id' => 'quantity', 'onblur'=>'new_row()','class' => 'form-control','required','placeholder'=>'Quantity')) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="col-sm-10 col-md-4 col-lg-3">--}}
                                    {{--<div class="form-group">--}}
                                        {{--{!! Form::text('remarks', null , array('id' => 'remarks', 'class' => 'form-control','required','placeholder'=>'Remarks')) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="row">


    <div class="col-md-6">
        {!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control']) !!}
    </div>

    {!! Form::close() !!}

    {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

    <div class="col-md-6">
        {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-right']) !!}
    </div>
    {!! Form::close() !!}
    </div>
@endsection
