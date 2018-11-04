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
            <p><strong>APPROVE REQUISITION</strong></p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            {{--<div class="col-lg-10 margin-tb">--}}
            <div class="pull-left">
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
            </div>
        </div>
    </div>

    @include('partials.flashmessage')

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="requisition-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Req No</th>
                    <th>Req Date</th>
                    <th>Req Type</th>
                    <th>product</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <!-- editCategoryModal -->

    <div class="modal fade" id="modal-details" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h3 class="modal-title">Details Requisition Data</h3>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Please Check Everything is all right before Approve</h3>
                    <table id="modaltable" class="table table-striped modaltable">
                        <thead style="background-color: #8eb4cb">
                        <tr>
                            <th class="tablecell" style="width: 20%">Req No</th>
                            <th class="tablecell" style="width: 60%">Product</th>
                            <th class="tablecell" style="width: 20%">Quantity</th>
                        </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-reject pull-left">Reject</button>
                    {{--<button class="btn btn-primary btn-approve center-block" id="addcheckedfriends1">Reject</button>--}}
                    <button class="btn btn-primary btn-approve pull-right">Approve</button>
                    <!--<button type="button" class="btn btn-success" onclick="add_checked_friends() >Import Contacts</button>-->
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



@stop

@push('scripts')

<script>

    var refNo;

    $(function() {


        var table= $('#requisition-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'requisition.approve.data',
            columns: [
                { data: 'refNo', name: 'refNo' },
                { data: 'reqDate', name: 'reqDate' },
                { data: 'reqType', name: 'reqType', orderable: false, searchable: false },
                { data: 'items', name: 'items' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });

//        $("body").on("click", "#requisition-table tbody tr", function (e) {
        $('#requisition-table').on('click', '.btn-detail', function (e) {

            e.preventDefault();

            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var id = row.data().refNo;

            refNo = id;

            //Ajax Load data from ajax
            $.ajax({
                url : 'requisition/ajax_details/' + id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {

//                    $(".rqdata").html("");
                    $(".rqdata").remove();

                    var trHTML = '';
                    $.each(data, function (i, item) {
                        trHTML += '<tr class="rqdata"><td>' + item.refno + '</td><td>' +  item.item.name + '</td><td>' +  item.quantity + '</td></tr>';
                    });

                    $('#modaltable').append(trHTML);

                    $('#modal-details').modal('show'); // show bootstrap modal when complete loaded

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });

        });

    });

    $('#requisition-table').on('click', '.btn-delete[data-remote]', function (e) {
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
            $('#requisition-table').DataTable().draw(false);
        });
    });

    $('#requisition-table').on('click', '.btn-edit', function (e) {
        e.preventDefault();

        if(document.getElementById('editData').value == 0)
        {
            alert('You Do Not Have Edit Permission. Please Contact Administrator')
            return false
        }
    });

    $(document).ready(function() {
        $(".btn-approve").click(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Ajax Load data from ajax
            $.ajax({
                url : 'requisition/approve/' + refNo,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {

                    $('#modal-details').modal('hide'); // show bootstrap modal when complete loaded
                    $('#requisition-table').DataTable().draw(false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        });


        $(".btn-reject").click(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Ajax Load data from ajax
            $.ajax({
                url : 'requisition/reject/' + refNo,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {

                    $('#modal-details').modal('hide'); // show bootstrap modal when complete loaded
                    $('#requisition-table').DataTable().draw(false);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        });

    });




</script>

@endpush