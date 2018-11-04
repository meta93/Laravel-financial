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

    <div class="row">
        <div class="col-sm-1 sidenav"></div>
        <div class="col-sm-10">
            <table class="table table-bordered table-hover" id="fiscal-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>FP No</th>
                    <th>FP Year</th>
                    <th>Month</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Depriciation</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->


    <script>
        $(function() {
            setInterval(function(){
                $('.alert').slideUp(500);
            }, 2000);
        });
    </script>

@stop

@push('scripts')

<script>
    $(function() {
        var table= $('#fiscal-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'fiscal.period.data',
            columns: [
                { data: 'fpNo', name: 'fpNo' },
                { data: 'FiscalYear', name: 'FiscalYear' },
                { data: 'monthName', name: 'monthName' },
                { data: 'startDate', name: 'startDate' },
                { data: 'endDate', name: 'endDate'},
                { data: 'depriciation', name: 'depriciation', orderable: false, searchable: false, printable: false}
            ]
        });
    });

</script>

@endpush
