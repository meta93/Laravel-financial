@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.inventoryMenu')
@endsection

@section('content')

    <script type='text/javascript'>

//        document.getElementById('productName').hide();

        var counter = 0;


        $( function() {
            $( "#productCode" ).autocomplete({
                source: "purchase/itemdetails",

                select: function(event, ui) {

                    var controlForm = $('.controls form:first'),
                            currentEntry = $(this).parents('.entry:first'),
                            newEntry = $(currentEntry.clone(true)).appendTo(controlForm);

                    counter++;


                    newEntry.find('input[name="productCode[]"]').val(ui.item.value);
                    newEntry.find('input[name="id[]"]').val(ui.item.id);
                    newEntry.find('input[name="productCode[]"]').focus();
                    newEntry.find('input[name="productCode[]"]').each(function () { this.disabled=true });

                    controlForm.find('input[name="productCode[]"]').eq(0).val('');
                    return false;


                }
            });


        } );



        function lineTotal() {
//            document.getElementsByName("subTotal[]").value = 100;

//            var arr = document.getElementsByName('purchasedQty[]');
//            alert('here');
//            for( var x = 0; x < arr.length; x++ ) {
        //                document.getElementsByName("unitPrice1").value = 10;
                document.getElementsByName("subTotal[]")[counter].value = Math.round((document.getElementsByName("qty[]")[counter].value * document.getElementsByName("unitPrice[]")[counter].value).toFixed(2));
//            }

        }



        {{-- Your JavaScript code goes here! --}}

    </script>

    @include('partials.flashmessage')

    <div class="col-sm-10 text-left col-sm-offset-1">
        <div class="controls">

            {!! Form::open(['url'=>'purchase.create.order']) !!}
            {{ csrf_field() }}

            <table class="table table-sm table-responsive">
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th colspan="2">Supplier</th>--}}
                    {{--<th></th>--}}
                    {{--<th style="text-align: right">Order No</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                <tbody>
                    <tr>
                        <td><label for="relationship_id" class="control-label">Suppliers</label></td>
                        <td>{!! Form::select('relationship_id', $suppliers , null , array('id' => 'relationship_id', 'class' => 'col-md-6 form-control','placeholder' => 'Select Suppliers...')) !!}</td>
                        <td><button type="button" class="btn btn-default btn-primary" data-toggle="modal" data-target="#modal-unit"><i class="glyphicon glyphicon-plus-sign"></i></button></td>
                        <td></td>
                        <td align="right"><label for="relationship_id" class="control-label">Order No</label></td>
                        <td align="right">{!! Form::text('orderNo','PO'.$ponumber , array('id' => 'orderNo', 'class' => 'form-control')) !!}</td>
                    </tr>
                </tbody>
                <tfoot></tfoot>
            </table>


            <div class="panel panel-default">
                <div class="panel-heading">Add Products with Quantity & Unit Price</div>
                {{--<div class="panel-heading">Education Experience</div>--}}
                <div class="panel-body">

                    <table class="entry table table-responsive table-bordered padding table-hover" >

                        <tr>
                            <td>{!! Form::text('productCode[]', null, array('id' => 'productCode', 'class' => 'productCode form-control','placeholder'=>'Enter Product')) !!}</td>
                            <td>{!! Form::text('qty[]', null, array('id' => 'qty', 'class' => 'form-control','placeholder'=>'Enter Quantity')) !!}</td>
                            <td>{!! Form::text('unitPrice[]', null, array('id' => 'unitPrice', 'onKeyUp'=>'lineTotal()', 'class' => 'form-control','placeholder'=>'Enter Unit Price')) !!}</td>
                            <td align="right">{!! Form::text('subTotal[]', null , array('id' => 'subTotal', 'class' => 'col-sm-12 form-control', 'placeholder'=>'Sub Total','disabled')) !!}</td>
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

                </div>
                {{--<div class="panel-footer"></div>--}}
            </div>


        </div>


        <div class="col-md-6">
            {!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control']) !!}
        </div>
        {!! Form::close() !!}

        {!! Form::open(['url'=>'inventoryHome', 'method' => 'GET']) !!}

        <div class="col-md-6">
            {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-right']) !!}
        </div>
        {!! Form::close() !!}

    </div>





@endsection

@push('scripts')

<script>

    $(function() {
        $("#tdate").datepicker({dateFormat: "dd/mm/yy"}).val();
    });

</script>

@endpush