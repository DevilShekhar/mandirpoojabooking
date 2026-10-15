@extends('admin.layouts.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        186
                                    </h3>
                                    <span class="text-muted">
                                        My Bookings
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-orange">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        32
                                    </h3>
                                    <span class="text-muted">Pending Bookings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-green">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        78
                                    </h3>
                                    <span class="text-muted">Confirmed Bookings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-cyan">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        24
                                    </h3>
                                    <span class="text-muted">In Progress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-purple">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        95
                                    </h3>
                                    <span class="text-muted">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="#" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon l-bg-fail">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        11
                                    </h3>
                                    <span class="text-muted">Cancelled Bookings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            {{-- Pooja Packages --}}
            @can('puja-packages.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('puja-packages.index') }}" class="text-decoration-none">
                    <div class="card l-bg-green">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fa fa-award"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Pooja Packages</h4>
                                <span>48</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-purple"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fa fa-box"></i>
                                        Packages
                                    </span>
                                    <span class="text-nowrap">
                                        Total Pooja Packages
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Temples --}}
            @can('temples.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('temples.index') }}" class="text-decoration-none">
                    <div class="card l-bg-cyan">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Temples</h4>
                                <span>12</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-orange"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-tags"></i>
                                        Temples
                                    </span>
                                    <span class="text-nowrap">
                                        Total Temples
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Categories --}}
            @can('puja-categories.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('puja-categories.index') }}" class="text-decoration-none">
                    <div class="card l-bg-purple">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Categories</h4>
                                <span>8</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-cyan"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-layer-group"></i>
                                        Categories
                                    </span>
                                    <span class="text-nowrap">
                                        Total Categories
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Sub Categories --}}
            @can('puja-categories.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('puja-categories.index') }}" class="text-decoration-none">
                    <div class="card l-bg-orange">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Sub Categories</h4>
                                <span>22</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-green"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-sitemap"></i>
                                        Sub Categories
                                    </span>
                                    <span class="text-nowrap">
                                        Total Sub Categories
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Devotees --}}
            @can('booking-devotees.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-devotees.index') }}" class="text-decoration-none">
                    <div class="card" style="background: linear-gradient(135deg, #3949ab, #5c6bc0);">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Devotees</h4>
                                <span>3,842</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar"
                                        style="background: #ffffff;"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-users"></i>
                                        Devotees
                                    </span>
                                    <span class="text-nowrap">
                                        Total Registered Devotees
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
        </div>
        <div class="row">
            {{-- Total Reviews --}}
            @can('reviews.view')
            <div class="col-xl-6 col-lg-6">
                <a href="#" class="text-decoration-none">
                    <div class="card l-bg-yellow">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Total Reviews</h4>
                                <span>264</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-orange"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-star"></i>
                                        Reviews
                                    </span>
                                    <span class="text-nowrap">
                                        Total Reviews
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
            {{-- Approved Reviews --}}
            @can('reviews.view')
            <div class="col-xl-6 col-lg-6">
                <a href="#" class="text-decoration-none">
                    <div class="card l-bg-green">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-content">
                                <h4 class="card-title">Approved Reviews</h4>
                                <span>231</span>
                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-cyan"
                                        role="progressbar"
                                        data-width="25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2">
                                        <i class="fas fa-check-circle"></i>
                                        Approved
                                    </span>
                                    <span class="text-nowrap">
                                        Total Approved Reviews
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
        </div>
        {{-- Booking Status wise cards --}}
        @can('puja-bookings.view')
        <div class="mb-3">
            <div class="d-inline-flex align-items-center px-3 py-2 rounded"
                style="background: linear-gradient(135deg, #3949ab, #5c6bc0); color: #fff;">
                <i class="fas fa-shopping-bag mr-2"></i>
                <h5 class="mb-0 font-weight-bold">
                    Latest Temple Bookings
                </h5>
            </div>
        </div>

        <div class="row">

            {{-- Pending --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Pending</h6>
                                    <span class="font-weight-bold mb-0">
                                        32
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-orange text-white">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 10%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Confirmed --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Confirmed</h6>
                                    <span class="font-weight-bold mb-0">
                                        78
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-cyan text-white">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 7.8%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- In Progress --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">In Progress</h6>
                                    <span class="font-weight-bold mb-0">
                                        24
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-green text-white">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 15%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Scheduled --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Scheduled</h6>
                                    <span class="font-weight-bold mb-0">
                                        41
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-purple text-white">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 5.4%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Completed --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Completed</h6>
                                    <span class="font-weight-bold mb-0">
                                        95
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-teal text-white">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 12%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Cancelled --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Cancelled</h6>
                                    <span class="font-weight-bold mb-0">
                                        11
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-pink text-white">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 3.2%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Failed --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Failed</h6>
                                    <span class="font-weight-bold mb-0">
                                        4
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-fail text-white">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 2.5%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            {{-- Refunded --}}
            @can('puja-bookings.view')
            <div class="col-xl-3 col-lg-6">
                <a href="{{ route('booking-puja-order.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body card-type-3">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-0">Refunded</h6>
                                    <span class="font-weight-bold mb-0">
                                        6
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="card-circle l-bg-red text-white">
                                        <i class="fas fa-undo-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2">
                                    <i class="fa fa-arrow-up"></i> 4.6%
                                </span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

        </div>
        @endcan
        @can('puja-packages.view')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Most Booked Pooja Packages</h4>

                @can('puja-packages.view')
                    <a href="{{ route('puja-packages.index') }}" class="btn btn-primary">
                        View All
                    </a>
                @endcan
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Package</th>
                                    <th>Code</th>
                                    <th>Temple</th>
                                    <th>Price</th>
                                    <th>Total Booked</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <img src="{{ asset('assets/img/default.png') }}"
                                            alt="Package"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit:cover;">
                                    </td>
                                    <td>Rudrabhishek Pooja</td>
                                    <td>RUD-001</td>
                                    <td>Mahakaleshwar Temple</td>
                                    <td>₹2,100.00</td>
                                    <td><strong>248</strong></td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>
                                        <img src="{{ asset('assets/img/default.png') }}"
                                            alt="Package"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit:cover;">
                                    </td>
                                    <td>Special Darshan</td>
                                    <td>DAR-002</td>
                                    <td>Kashi Vishwanath</td>
                                    <td>₹501.00</td>
                                    <td><strong>412</strong></td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>
                                        <img src="{{ asset('assets/img/default.png') }}"
                                            alt="Package"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit:cover;">
                                    </td>
                                    <td>Laghu Rudra</td>
                                    <td>LRU-003</td>
                                    <td>Trimbakeshwar</td>
                                    <td>₹5,100.00</td>
                                    <td><strong>96</strong></td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>
                                        <img src="{{ asset('assets/img/default.png') }}"
                                            alt="Package"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit:cover;">
                                    </td>
                                    <td>Satyanarayan Katha</td>
                                    <td>SAT-004</td>
                                    <td>Siddhivinayak Temple</td>
                                    <td>₹1,501.00</td>
                                    <td><strong>187</strong></td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>
                                        <img src="{{ asset('assets/img/default.png') }}"
                                            alt="Package"
                                            width="45"
                                            height="45"
                                            class="rounded"
                                            style="object-fit:cover;">
                                    </td>
                                    <td>Maha Mrityunjaya Jaap</td>
                                    <td>MMJ-005</td>
                                    <td>Somnath Temple</td>
                                    <td>₹3,100.00</td>
                                    <td><strong>134</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endcan
        <div class="row">
            @can('puja-bookings.index')
                <div class="col-xl-6 col-lg-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card l-bg-orange">
                            <div class="card-statistic-3">
                                <div class="card-icon card-icon-large">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Active Offers</h4>
                                    <span>9</span>
                                    <div class="progress mt-1 mb-1" data-height="8">
                                        <div class="progress-bar l-bg-purple"
                                            role="progressbar"
                                            data-width="25%"
                                            aria-valuenow="25"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="mb-0 text-sm">
                                        <span class="mr-2">
                                            <i class="fas fa-tag"></i>
                                            Offers
                                        </span>
                                        <span class="text-nowrap">
                                            Total Active Offers
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('puja-bookings.index')
                <div class="col-xl-6 col-lg-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card l-bg-red">
                            <div class="card-statistic-3">
                                <div class="card-icon card-icon-large">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">Expired Offers</h4>
                                    <span>5</span>
                                    <div class="progress mt-1 mb-1" data-height="8">
                                        <div class="progress-bar l-bg-orange"
                                            role="progressbar"
                                            data-width="25%"
                                            aria-valuenow="25"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="mb-0 text-sm">
                                        <span class="mr-2">
                                            <i class="fas fa-calendar-times"></i>
                                            Offers
                                        </span>
                                        <span class="text-nowrap">
                                            Total Expired Offers
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endcan
        </div>

        <div class="row">
            @can('puja-bookings.view')
                <div class="col-12 col-sm-12 col-lg-4">
                    <div class="card dashboard-chart-card">
                        <div class="card-header">
                            <h4>Booking Status</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart4" class="dashboard-chart"></div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('puja-bookings.view')
                <div class="col-12 col-sm-12 col-lg-4">
                    <div class="card dashboard-chart-card">
                        <div class="card-header">
                            <h4>Monthly Bookings</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart3" class="dashboard-chart"></div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('puja-bookings.view')
                <div class="col-12 col-sm-12 col-lg-4">
                    <div class="card dashboard-chart-card">
                        <div class="card-header">
                            <h4>Monthly Revenue</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart2" class="dashboard-chart"></div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        @can('puja-bookings.index')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Latest Temple Bookings</h4>

                            @can('puja-bookings.view')
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-primary">
                                        View All
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Booking Number</th>
                                            <th>Devotee</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Date</th>

                                            @can('puja-bookings.view')
                                                <th>Action</th>
                                            @endcan
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><strong>BK-20240901</strong></td>
                                            <td>
                                                <div>
                                                    <strong>Rajesh Sharma</strong>
                                                    <br>
                                                    <small class="text-muted">rajesh@example.com</small>
                                                </div>
                                            </td>
                                            <td><strong>₹2,100.00</strong></td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                            <td>
                                                15 Sep 2024
                                                <br>
                                                <small class="text-muted">10:30 AM</small>
                                            </td>

                                            @can('puja-bookings.view')
                                                <td>
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td><strong>BK-20240902</strong></td>
                                            <td>
                                                <div>
                                                    <strong>Priya Mehta</strong>
                                                    <br>
                                                    <small class="text-muted">priya@example.com</small>
                                                </div>
                                            </td>
                                            <td><strong>₹501.00</strong></td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                            <td>
                                                16 Sep 2024
                                                <br>
                                                <small class="text-muted">02:15 PM</small>
                                            </td>

                                            @can('puja-bookings.view')
                                                <td>
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td><strong>BK-20240903</strong></td>
                                            <td>
                                                <div>
                                                    <strong>Amit Patel</strong>
                                                    <br>
                                                    <small class="text-muted">amit@example.com</small>
                                                </div>
                                            </td>
                                            <td><strong>₹5,100.00</strong></td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><span class="badge badge-primary">Scheduled</span></td>
                                            <td>
                                                17 Sep 2024
                                                <br>
                                                <small class="text-muted">11:45 AM</small>
                                            </td>

                                            @can('puja-bookings.view')
                                                <td>
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>

                                        <tr>
                                            <td>4</td>
                                            <td><strong>BK-20240904</strong></td>
                                            <td>
                                                <div>
                                                    <strong>Sneha Reddy</strong>
                                                    <br>
                                                    <small class="text-muted">sneha@example.com</small>
                                                </div>
                                            </td>
                                            <td><strong>₹1,501.00</strong></td>
                                            <td><span class="badge badge-success">Paid</span></td>
                                            <td><span class="badge badge-info">Confirmed</span></td>
                                            <td>
                                                18 Sep 2024
                                                <br>
                                                <small class="text-muted">09:20 AM</small>
                                            </td>

                                            @can('puja-bookings.view')
                                                <td>
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>

                                        <tr>
                                            <td>5</td>
                                            <td><strong>BK-20240905</strong></td>
                                            <td>
                                                <div>
                                                    <strong>Vikram Singh</strong>
                                                    <br>
                                                    <small class="text-muted">vikram@example.com</small>
                                                </div>
                                            </td>
                                            <td><strong>₹3,100.00</strong></td>
                                            <td><span class="badge badge-danger">Failed</span></td>
                                            <td><span class="badge badge-danger">Cancelled</span></td>
                                            <td>
                                                19 Sep 2024
                                                <br>
                                                <small class="text-muted">04:50 PM</small>
                                            </td>

                                            @can('puja-bookings.view')
                                                <td>
                                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        View
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('puja-bookings.index')
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pending Temple Reviews</h4>

                            @can('puja-bookings.view')
                                <div class="card-header-action">
                                    <a href="#" class="btn btn-primary">
                                        View All
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Package</th>
                                            <th>Devotee</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="assets/img/default.png"
                                                        alt="Package"
                                                        width="45"
                                                        height="45"
                                                        class="rounded mr-2"
                                                        style="object-fit: cover;">
                                                    <span>Rudrabhishek Pooja</span>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Rajesh Sharma</strong>
                                                <small class="d-block text-muted">
                                                    rajesh@example.com
                                                </small>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <span title="Very peaceful experience. The priests performed the pooja with full devotion.">
                                                    Very peaceful experience. The priests performed the pooja with full devotion.
                                                </span>
                                            </td>
                                            <td>
                                                <small>12-09-2024</small>
                                                <span class="badge badge-warning d-block mt-1">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="assets/img/default.png"
                                                        alt="Package"
                                                        width="45"
                                                        height="45"
                                                        class="rounded mr-2"
                                                        style="object-fit: cover;">
                                                    <span>Special Darshan</span>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Priya Mehta</strong>
                                                <small class="d-block text-muted">
                                                    priya@example.com
                                                </small>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <span title="Smooth booking process and timely darshan. Highly recommended.">
                                                    Smooth booking process and timely darshan. Highly recommended.
                                                </span>
                                            </td>
                                            <td>
                                                <small>14-09-2024</small>
                                                <span class="badge badge-warning d-block mt-1">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="assets/img/default.png"
                                                        alt="Package"
                                                        width="45"
                                                        height="45"
                                                        class="rounded mr-2"
                                                        style="object-fit: cover;">
                                                    <span>Laghu Rudra</span>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Amit Patel</strong>
                                                <small class="d-block text-muted">
                                                    amit@example.com
                                                </small>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <span title="Good arrangement but waiting time was a bit long.">
                                                    Good arrangement but waiting time was a bit long.
                                                </span>
                                            </td>
                                            <td>
                                                <small>16-09-2024</small>
                                                <span class="badge badge-warning d-block mt-1">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @can('puja-bookings.view')
                            <a href="#" class="card-footer card-link text-center small">
                                View All Reviews
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @endcan
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded.');
        return;
    }

    // Dummy data for Mandir Booking System
    var monthlyOrders = [
        { month: 1, total: 85 },
        { month: 2, total: 92 },
        { month: 3, total: 110 },
        { month: 4, total: 98 },
        { month: 5, total: 125 },
        { month: 6, total: 140 },
        { month: 7, total: 165 },
        { month: 8, total: 152 },
        { month: 9, total: 180 },
        { month: 10, total: 170 },
        { month: 11, total: 195 },
        { month: 12, total: 220 }
    ];

    var monthlySales = [
        { month: 1, total: 185000 },
        { month: 2, total: 210000 },
        { month: 3, total: 245000 },
        { month: 4, total: 220000 },
        { month: 5, total: 280000 },
        { month: 6, total: 310000 },
        { month: 7, total: 355000 },
        { month: 8, total: 330000 },
        { month: 9, total: 390000 },
        { month: 10, total: 375000 },
        { month: 11, total: 420000 },
        { month: 12, total: 480000 }
    ];

    var orderStatus = [
        { status: 0, total: 32 },
        { status: 1, total: 78 },
        { status: 2, total: 24 },
        { status: 3, total: 41 },
        { status: 4, total: 95 },
        { status: 5, total: 11 },
        { status: 6, total: 4 },
        { status: 7, total: 6 }
    ];

    var monthNames = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    var statusNames = {
        0: 'Pending',
        1: 'Confirmed',
        2: 'In Progress',
        3: 'Scheduled',
        4: 'Completed',
        5: 'Cancelled',
        6: 'Failed',
        7: 'Refunded'
    };

    var statusOrder = [
        'Pending',
        'Confirmed',
        'In Progress',
        'Scheduled',
        'Completed',
        'Cancelled',
        'Failed',
        'Refunded'
    ];

    var normalizedStatuses = {};

    orderStatus.forEach(function (item) {
        var statusValue = item.status;
        var statusName = null;

        if (statusValue !== null && statusValue !== undefined) {
            if (statusNames[statusValue] !== undefined) {
                statusName = statusNames[statusValue];
            } else {
                var textStatus = String(statusValue).trim().toLowerCase();

                var textStatusMap = {
                    'pending': 'Pending',
                    'confirm': 'Confirmed',
                    'confirmed': 'Confirmed',
                    'processing': 'In Progress',
                    'in progress': 'In Progress',
                    'shipped': 'Scheduled',
                    'scheduled': 'Scheduled',
                    'delivered': 'Completed',
                    'completed': 'Completed',
                    'cancel': 'Cancelled',
                    'cancelled': 'Cancelled',
                    'failed': 'Failed',
                    'refunded': 'Refunded',
                    'refund': 'Refunded'
                };

                if (textStatusMap[textStatus] !== undefined) {
                    statusName = textStatusMap[textStatus];
                }
            }
        }

        if (statusName) {
            if (!normalizedStatuses[statusName]) {
                normalizedStatuses[statusName] = 0;
            }

            normalizedStatuses[statusName] += parseInt(item.total) || 0;
        }
    });

    var statusLabels = [];
    var statusTotals = [];

    statusOrder.forEach(function (statusName) {
        if (normalizedStatuses[statusName] > 0) {
            statusLabels.push(statusName);
            statusTotals.push(normalizedStatuses[statusName]);
        }
    });

    var totalOrders = statusTotals.reduce(function (total, value) {
        return total + value;
    }, 0);

    var orderMonths = monthlyOrders.map(function (item) {
        return monthNames[parseInt(item.month) - 1];
    });

    var orderTotals = monthlyOrders.map(function (item) {
        return parseInt(item.total) || 0;
    });

    var salesMonths = monthlySales.map(function (item) {
        return monthNames[parseInt(item.month) - 1];
    });

    var salesTotals = monthlySales.map(function (item) {
        return parseFloat(item.total) || 0;
    });

    var chart4 = document.querySelector('#chart4');

    if (chart4 && statusTotals.length > 0) {
        new ApexCharts(chart4, {
            chart: {
                type: 'donut',
                toolbar: {
                    show: false
                }
            },
            series: statusTotals,
            labels: statusLabels,
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px',
                fontWeight: 500,
                markers: {
                    width: 9,
                    height: 9,
                    radius: 50
                },
                itemMargin: {
                    horizontal: 7,
                    vertical: 5
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: true,
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontWeight: 500
                            },
                            value: {
                                show: true,
                                fontSize: '22px',
                                fontWeight: 600,
                                offsetY: 5,
                                formatter: function (value) {
                                    return value;
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total Bookings',
                                fontSize: '13px',
                                fontWeight: 500,
                                formatter: function () {
                                    return totalOrders;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (value) {
                    return value.toFixed(1) + '%';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 600
                },
                dropShadow: {
                    enabled: false
                }
            },
            stroke: {
                width: 2
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value + ' Bookings';
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 1200,
                    options: {
                        chart: {
                            height: 320
                        },
                        legend: {
                            fontSize: '12px'
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 350
                        },
                        legend: {
                            fontSize: '12px',
                            itemMargin: {
                                horizontal: 5,
                                vertical: 4
                            }
                        }
                    }
                }
            ]
        }).render();
    }

    var chart3 = document.querySelector('#chart3');

    if (chart3) {
        new ApexCharts(chart3, {
            chart: {
                type: 'line',
                height: 330,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            series: [
                {
                    name: 'Bookings',
                    data: orderTotals
                }
            ],
            xaxis: {
                categories: orderMonths,
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: {
                    formatter: function (value) {
                        return Math.round(value);
                    },
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 4,
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#e9ecef',
                strokeDashArray: 4,
                padding: {
                    left: 10,
                    right: 10
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (value) {
                        return value + ' Bookings';
                    }
                }
            }
        }).render();
    }

    var chart2 = document.querySelector('#chart2');

    if (chart2) {
        new ApexCharts(chart2, {
            chart: {
                type: 'bar',
                height: 330,
                toolbar: {
                    show: false
                }
            },
            series: [
                {
                    name: 'Revenue',
                    data: salesTotals
                }
            ],
            xaxis: {
                categories: salesMonths,
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                labels: {
                    formatter: function (value) {
                        return '₹' + Math.round(value).toLocaleString('en-IN');
                    },
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%'
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#e9ecef',
                strokeDashArray: 4,
                padding: {
                    left: 10,
                    right: 10
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return '₹' + value.toLocaleString('en-IN', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }
            }
        }).render();
    }
});
</script>
@endpush
