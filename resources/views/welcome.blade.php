@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
        @include('layouts.menu')
@endsection

@section('scripts')
    <!-- jQuery -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/libs/jquery/jquery.js"></script>

    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/jquery.smartmenus.js"></script>

    <!-- SmartMenus jQuery init -->
    <script type="text/javascript">
        $(function() {
            $('#main-menu').smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });
        });
    </script>


    <!-- SmartMenus core CSS (required) -->
    <link href="../src/smartmenus-1.0.1/css/sm-core-css.css" rel="stylesheet" type="text/css" />

    <!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
    <link href="../src/smartmenus-1.0.1/css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />

    <!--End Smart Manu-->
@endsection