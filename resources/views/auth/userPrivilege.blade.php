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

    <div class="col-md-10 col-md-offset-1 text-center">
        <div class="well text-center">
            <p>APPROVE USER FOR SPECIFIC PRIVILEGES</p>
        </div>
    </div>
    <br>

    <div class="row col-md-10 col-md-offset-1">
        {{--<div class="well-sm text-center col-md-10 col-md-offset-1">--}}
            {{--<h4>APPROVE USER FOR SPECIFIC PRIVILEGES</h4>--}}
        {{--</div>--}}




        {!! Form::open(['url'=>'userPrivilegeIndex',  'method' => 'GET']) !!}
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <label for="email" class="col-md-2 control-label pull-left">Select User</label>

            <div class="col-md-4">
                {!! Form::select('email', $emailList, null , array('id' => 'email', 'class' => 'form-control')) !!}
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="form-group col-md-2">
                {!! Form::submit('Submit',['class'=>'btn btn-primary button-control']) !!}
            </div>

        </div>
        {!! Form::close() !!}




        {{--<div class="form-group col-md-10 col-md-offset-1">--}}
            {{--{!! Form::open(['url'=>'userPrivilegeIndex', 'method' => 'GET']) !!}--}}
            {{--<div class="col-sm-8">--}}
                {{--{!! Form::label('UserList #', 'Select User', array('class' => 'col-md-2 control-label text-center')) !!}--}}
                {{--{!! Form::select('email',$emailList,null, array('id' => 'email', 'class' => 'col-md-4 form-control')) !!}--}}
                {{--{!! Form::submit('Submit',['class'=>'col-md-1 btn btn-primary button-control']) !!}--}}
            {{--</div>--}}
            {{--{!! Form::close() !!}--}}
        {{--</div>--}}
    </div>

    @if(!empty($userPrivilege))
        <div class="row">
            <div class="col-sm-10 col-md-offset-1 panel panel-default padding-left">
                <div class="panel-heading"><strong>Check Privilege to Approve For User {!! $userEmail !!}</strong> </div>

                {!! Form::open(['url'=>'approvePrivilege']) !!}

                <table class="table table-bordered table-hover padding" >

                    <colgroup>
                        {{--<col style="width: 40px"/>--}}
                        <col style="width: 50px"/>
                        <col style="width: 100px"/>
                        <col style="width: 50px"/>
                        <col style="width: 50px"/>
                        <col style="width: 50px"/>
                        <col style="width: 50px"/>
                    </colgroup>
                    <thead style="background-color: #66afe9">
                    <th>ID</th>
                    <th>FORM</th>
                    <th>VIEW</th>
                    <th>ADD</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                    </thead>
                    <tbody>
                    @foreach ($userPrivilege as $i => $userPrivileges)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#a9cce3' }};">
                            {!! Form::hidden('email', $userPrivileges->email, array('id' => 'email')) !!}
                            <td>{{ $userPrivileges->useCaseId }}</td>
                            <td>{!! $userPrivileges->useCaseName !!}</td>
                            <td>{!! Form::checkbox('view[]',$userPrivileges->useCaseId, $userPrivileges->view) !!}</td>
                            <td>{!! Form::checkbox('add[]',$userPrivileges->useCaseId, $userPrivileges->add) !!}</td>
                            <td>{!! Form::checkbox('edit[]',$userPrivileges->useCaseId, $userPrivileges->edit) !!}</td>
                            <td>{!! Form::checkbox('delete[]',$userPrivileges->useCaseId, $userPrivileges->delete) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <br>

                <div class="form-group">
                    {!! Form::submit('SUBMIT',['class'=>'btn btn-primary form-control']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    @endif

    @include('partials.flashmessage');

@endsection