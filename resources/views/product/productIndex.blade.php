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
   
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-md-10 col-md-offset-1">
        <div class="well text-center" style="height: 50%; background-color: #f0f0ef">
            <p><strong>ADD EDIT UPDATE DELETE PRODUCT</strong></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New Product</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->



    <!-- Modal -->
    <!-- Create Unit Modal -->

    <div class="modal fade" id="modal-unit" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-unit-label">Add New Unit</h3>
                </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/unit.new') }}">
                    {{ csrf_field() }}

                    <div class="modal-body">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Unit Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="" autofocus required>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('formalName') ? ' has-error' : '' }}">
                            <label for="formalName" class="col-md-4 control-label">Formal Name</label>

                            <div class="col-md-6">
                                <input id="formalName" type="text" class="form-control" name="formalName" value="" required>

                                @if ($errors->has('formalName'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('formalName') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('noOfDecimalplaces') ? ' has-error' : '' }}">
                            <label for="noOfDecimalplaces" class="col-md-4 control-label">Decimal Places</label>

                            <div class="col-md-6">
                                <input id="noOfDecimalplaces" type="text" class="form-control" name="noOfDecimalplaces" value="" required>

                                @if ($errors->has('noOfDecimalplaces'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('noOfDecimalplaces') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{--MODAL DETAILS    --}}



    <div class="modal" id="modal-details">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Question Editor</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="sidebar-nav">
                                <div class="navbar navbar-default" role="navigation">
                                    {{--<div class="navbar-header">--}}
                                        {{--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">--}}
                                            {{--<span class="sr-only">Toggle navigation</span>--}}
                                            {{--<span class="icon-bar"></span>--}}
                                            {{--<span class="icon-bar"></span>--}}
                                            {{--<span class="icon-bar"></span>--}}
                                        {{--</button>--}}
                                    {{--</div>--}}
                                    <div class="navbar-collapse collapse sidebar-navbar-collapse">
                                        <ul class="nav navbar-nav">
                                            {{--<li><a href="#customer-div" data-parent="#accordion" data-toggle="collapse">Customers</a></li>--}}
                                            {{--<li><a href="#orders-div" data-parent="#accordion" data-toggle="collapse">Orders</a></li>--}}

                                            <li><a href="#details-form" onclick="showCustomers()"><i class="glyphicon glyphicon-eye-open"></i>Details</a></li>
                                            <li><a href="#image-form" onclick="showOrders()"><i class="glyphicon glyphicon-eye-open"></i>Image</a></li>

                                            <li><a href="#">Menu Item 3</a></li>
                                            <li><a href="#">Menu Item 4</a></li>

                                        </ul>
                                    </div><!--/.nav-collapse -->
                                </div>
                            </div>
                        </div>

                        <div class=" row col-md-8 col-md-offset-1">
                            <form id="details-form" class="form-horizontal" role="form" method="POST" action="{{ url('/product.details.update') }}">
                                {{ csrf_field() }}


                                {{--<div class="row form-group{{ $errors->has('name') ? ' has-error' : '' }}">--}}
                                {{--<label for="name" class="col-md-1 control-label">Name</label>--}}

                                {{--<div class="col-md-3">--}}
                                {{--<input id="d_name" type="text" class="form-control" name="name" value="" autofocus required>--}}
                                {{--</div>--}}

                                {{--<label for="sku" class="col-md-1 control-label">SKU</label>--}}

                                {{--<div class="col-md-3">--}}
                                {{--<input id="d_sku" type="text" class="form-control" name="sku" value="" required>--}}
                                {{--</div>--}}

                                {{--</div>--}}

                                <table id="tabonetable" class="table order-bank">

                                    <thead>
                                    <tr class="row-line" style="line-height: 200%">
                                        <th class="tablecell" style="width: 20%">Product Code</th>
                                        <th class="tablecell" style="width: 60%">Product Name</th>
                                        <th class="tablecell" style="width: 20%">Quantity</th>
                                        <th class="tablecell" style="width: 20%">Unit</th>
                                        <th class="tablecell" style="width: 20%">Delivery</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                </table>

                                {{--<div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">--}}
                                {{--<label for="sku" class="col-md-2 control-label">SKU</label>--}}

                                {{--<div class="col-md-3">--}}
                                {{--<input id="d_sku" type="text" class="form-control" name="sku" value="" required>--}}
                                {{--</div>--}}
                                {{--</div>--}}


                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-1">
                                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>




                        <form id="image-form" class="form-horizontal" role="form" method="POST" action="{{ url('/unit.image.update') }}">
                            {{ csrf_field() }}


                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Product Image</label>

                                <div class="col-md-6" id="prod-image">

                                    <img src="" class="imagepreview" style="width: 100%;" >

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="modal-footer">
                    <p>This is Modal Footer</p>
                    {{--<a href="#" data-dismiss="modal" class="btn">Close</a>--}}
                    {{--<a href="#" class="btn btn-primary">Save changes</a>--}}
                </div>
            </div>
        </div>
    </div>






    @include('partials.flashmessage')

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>id</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>In Hand</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
        </div>


    </div>

@stop

@push('scripts')

<script>

    $(document).ready(function(){
        $('#image-form').css("display", "none");
    });


    function showCustomers() {
        $('#details-form').show();
        $('#image-form').hide();
    }

    function showOrders() {
        $('#details-form').hide();
        $('#image-form').show();
    }

    $(function() {
        var table= $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'product.data.get',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'productCode', name: 'productCode' },
                { data: 'name', name: 'name' },
                { data: 'category.name', name: 'fcategory.name' },
                { data: 'brand.name', name: 'brand.name', defaultContent: '' },
                { data: 'unit_name', name: 'unit_name' },
                { data: 'unitPrice', name: 'unitPrice' },
                { data: 'onhand', name: 'onhand' },
                { data: 'status', name: 'status', orderable: false, searchable: false, printable: false}
//                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });

        $("body").on("click", "#users-table tbody tr", function (e) {
            e.preventDefault();

            var nTds = $('td', this)
            var id = $(nTds[0]).text()

//            alert(id);

            //Ajax Load data from ajax
            $.ajax({
                url : 'product/ajax_details/' + id,
                type: "GET",
                dataType: "JSON",

                success: function(data)
                {
                    $(".tabonedata").remove();
//
                    var trHTML = '';
                    $.each(data, function (i, item) {
                        trHTML += '<tr class="tabonedata"><td align="left">' + item.productCode + '</td><td>' +  item.name + '</td><td align="right">' + item.onhand + '</td></tr>';

                        $('.imagepreview').attr('src', item.image)
                    });
//
                    $('#tabonetable').append(trHTML);


//                    $('[id="d_name"]').val(data.name);
//                    $('[id="d_sku"]').val(data.sku);
//                    $('[id="d_size"]').val(data.size);
//                    $('[id="d_color"]').val(data.color);
//                    $('[name="firstName"]').val(data.firstName);
//                    $('[name="lastName"]').val(data.lastName);
//                    $('[name="gender"]').val(data.gender);
//                    $('[name="address"]').val(data.address);
//                    $('[name="dob"]').datepicker('update',data.dob);
                    $('#modal-details').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Product Details'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });

        });
    });

//    $("#users-table tbody tr").off("click").on("click", function () {alert('Here')});


    $('#users-table').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();

        if(document.getElementById('deleteData').value == 0)
        {
            alert('You Do Not Have Permission. Please Contact Administrator')
            return false
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = $(this).data('remote');
        // confirm then
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true}
        }).always(function (data) {
            $('#users-table').DataTable().draw(false);
        });
    });

    $('#users-table').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        if(document.getElementById('editData').value == 0)
        {
            alert('You Do Not Have Edit Permission. Please Contact Administrator')
            return false
        }
    });

    $(document).ready ( function () {
        //replace document below with enclosing container but below will work too
        $(document).on('click', '.btn-new', function () {
            if(document.getElementById('addData').value == 1)
            {
                window.location.href = "product.create.form";
            }else {
                alert('You Do Not Have Permission. Please Contact Administrator')
                return false
            }
        });
    });


</script>

@endpush

