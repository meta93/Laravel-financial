<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventory Management System</title>

    <link href="/css/app.css" rel="stylesheet">
    <link href="/src/css/fixed.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link href="../src/DataTables-1.10.12/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />


    <!-- jQuery -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/libs/jquery/jquery.js"></script>
    {{--<script type="text/javascript" src="../src/jquery-ui-1.12.1/external/jquery/jquery.js"></script>--}}
    <script type="text/javascript" src="../src/jquery-ui-1.12.1/jquery-ui.js"></script>

    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/jquery.smartmenus.js"></script>
    <script type="text/javascript" src="../src/bootstrap-3.3.7/js/bootstrap.min.js"></script>

    <!-- SmartMenus jQuery init -->
    <script type="text/javascript">
        $(function() {
            $('#main-menu').smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });
        });
    </script>

    <link href="../src/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
    <!-- SmartMenus core CSS (required) -->
    <link href="../src/smartmenus-1.0.1/css/sm-core-css.css" rel="stylesheet" type="text/css" />

    <!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
    <link href="../src/smartmenus-1.0.1/css/sm-blue/sm-blue.css" rel="stylesheet" type="text/css" />
    <link href="../src/smartmenus-1.0.1/css/sm-mint/sm-mint.css" rel="stylesheet" type="text/css" />
    <link href="../src/smartmenus-1.0.1/css/sm-simple/sm-simple.css" rel="stylesheet" type="text/css" />
    <link href="../src/smartmenus-1.0.1/css/sm-clean/sm-clean.css" rel="stylesheet" type="text/css" />
    <link href="../src/smartmenus-1.0.1/addons/bootstrap/jquery.smartmenus.bootstrap.css" rel="stylesheet">

    <style type="text/css">
        @media (min-width: 768px) {
            #main-menu > li {
                float: none;
                display: table-cell;
                width: 1%;
                text-align: center;
            }
        }
    </style>


    @yield('css')

    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}




</head>
<body>



<div class="container">

    <div class="row">
        <div class="col-md-12">
            @yield('banner')
        </div>
    </div>

    <div class="row">
        @yield('menu')
    </div>

    <div class="row fullpage">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>

</div>
<div class="container-fluid">
    <footer>
        @include('layouts.backend.footer')
    </footer>
</div>

<script type="text/javascript" src="../src/DataTables-1.10.12/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../src/DataTables-1.10.12/media/js/dataTables.jqueryui.min.js"></script>
<script type="text/javascript" src="../src/Buttons-1.2.2/js/dataTables.buttons.min.js"></script>
<script src="../vendor/datatables/buttons.server-side.js"></script>
<script src="https://datatables.yajrabox.com/js/handlebars.js"></script>


@stack('scripts')

</body>
</html>