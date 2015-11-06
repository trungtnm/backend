<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js bg-black"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- text fonts -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300">
    <link rel="stylesheet" href="{{ $assetURL }}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $assetURL }}css/font-awesome.min.css">

    <style type="text/css">
        .login-panel {
            margin-top: 25%;
        }
    </style>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{ $assetURL }}js/html5shiv.js"></script>
    <script type="text/javascript" src="{{ $assetURL }}js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ $assetURL }}js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-primary">
               @yield('content')
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ $assetURL }}js/vendor/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="{{ $assetURL}}js/plugins.js"></script>
<script type="text/javascript" src="{{ $assetURL }}js/bootstrap.min.js"></script>

</body>
</html>
