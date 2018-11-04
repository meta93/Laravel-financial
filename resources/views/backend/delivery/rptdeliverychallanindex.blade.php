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

    <script>

    </script>

    @include('partials.flashmessage')


    <div class="container spark-screen fullpage">
        <div class="row">
            <div class="col-md-6 col-md-offset-2" style="border-right: solid; overflow: scroll; height: 700px">
                <br/>
                <div><h3>Print Preview Delivery Challan</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'report.delivery.challan', 'method' => 'POST']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="type" class="control-label">Customer Name</label></td>
                            <td width="10%">{!! Form::select('relationship_id',(['' => 'Please Select'] + $customers), null , array('id' => 'relationship_id', 'class' => 'form-control')) !!}</td>
                        </tr>
                        <tr>
                            <td width="5%"><label for="challandate" class="control-label" >Delivery Date</label></td>
                            <td width="10%">{!! Form::text('challandate', null, array('id' => 'challandate', 'class' => 'form-control','required','readonly')) !!}</td>

                        </tr>
                        <tr>
                            <td width="5%"><label for="challanno" class="control-label">Challan No</label></td>
                            <td width="10%">{!! Form::text('challanno', null, array('id' => 'challanno', 'class' => 'challanno form-control','required','placeholder'=>'Enter 8 and select')) !!}</td>
                            {!! Form::hidden('id', null, array('id' => 'id')) !!}
                        </tr>

                        <tr>
                            <td width="10%"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width="10%"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

            <div class="col-md-2 col-md-offset-1">
                <article>
                    <h1>Help Tips</h1>
                    <p>Select <strong>Customer Name </strong> from dropdown. Then select date. Then enter <strong> 8 </strong> at the challan no field. You will find all posibble challan no numbers in the dropdown list.</p>
                </article>

                <p><strong>Note:</strong> To view the preview of the delivery challan submit Preview Button. To print the Challan No submit Print button.</p>
            </div>

        </div>
    </div>

@stop

@push('scripts')

<script>

    $(document).ready(function() {
        //Date picker

        var minday = new Date();

        $('#challandate').datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',
            maxDate: minday
        });
    });

    $( function() {
        $( "#challanno" ).autocomplete({
            source: "challanautocomplete",

            select: function(event, ui) {

                $("#id").val(ui.item.id);
            }
        });
    });

</script>

@endpush
