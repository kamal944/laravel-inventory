<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ __('main.title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css') }}">

    {{-- SweetAlert2 --}}
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- jQuery (required for toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/skin-blue.min.css') }}">

    @yield('top')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>{{ __('main.logo_mini') }}</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>{{ __('main.logo_lg') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">{{ __('main.toggle_nav') }}</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('user.png') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ \Auth::user()->name }}</span>
                        </a>
                        <!-- Language Dropdown Trigger -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            🌐 {{ app()->getLocale() == 'ar' ? __('main.arabic') : __('main.english') }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        </ul>
                    </li>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ asset('user.png') }}" class="img-circle" alt="User Image">
                            <p>
                                {{ \Auth::user()->name }}
                                <small>{{ \Auth::user()->email }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                    {{ __('main.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    </li>
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    @include('layouts.sidebar')

    <div class="content-wrapper">
        <section class="content container-fluid">
            @yield('content')
        </section>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            {{ __('main.footer_right') }}
        </div>
        <strong>{{ __('main.copyright') }}</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">{{ __('main.recent_activity') }}</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">{{ __('main.langdons_birthday') }}</h4>
                                <p>{{ __('main.birthday_date') }}</p>
                            </div>
                        </a>
                    </li>
                </ul>

                <h3 class="control-sidebar-heading">{{ __('main.tasks_progress') }}</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                {{ __('main.custom_template') }}
                                <span class="pull-right-container">
                                    <span class="label label-danger pull-right">70%</span>
                                </span>
                            </h4>
                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">{{ __('main.general_settings') }}</h3>
                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            {{ __('main.report_panel') }}
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                        <p>{{ __('main.report_panel_desc') }}</p>
                    </div>
                </form>
            </div>
        </div>
    </aside>
    <div class="control-sidebar-bg"></div>
</div>

<script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

@yield('bot')
</body>
</html>