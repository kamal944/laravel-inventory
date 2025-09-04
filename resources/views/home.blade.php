@extends('layouts.master')

@section('title', __('main.title'))

@section('top')
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ \App\User::count() }}</h3>
                    <p>{{ __('main.users') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ \App\Category::count() }}</h3>
                    <p>{{ __('main.categories') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('categories.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ \App\Product::count() }}</h3>
                    <p>{{ __('main.products') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('products.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ \App\Customer::count() }}</h3>
                    <p>{{ __('main.customers') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('customers.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ \App\Sale::count() }}</h3>
                    <p>{{ __('main.sales') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('sales.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ \App\Supplier::count() }}</h3>
                    <p>{{ __('main.suppliers') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('suppliers.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ \App\Product_Masuk::count() }}</h3>
                    <p>{{ __('main.products_in') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('productsIn.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-gray">
                <div class="inner">
                    <h3>{{ \App\Product_Keluar::count() }}</h3>
                    <p>{{ __('main.products_out') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('productsOut.index') }}" class="small-box-footer">{{ __('main.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="box">
            <div class="box-body">
                <div class="callout callout-success">
                    <h4>{{ __('main.success') }}</h4>
                    <p>{{ session('status') }} {{ __('main.welcome_message') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('top')
@endsection