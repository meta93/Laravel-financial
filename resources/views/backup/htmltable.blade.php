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
   
    @include('partials.flashmessage')

    <div class="container">
        <p style="line-height: 18px; font-size: 18px; font-family: times;">
            Input a number to generate math table.<br>
            <br>
            Muhammad Bin Naeem JavaScript:<br><br>
            <input type="number" id="number" placeholder="Input a Number" onKeyup="createTable(this)"/>
        <div id="Result"></div>

        <script>
            function createTable(element){
                var number = element.value;
                var html = "";
                for(i=1;i<=10;i++){
                    html += number+" * "+i+" = "+(number*i)+"</br>";
                }
                document.getElementById('Result').innerHTML = html;
            }
        </script>
        </p>
    </div>

@stop

@push('scripts')

<script>
    


</script>

@endpush
