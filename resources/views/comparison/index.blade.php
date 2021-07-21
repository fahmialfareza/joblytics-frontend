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
                <i class="icon-stats-bars3 position-left"></i>
                <span class="text-semibold">Comparison</span>
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
        <div class="col-lg-12">
            @include('comparison.industry_needs')
            @include('comparison.bootcamp_needs')
        </div>
    </div>
@endsection
@section('script')
    <script>
        let $yearIn, $yearOut
        
        $.getJSON('./json/years.json', function (data) { 
            $years = data 
            let yearHtml = ``
            data.forEach(y => {
                yearHtml += `<option value="${y.no}">${y.years}</option>`
            });
            $('#input-year-in').html(yearHtml)
            $('#input-year-out').html(yearHtml)

            // select last option in year out
            let lastValue = $('#input-year-out option:last').val()
            $('#input-year-out').val(lastValue)
            
            $yearIn = $('#input-year-in').val()
            $yearOut = $('#input-year-out').val()
            
            setTimeout(() => {
                searchIndustryNeeds()
                searchBootcampNeeds()
            }, 100);

        })

        function yearInSelect() {
            $yearIn = $('#input-year-in').val()
        }

        function yearOutSelect() {
            $yearOut = $('#input-year-out').val()
        }
    </script>
@endsection
