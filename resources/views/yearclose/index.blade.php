@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
   
    @include('partials.flashmessage')

@stop

@push('scripts')

<script>
    


</script>

@endpush
