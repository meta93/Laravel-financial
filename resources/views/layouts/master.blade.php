<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Application Users</title>

    @yield('title')

    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/table.css" rel="stylesheet">
    <link href="../src/css/main.css" rel="stylesheet" type="text/css" />
    <link href="../src/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="../src/select2/select2.min.css" rel="stylesheet" type="text/css" />

    {{--<link href="../src/css/bootstrap.min.css" rel="stylesheet" type="text/css" />--}}

    <link href="../src/DataTables-1.10.12/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../src/css/font-awesome.min.css" type="text/css" />

    <!-- jQuery -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/libs/jquery/jquery.js"></script>
    {{--<script type="text/javascript" src="../src/jquery-ui-1.12.1/external/jquery/jquery.js"></script>--}}
    <script type="text/javascript" src="../src/jquery-ui-1.12.1/jquery-ui.js"></script>


    <script type="text/javascript" src="../src/select2/select2.min.js"></script>

    <!-- SmartMenus jQuery plugin -->
    <script type="text/javascript" src="../src/smartmenus-1.0.1/jquery.smartmenus.js"></script>
    <script type="text/javascript" src="../src/bootstrap-3.3.7/js/bootstrap.min.js"></script>


    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>`--}}


    <!-- SmartMenus jQuery init -->
    <script type="text/javascript">
        $(function() {
            $('#main-menu').smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });
        });
    </script>

    <script>
        var dateToday = new Date('2015-01-01');
        $(function() {
            $( "#transdatepicker" ).datepicker({
                numberOfMonths: 1,
                showButtonPanel: true,
                minDate: dateToday,
                maxDate: new Date('2015-12-31'),
                dateFormat: "dd/mm/yy"
            });
        });


    </script>




    <link href="../src/jquery-ui-1.12.1/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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

</head>

<body>

<div class="container-full">

    <div class="row">
        <div class="col-md-12">
            @yield('banner')
        </div>
    </div>

    <div class="row">
        @yield('header')
    </div>

    <div class="row">
        @yield('menu')
    </div>

    <div class="row fullpage">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>

    <div class="row" style="background-color: #3092c0; height: 50px; padding-top: 10px; text-align: center">
        <div class="col-md-12">
            @include('layouts.footer')
        </div>
    </div>

</div>

<script type="text/javascript" src="../src/DataTables-1.10.12/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../src/DataTables-1.10.12/media/js/dataTables.jqueryui.min.js"></script>
<script type="text/javascript" src="../src/Buttons-1.2.2/js/dataTables.buttons.min.js"></script>
<script src="../vendor/datatables/buttons.server-side.js"></script>
<script src="https://datatables.yajrabox.com/js/handlebars.js"></script>


@stack('scripts')



</body>
</html>