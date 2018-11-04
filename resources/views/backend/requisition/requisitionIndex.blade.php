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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>


    <script type='text/javascript'>
        {{-- Your JavaScript code goes here! --}}

    </script>



    <div class="col-sm-10 text-left col-sm-offset-1">
        <div class="controls">

            {!! Form::open(['url'=>'requisition.create.post','method' => 'POST']) !!}
            {{ csrf_field() }}

            {{--<div class="row col-md-6 col-md-offset-1" style="border-right: solid">--}}
                <table class="table table-sm table-responsive">
                    <tbody>
                    <tr>
                        <td>
                        <div class="form-group{{ $errors->has('reqType') ? ' has-error' : '' }}">
                            <label for="reqType" class="col-md-4 control-label">Requisition For</label>
                            <div class="col-md-6">
                                {!! Form::select('reqType', array('0' => 'Please Select', 'P' => 'Purchase', 'C' => 'Consumption'), null , array('id' => 'reqType', 'class' => 'form-control')) !!}
                            </div>
                        </div>
                        </td>
                        <td><label for="reqDate" class="control-label">Date</label></td>
                        <td>{!! Form::text('reqDate', \Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'reqDate', 'class' => 'form-control','required','disabled')) !!}</td>
                        <td align="right"><label for="refNo" class="control-label">Requisition No</label></td>
                        <td align="right">{!! Form::text('refNo','RQ'.$reqnumber , array('id' => 'refNo', 'class' => 'form-control')) !!}</td>
                    </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>

            {{--</div>--}}
            @include('partials.flashmessage')

            <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
                {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="items">
                        <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th width="5%"  class="text-center">Action</th>
                            <th width="40%" class="text-left">Product</th>
                            <th width="15%" class="text-center">Quantity</th>
                            <th width="40%" class="text-right">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $item_row = 0; ?>
                        <tr id="item-row-{{ $item_row }}">
                            <td class="text-center">
                                <button style="margin: 0 auto" type="button" onclick="$(this).tooltip('destroy'); $('#item-row-{{ $item_row }}').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                            <td>
                                <input class="form-control typeahead" required="required" placeholder="Enter Product" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">
                                <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
                            </td>
                            <td>
                                <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                            </td>
                            <td>
                                <input class="form-control" name="item[{{ $item_row }}][remarks]" type="text" id="item-remarks-{{ $item_row }}">
                            </td>
                        </tr>
                        <?php $item_row++; ?>
                        <tr id="addItem">
                            <td class="text-center"><button style="margin: 0 auto" type="button" onclick="addItem();" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                            <td class="text-right" colspan="5"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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

<script type="text/javascript">
    var item_row = '{{ $item_row }}';

    function addItem() {
        html  = '<tr id="item-row-' + item_row + '">';
        html += '  <td class="text-center" style="vertical-align: middle;">';
        html += '      <button style="margin: 0 auto" type="button" onclick="$(this).tooltip(\'destroy\'); $(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
        html += '  </td>';
        html += '  <td>';
        html += '      <input class="form-control typeahead" required="required" autocomplete="off" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('invoices.item_name', 1)]) }}" name="item[' + item_row + '][name]" type="text" id="item-name-' + item_row + '">';
        html += '      <input name="item[' + item_row + '][item_id]" type="hidden" id="item-id-' + item_row + '">';
        html += '  </td>';
        html += '  <td>';
        html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][quantity]" type="text" id="item-quantity-' + item_row + '">';
        html += '  </td>';
        html += '  <td>';
        html += '      <input class="form-control" name="item[' + item_row + '][remarks]" type="text" id="item-remarks-' + item_row + '">';
        html += '  </td>';

        $('#items tbody #addItem').before(html);
        //$('[rel=tooltip]').tooltip();

        $('[data-toggle="tooltip"]').tooltip('hide');

        item_row++;
    }

    $(document).ready(function(){
        //Date picker
        $('#reqDate').datepicker({
            format: 'dd-mm-yy',
            autoclose: true
        });


        var autocomplete_path = "{{ url('requisition.productlist') }}";

        $(document).on('click', '.form-control.typeahead', function() {
            input_id = $(this).attr('id').split('-');



            item_id = parseInt(input_id[input_id.length-1]);

            $(this).typeahead({
                minLength: 2,
                displayText:function (data) {
                    return data.value;
                },
                source: function (query, process) {
                    $.ajax({
                        url: autocomplete_path,
                        type: 'GET',
                        dataType: 'JSON',
                        data: 'query=' + query ,
                        success: function(data) {
                            return process(data);
                        }
                    });
                },
                afterSelect: function (data) {
                    $('#item-id-' + item_id).val(data.item_id);
                    $('#item-quantity-' + item_id).val('1');
                }
            });
        });
    });

</script>

@endpush