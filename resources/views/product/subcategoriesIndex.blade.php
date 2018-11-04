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
            <p><strong>ADD EDIT UPDATE DELETE SUB CATEGORIES OF PRODUCT</strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {{--<div class="col-lg-10 margin-tb">--}}
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New Sub Category</button>
            </div>
        </div>
    </div>

    @include('partials.flashmessage')

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog" style="z-index:2000;">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/subcategories.data.new') }}">
                {{ csrf_field() }}
                <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Category</h3>
                </div>



                <div class="modal-body">




                    <div class="form-group">
                        <label class="control-label col-md-4">Category</label>
                        <div class="col-md-6">
                            {!! Form::select('category_id',$categories, null , array('id' => 'category_id', 'class' => 'form-control','placeholder'=>'Select Category')) !!}
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('alias') ? ' has-error' : '' }}">
                        <label for="alias" class="col-md-4 control-label">Alias</label>

                        <div class="col-md-6">

                            {{--{{ Form::text('q', '', ['id' =>  'q', 'placeholder' =>  'Enter name'])}}--}}

                            <input id="alias" type="text" class="form-control" name="alias" placeholder="Enter Alias">

                            @if ($errors->has('alias'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('alias') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="submit" class="btn btn-default pull-left" data-dismiss="modal">Close</button>

                    </div>
            </div>
        </form>
        </div>
    </div>



    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="categories-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Alias</th>
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
    $(function()
    {
        $( "#alias" ).autocomplete({
            source: "subcategories/autocomplete",
            minLength: 2,
            select: function(event, ui) {
                $('#alias').val(ui.item.value);
            }
        });
    });

</script>



<script>
    $(function() {
        var table= $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'subcategory.index.data',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'category.name', name: 'category.name'},
                { data: 'name', name: 'name' },
                { data: 'alias', name: 'alias' },
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
//
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
