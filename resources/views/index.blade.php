@extends('layout.master')
@section('title', ' Dashboard')
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
        <h1>Dashboard</h1>
    </div>

@endsection

@section('script')


@endsection
