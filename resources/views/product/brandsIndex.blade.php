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
            <p><strong>ADD EDIT UPDATE DELETE BRANDS/MANUFACTURERS</strong></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {{--<div class="col-lg-10 margin-tb">--}}
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success"><i class="glyphicon glyphicon-plus"></i>New Brand</button>
            </div>
        </div>
    </div>

    @include('partials.flashmessage')

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-brands" role="dialog" data-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Brands Form</h3>
                </div>
                <div class="modal-body form">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('product.brand.new') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" value="" name="id"/>
                        <div class="form-body">

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Name</label>

                                <div class="col-md-8">
                                    <input name="name" placeholder="Name" class="form-control" type="text" id="name" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('manufacturer') ? ' has-error' : '' }}">
                                <label for="manufacturer" class="col-md-3 control-label">Manufacturer</label>

                                <div class="col-md-8">
                                    <input name="manufacturer" placeholder="manufacturer" class="form-control" type="text" id="manufacturer">
                                    @if ($errors->has('manufacturer'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('manufacturer') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label">Logo</label>
                                <div class="col-md-8">
                                    <input type="file" name="logo" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|images/*">
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
                    {{--{!! Form::close() !!}--}}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-
    <!-- End Bootstrap modal -->



    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="brands-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Manufacturer</th>
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
        var table= $('#brands-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'product.brand.data',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'showimage', name: 'showimage', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'manufacturer', name: 'manufacturer' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#brands-table').on('click', '.btn-delete[data-remote]', function (e) {
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
            $('#brands-table').DataTable().draw(false);
        });
    });

    $('#brands-table').on('click', '.btn-edit', function (e) {
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
                $("#modal-brands").modal()
            }else {
                alert('You Do Not Have Permission. Please Contact Administrator')
                return false
            }
        });
    });


</script>

@endpush
