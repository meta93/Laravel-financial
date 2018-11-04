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
            <p><strong>ADD EDIT UPDATE DELETE CUSTOMERS & SUPPLIERS</strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {{--<div class="col-lg-10 margin-tb">--}}
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New Supplier/Customer</button>
                {{--<button type="button" class="btn btn-cust btn-success"><i class="glyphicon glyphicon-plus"></i>New Customer</button>--}}
            </div>
        </div>
    </div>

    @include('partials.flashmessage')

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="modal-register-label">New Customer/ Supplier</h3>
                </div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/relationship.data.new') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">

                        <legend style="font-size: large;"><b> Company Details : </b><span style="font-size: medium">basic information on this company</span> </legend>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Company Name</label>
                            <div class="col-md-6">
                                {!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control','required','autofocus')) !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">Company Type</label>

                            <div class="col-md-6">
                                {!! Form::select('type', array('0' => 'Please Select', 'S' => 'Supplier', 'C' => 'Customer'), null , array('id' => 'type', 'class' => 'form-control')) !!}
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('company_code') ? ' has-error' : '' }}">
                            <label for="company_code" class="col-md-4 control-label">Company Code</label>
                            <div class="col-md-6">
                                {!! Form::text('company_code', null, array('id' => 'company_code', 'class' => 'form-control')) !!}
                                @if ($errors->has('company_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <label for="tax_number" class="col-md-4 control-label">Company Tax Number</label>
                            <div class="col-md-6">
                                {!! Form::text('tax_number', null, array('id' => 'tax_number', 'class' => 'form-control')) !!}
                                @if ($errors->has('tax_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tax_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('glcode') ? ' has-error' : '' }}">
                            <label for="glcode" class="col-md-4 control-label">Company GL Code</label>
                            <div class="col-md-6">
                                {!! Form::text('glcode', null, array('id' => 'glcode', 'class' => 'form-control')) !!}
                                @if ($errors->has('glcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('glcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <label for="phone_number" class="col-md-4 control-label">Company Phone Number</label>
                            <div class="col-md-6">
                                {!! Form::text('phone_number', null, array('id' => 'phone_number', 'class' => 'form-control')) !!}
                                @if ($errors->has('phone_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                            <label for="website" class="col-md-4 control-label">Company Website</label>
                            <div class="col-md-6">
                                {!! Form::text('website', null, array('id' => 'website', 'class' => 'form-control')) !!}
                                @if ($errors->has('website'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('website') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Company Email Address</label>
                            <div class="col-md-6">
                                {!! Form::text('email', null, array('id' => 'email', 'class' => 'form-control')) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('default_price') ? ' has-error' : '' }}">
                            <label for="default_price" class="col-md-4 control-label">Default Price List</label>

                            <div class="col-md-6">
                                {!! Form::select('default_price', array('0' => 'Please Select', 'R' => 'Retail', 'W' => 'Wholesale'), null , array('id' => 'default_price', 'class' => 'form-control')) !!}
                                @if ($errors->has('default_price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('default_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <legend style="font-size: large;"><b> Company Address : </b><span style="font-size: medium">Add an address to this company, for use in your Sales Orders, Invoices and Purchase order</span> </legend>

                        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                            <label for="street" class="col-md-4 control-label">Street</label>
                            <div class="col-md-6">
                                {!! Form::text('street', null, array('id' => 'street', 'class' => 'form-control')) !!}
                                @if ($errors->has('street'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Address</label>
                            <div class="col-md-6">
                                {!! Form::text('address', null, array('id' => 'address', 'class' => 'form-control')) !!}
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">City</label>
                            <div class="col-md-6">
                                {!! Form::text('city', null, array('id' => 'city', 'class' => 'form-control')) !!}
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">State</label>
                            <div class="col-md-6">
                                {!! Form::text('state', null, array('id' => 'state', 'class' => 'form-control')) !!}
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <label for="zipcode" class="col-md-4 control-label">Zip Code</label>
                            <div class="col-md-6">
                                {!! Form::text('zipcode', null, array('id' => 'zipcode', 'class' => 'form-control')) !!}
                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label for="country" class="col-md-4 control-label">Country</label>

                            <div class="col-md-6">
                                {!! Form::select('country', $countries , null , array('id' => 'type', 'class' => 'form-control', 'placeholder'=>'Please Select')) !!}
                                @if ($errors->has('country'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="submit" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>



        </div>
    </div>



    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="categories-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Street </th>
                    <th>Phone No</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@stop

@push('scripts')

<script>
    $(function() {
        var table= $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'relationship.data.get',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                { data: 'street', name: 'street' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'status', name: 'status', orderable: false, searchable: false, printable: false},
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#categories-table').on('click', '.btn-delete[data-remote]', function (e) {
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
            data: {method: '_DELETE', submit: true},

            error: function (request, status, error) {
                alert(request.responseText);
            }

        }).always(function (data) {
            $('#categories-table').DataTable().draw(false);
        });
    });

    $('#categories-table').on('click', '.btn-edit', function (e) {
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
                $("#modal-register").modal()
            }else {
                alert('You Do Not Have Permission. Please Contact Administrator')
                return false
            }
        });
    });


</script>

@endpush

