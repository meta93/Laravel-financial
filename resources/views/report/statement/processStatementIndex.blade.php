@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <br/><br/>
    <br/>
    <br/>
    <div class="row">

        @include('partials.flashmessage')

        {!! Form::open(['url'=>'fn.statement.process.post', 'method' => 'POST']) !!}

            <div class="col-md-6 col-md-offset-3">
                {!! Form::submit('Process Statement Data',['id' => 'submit', 'class'=>'btn btn-primary form-control']) !!}
            </div>

        {!! Form::close() !!}
    </div>


@stop


