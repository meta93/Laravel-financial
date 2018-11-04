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
    {{--<div class="flash-message">--}}
        {{--@foreach (['danger', 'warning', 'success', 'info'] as $msg)--}}
            {{--@if(Session::has('alert-' . $msg))--}}
                {{--<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>--}}
            {{--@endif--}}
        {{--@endforeach--}}
    {{--</div> <!-- end .flash-message -->--}}



    {{--<script>--}}
        {{--$(function() {--}}
            {{--setInterval(function(){--}}
                {{--$('.alert').slideUp(500);--}}
            {{--}, 2000);--}}
        {{--});--}}
    {{--</script>--}}
@endsection

