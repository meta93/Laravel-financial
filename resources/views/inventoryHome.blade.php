@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.inventoryMenu')
@endsection

@section('content')
    @include('partials.flashmessage')
@endsection

