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

    <script>
        $(function() {
            $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
            $("#datepicker1").datepicker({ dateFormat: "dd/mm/yy" }).val()
        });
    </script>


    <div class="row">
        <div class="col-sm-1 sidenav"></div>
        <div class="col-lg-10 margin-tb">
            <div class="pull-left">
                <input type="hidden" name="addData" id="addData" value={!! $userPr->add !!}>
                <input type="hidden" name="editData" id="editData" value={!! $userPr->edit !!}>
                <input type="hidden" name="deleteData" id="deleteData" value={!! $userPr->delete !!}>
                <button type="button" class="btn btn-new btn-success">New Project</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-register" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="modal-title" id="modal-register-label">Add New Project</h3>
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/project.data.new') }}">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <div class="form-group{{ $errors->has('projName') ? ' has-error' : '' }}">
                            <label for="projName" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="projName" type="text" class="form-control" name="projName" value="" required autofocus>

                                @if ($errors->has('projName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('projName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('projDesc') ? ' has-error' : '' }}">
                            <label for="projDesc" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                {!! Form::textarea('projDesc',null,['id'=>'projDesc','size' => '34x6','class'=>'field']) !!}
                                @if ($errors->has('projDesc'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('projDesc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('startDate') ? ' has-error' : '' }}">
                            <label for="startDate" class="col-md-4 control-label">Start Date</label>

                            <div class="col-md-6">
                                {!! Form::text('startDate', Carbon\Carbon::now()->format('d/m/Y') , array('id' => 'datepicker', 'class' => 'control-text','readonly' => 'true', 'data-mg-required' => '')) !!}
                                @if ($errors->has('startDate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('startDate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('endDate') ? ' has-error' : '' }}">
                            <label for="endDate" class="col-md-4 control-label">End Date</label>

                            <div class="col-md-6">
                                {!! Form::text('endDate', Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'datepicker1', 'class' => 'control-text','readonly' => 'true', 'data-mg-required' => '')) !!}
                                @if ($errors->has('endDate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('endDate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('projBudget') ? ' has-error' : '' }}">
                            <label for="projBudget" class="col-md-4 control-label">Budget</label>

                            <div class="col-md-6">
                                <input id="projBudget" type="text" class="form-control" name="projBudget" value="" placeholder="0.00" required autofocus>

                                @if ($errors->has('projBudget'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('projBudget') }}</strong>
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
            <table class="table table-bordered table-hover" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Budget</th>
                    {{--<th>Expense</th>--}}
                    <th>Active ?</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


    </div>

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p style="text-align: center" class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
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
        var table= $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'project.index.data',
            columns: [
                { data: 'projCode', name: 'projCode' },
                { data: 'projName', name: 'projName' },
                { data: 'projDesc', name: 'projDesc' },
                { data: 'startDate', name: 'startDate' },
                { data: 'endDate', name: 'endDate' },
                { data: 'projBudget', name: 'projBudget' },
//                { data: 'expense', name: 'expense' },
                { data: 'status', name: 'status',orderable: false, searchable: false, printable: false},
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ]
        });
    });

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
                $("#modal-register").modal()
            }else {
                alert('You Do Not Have Permission. Please Contact Administrator')
                return false
            }
        });
    });


</script>

@endpush
