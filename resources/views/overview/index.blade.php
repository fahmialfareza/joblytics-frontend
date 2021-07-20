@extends('master')

@section('header')
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/visualization/c3/c3.min.js')}}"></script>

	<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
@endsection
@php
    $PATH = explode('/', Request::path());
@endphp
@section('page-bar')
    <div class="page-header-content">
        <div class="page-title">
            <h4>
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
        <div class="panel panel-flat">
             <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">From : </span>
                                <select class="select2" id="input-year-in" onselect="yearInSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">To : </span>
                                <select class="select2" id="input-year-out" onselect="yearOutSelect()">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <!-- Revenue Chart -->
        <div class="col-lg-12">
            {{-- @if($PATH[0]=='job') --}}
                @include('overview.job_trends')
            {{-- @elseif($PATH[0]=='skill') --}}
                @include('overview.skill_trends')
            {{-- @elseif($PATH[0]=='overview') --}}
                @include('overview.industry_trends')
            {{-- @elseif($PATH[0]=='needs') --}}
                @include('overview.needs')
            {{-- @endif --}}
        </div>
        <!-- /Revenue Chart -->
    </div>

    <script>
        
    </script>
@endsection

@section('script')
<script>
    $.getJSON('./json/years.json', function (data) { 
        $years = data 
        let yearHtml = ``
        data.forEach(y => {
            yearHtml += `<option value="${y.no}">${y.years}</option>`
        });
        $('#input-year-in').html(yearHtml)
        $('#input-year-out').html(yearHtml)
        $yearIn = $('#input-year-in').val()
        $yearOut = $('#input-year-out').val()
    })

    function yearInSelect() {
        $yearIn = $('#input-year-in').val()
    }
    function yearOutSelect() {
        $yearOut = $('#input-year-out').val()
    }
</script>
@endsection
