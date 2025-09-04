<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('user.png') }} " class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ \Auth::user()->name  }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ __('main.online_status') }}</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ __('main.search_placeholder') }}">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{ __('main.menu_header') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="{{ url('/home') }}"><i class="fa fa-link"></i> <span>{{ __('main.dashboard') }}</span></a></li>
            <li class="active"><a href="{{ route('categories.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.category') }}</span></a></li>
            <li class="active"><a href="{{ route('products.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.product') }}</span></a></li>
            <li class="active"><a href="{{ route('customers.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.customer') }}</span></a></li>
            <li class="active"><a href="{{ route('sales.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.sales') }}</span></a></li>
            <li class="active"><a href="{{ route('suppliers.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.supplier') }}</span></a></li>
            <li class="active"><a href="{{ route('productsOut.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.product_out') }}</span></a></li>
            <li class="active"><a href="{{ route('productsIn.index') }}"><i class="fa fa-link"></i> <span>{{ __('main.product_in') }}</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>