@extends('layout.master')
@section('title', 'Ecommerce Dashboard')
@section('css')

    <!-- apexcharts css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}">

    <!-- slick css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/slick/slick-theme.css') }}">

    <!-- glight css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/glightbox.min.css') }}">

    <!-- Data Table css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/jquery.dataTables.min.css') }}">

    <!-- vector map css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/vector-map/jquery-jvectormap.css') }}">

@endsection
@section('main-content')
    <div class="container-fluid">
        <h1>Home</h1>
    </div>
@endsection

@section('script')

    <!-- slick-file -->
    <script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>

    <!-- vector map plugin js -->
    <script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-world-mill.js') }}"></script>

    <!--cleave js  -->
    <script src="{{ asset('assets/vendor/cleavejs/cleave.min.js') }}"></script>

    <!-- Glight js -->
    <script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>

    <!-- data table-->
    <script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>

    <!-- apexcharts js-->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Ecommerce js-->
    <script src="{{ asset('assets/js/ecommerce_dashboard.js') }}"></script>

@endsection
