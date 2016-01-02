<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $title or "Trungtnm Backend" }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
       <!-- text fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300">
        <link rel="stylesheet" href="{{ $assetURL }}css/bootstrap.min.css">
        <link rel="stylesheet" href="{{  $assetURL }}css/font-awesome.min.css">

        <!-- ace styles -->
        <link rel="stylesheet" href="{{ $assetURL }}css/font-style.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/ace-rtl.min.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/ace-skins.min.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/ace.min.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/jquery-ui.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/jquery.fancybox.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/define.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/chosen.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/bootstrap-tagsinput.css">
        <link rel="stylesheet" href="{{ $assetURL }}css/bootstrap-multiselect.css">
        <link rel="stylesheet" href="{{ asset('bower/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script type="text/javascript" src="{{ $assetURL }}js/html5shiv.js" ></script>
        <script type="text/javascript" src="{{ $assetURL }}js/respond.min.js" ></script>
        <![endif]-->
        <script type="text/javascript">
            var frontendRoot = "{{ url() }}/";
            var root = "{{ url(config('trungtnm.backend.uri')) }}/";
            var assetURL = "{{ $assetURL }}";
            var module = "{{Request::segment(2) }}";

        </script>
        
        <script type="text/javascript" src="{{ $assetURL }}js/vendor/modernizr-2.6.2.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/vendor/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/plugins.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}ckfinder/ckfinder.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/multiselect/bootstrap-multiselect.js"></script>
    </head>
    <body class="no-skin">
        @include('TrungtnmBackend::includes.navbar')

        <div class="main-container">
            @include('TrungtnmBackend::includes.menu')

            <div class="main-content ">
                <div class="page-content ">
                    @if (Session::has('message'))
                        <div class="alert {{Session::get('status') ? 'alert-success' : 'alert-danger'}} ">{{ Session::get('message') }}</div>
                    @endif
                    @if (!empty($message))
                        <div class="alert {{ !empty($status) ? 'alert-success' : 'alert-danger'}}">{!! $message !!}</div>
                    @endif
                    @yield('content')
                    @section('script')
                        {{-- this section is for inpage script --}}
                    @show
                    <div class="clearfix"></div>
                </div>

                <div class="footer">
                    @include('TrungtnmBackend::includes.footer')
                </div>
                <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse display">
                    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
                 </a>
            </div>
        </div>         


        
        <script type="text/javascript" src="{{ $assetURL }}js/ace/ace-extra.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/ace/ace-elements.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/ace/ace.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/chosen.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/admin.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/main.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/bootstrap-checkbox.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/bootstrap3-typeahead.min.js"></script>
        <script type="text/javascript" src="{{ $assetURL }}js/bootstrap-tagsinput.js"></script>
        <script type="text/javascript" src="{{ asset('bower/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js') }}"></script>
    </body>
</html>
