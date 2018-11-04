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
                <div><h3>Print Preview Sales Invoice</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'report.sales.invoice', 'method' => 'POST']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="5%"><label for="type" class="control-label">Customer Name</label></td>
                                <td width="10%">{!! Form::select('relationship_id',(['' => 'Please Select'] + $customers), null , array('id' => 'relationship_id', 'class' => 'form-control')) !!}</td>
                            </tr>
                            <tr>
                                <td width="5%"><label for="type" class="control-label" >Invoice Date</label></td>
                                <td width="10%">{!! Form::text('invoicedate', null, array('id' => 'invoicedate', 'class' => 'form-control','required','readonly')) !!}</td>

                            </tr>
                            <tr>
                                <td width="5%"><label for="invoiceno" class="control-label">Invoice No</label></td>
                                <td width="10%">{!! Form::text('invoiceno', null, array('id' => 'invoiceno', 'class' => 'invoiceno form-control','required')) !!}</td>
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
                    <p>Google Chrome is a free, open-source web browser developed by Google, released in 2008.</p>
                </article>

                <p><strong>Note:</strong> The article tag is not supported in Internet Explorer 8 and earlier versions.</p>
            </div>
        </div>
    </div>

@stop

@push('scripts')

<script>

    $(document).ready(function() {
        //Date picker

        var minday = new Date();

        $('#invoicedate').datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',
            maxDate: minday
        });
    });

    $( function() {
        $( "#invoiceno" ).autocomplete({
            source: "invoiceautocomplete",

            select: function(event, ui) {

                $("#id").val(ui.item.id);
            }
        });
    });

</script>

@endpush
