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
                <i class="icon-stats-growth position-left"></i>
                <span class="text-semibold">Trends</span>
            </h4>
        </div>
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
                                <span class="input-group-addon">Job : </span>
                                <select class="select2" id="input-job" oninput="jobSelect()"></select>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="col-lg-12">
            {{-- @include('trends.job_trends')
            @include('trends.skill_trends')
            @include('trends.industry_trends')
            @include('trends.bootcamp_trends') --}}
        </div>
    </div>
@endsection
@section('script')
    <script>
        let $jobsSelected
        
        $.getJSON('./json/jobs.json', function (data) { 
            $jobs = data 
            let randomIndexs = []
            let jobHtml = ``

            data.forEach(j => {
                jobHtml += `<option value="${j.no}">${j.jobs}</option>`
            });

            $('#input-job').html(jobHtml)
            $('#input-job').val(Math.floor(Math.random() * data.length))
            $jobsSelected = $('#input-job').val()
        })

        setTimeout(() => {
            searchJobDetail()
        }, 100);
    </script>
@endsection
