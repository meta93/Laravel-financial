@extends('layouts.master')
@section('title')
    <title>Application Users</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" class="init">

        //When a row is clicked the edit and delete button will be enabled.

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


    </script>

    @include('partials.flashmessage')

    <div class="row">
        {{--<div class="col-sm-1 sidenav"></div>--}}
        <div class="col-sm-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Group</th>
                    <th>Acc No</th>
                    <th>Account Name</th>
                    <th>Type Desc</th>
                    <th>Expensed</th>
                    <th>Budget</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>

    {{--<script>--}}
        {{--$(function() {--}}
            {{--setInterval(function(){--}}
                {{--$('.alert').slideUp(500);--}}
            {{--}, 2000);--}}
        {{--});--}}
    {{--</script>--}}

    <script>


        $('#form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.prop('action');
            $.ajax({
                type: "post",
                url: url,
                data: form.serialize(),
                dataType: 'json',
                success: function(json) {
                    alert(json);
                },
                error: function(json) {
                    alert(json);
                },
            });
        });
    </script>

@stop

@push('scripts')

<script>
    $(function() {
        var table= $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'budget.head.data',
            columns: [
                { data: 'ldgrCode', name: 'ldgrCode' },
                { data: 'accNo', name: 'accNo' },
                { data: 'accName', name: 'accName' },
                { data: 'TypeDesc', name: 'TypeDesc' },
                { data: 'currBal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'currBal' },
                { data: 'cyrBbgtr', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'cyrBbgtr' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

    $('#users-table').on('click', '.btn-delete[data-remote]', function (e) {
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
            $('#users-table').DataTable().draw(false);
        });
    });

</script>

@endpush
