@extends('master')

@section('header')
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/c3/c3.min.js')}}"></script>
	{{-- <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/echarts/echarts.js')}}"></script> --}}

	<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
	{{-- <script type="text/javascript" src="{{asset('assets/js/charts/echarts/lines_areas.js')}}"></script> --}}

@endsection
@php
    $PATH = explode('/', Request::path());
@endphp
@section('page-bar')
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <a href="{{url()->previous()}}"><i class="icon-arrow-left52 position-left"></i></a>
                <span class="text-semibold">Overview</span>
            </h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="icon-graph position-left"></i> Overview</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-lg-12">
            @if($PATH[0]=='job')
                @include('dashboard.job_trends')
            @elseif($PATH[0]=='skill')
                @include('dashboard.skill_trends')
            @elseif($PATH[0]=='overview')
                @include('dashboard.industry_trends')
            @elseif($PATH[0]=='needs')
                @include('dashboard.needs')
            @endif
        </div>
        <!-- /Revenue Chart -->
    </div>

    <script>
        
    </script>
@endsection

@section('script')
<script>

</script>
@endsection
