@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
{{--@section('header')--}}
{{--@include('layouts.header')--}}
{{--@endsection--}}
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    @include('partials.flashmessage')

    <div style="height: 25px"></div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="voucher-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Date</th>
                    <th>VoucherNo</th>
                    <th>Created By</th>
                    <th>Post Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <script id="details-template" type="text/x-handlebars-template">
        <div class="modal fade" id="edit-modal" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal">close</button>--}}
                    <h4 class="modal-title">Cheque & Post Voucher</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" action="voucher.data.post/@{{voucherNo}}" method="POST" >
                        {{ csrf_field() }}

                                <div class="label label-info">Voucher No @{{voucherNo}}</div>
                                <table class="table details-table table-hover" id="posts-@{{voucherNo}}">
                                    <thead>
                                    <tr>
                                        <th>Head</th>
                                        <th>Description</th>
                                        <th>Dabit</th>
                                        <th>Credit</th>
                                    </tr>
                                    </thead>
                                </table>
                        <button type="submit" class="btn btn-primary" id="update-data">Post Voucher</button>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    </script>


@endsection
@push('scripts')

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(function() {
        var template = Handlebars.compile($("#details-template").html());
        var table= $('#voucher-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'unposted.voucher.data',
            columns: [
                { data: 'transDate', name: 'transDate' },
                { data: 'voucherNo', name: 'voucherNo' },
                { data: 'userCreated', name: 'userCreated' },
                { data: 'status', name: 'status', orderable: false, searchable: false, printable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });


        $('#voucher-table').on('click', '.btn-primary[data-target]', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'posts-' + row.data().voucherNo;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row


                row.child(template(row.data())).show();
                initTable(tableId, row.data());
                tr.addClass('shown');
                tr.next().find('td').addClass('no-padding bg-gray');
            }

        });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                ajax: data.details_url,
                columns: [

                    { data: 'dr_acc.accName', name: 'dr_acc.accName' },
                    { data: 'transDesc1', name: 'transDesc1' },
                    { data: 'dr_amt', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2, '৳ ' ), name: 'dr_amt' },
                    { data: 'cr_amt',className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2, '৳ ' ), name: 'cr_amt' }
                ]
            })

            $("#edit-modal").modal();
        }
    });


    $('#voucher-table').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
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
            $('#voucher-table').DataTable().draw(false);
        });
    });

    $(document).on('click', '.btn-danger', function (e) {

        $('#voucher-table').DataTable().draw(false);

    });

</script>

@endpush

