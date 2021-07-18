<style>
.no-bottom {
    margin-bottom: 0px;
}
.list-inline {
    margin-bottom: 0px;
}
</style>
<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h5 class="panel-title text-semibold">Hotel Overview</h6>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Search Period : </span>
                    <span class="input-group-addon"><i class="icon icon-calendar"></i></span>
                    <input id="input-dateperiod-statistik" type="text" class="form-control daterange" value="">
                    {{-- <a class="input-group-addon btn btn-warning" onclick="resetDate()"><i class="icon-cross"></i></a> --}}
                    <a class="input-group-addon btn btn-primary bg-primary" onclick="filterDate()">Search</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div id="statistik-hotel" class="col-lg-3 col-md-4 col-sm-6">
                <div class="panel panel-body no-bottom">
                    <ul class="list-inline text-center">
                        <li class="text-center">
                            <div class="text-semibold">Total Hotel</div>
                            <h5>
                                <span id="total-hotel-on" class="text-primary no-bottom">00</span>
                                <br>
                                <span id="total-hotel-off" class="text-muted no-bottom">00</span>
                            </h5>
                        </li-off>
                        <li>
                            <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-xs valign-text-bottom">
                                <i class="icon-office"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="statistik-unit" class="col-lg-3 col-md-4 col-sm-6">
                <div class="panel panel-body no-bottom">
                    <ul class="list-inline text-center">
                        <li class="text-center">
                            <div class="text-semibold">Total Unit Room</div>
                            <h5>
                                <span id="total-unit-room" class="text-muted no-bottom">00</span>
                            </h5>
                        </li-off>
                        <li>
                            <a href="#" class="btn border-primary text-primary btn-flat btn-rounded btn-icon btn-xs valign-text-bottom">
                                <i class="icon-office"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="panel panel-body no-bottom">
                    <ul class="list-inline text-center">
                        <li class="text-center">
                            <div class="text-semibold">Total Booking</div>
                            <h5 id="total-booking" class="text-muted no-bottom">00</h5>
                        </li>
                        <li>
                            <a href="#" class="btn border-danger text-danger btn-flat btn-rounded btn-icon btn-xs valign-text-bottom">
                                <i class="icon-file-check2"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="panel panel-body no-bottom">
                    <ul class="list-inline text-center">
                        <li class="text-center">
                            <div class="text-semibold">Total Revenue</div>
                            <h5 id="total-revenue" class="text-muted no-bottom">Rp 0</h5>
                        </li>
                        <li>
                            <a href="#" class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs valign-text-bottom">
                                <i class="icon-cash3"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="panel panel-body no-bottom">
                    <ul class="list-inline text-center">
                        <li class="text-center">
                            <div class="text-semibold">Total Review</div>
                            <h5 id="total-review" class="text-muted no-bottom">0</h5>
                        </li>
                        <li>
                            <a href="#" class="btn border-orange text-orange btn-flat btn-rounded btn-icon btn-xs valign-text-bottom">
                                <i class="icon-star-full2"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="content-group-sm" id="app_sales"></div>
    <div id="monthly-sales-stats"></div>
</div>

<script>

    let $startDateStatistik   = '01/01/'+new Date().getFullYear();
    let $endDateStatistik     = '31/12/'+new Date().getFullYear();

    $('#input-dateperiod-statistik').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        startDate: $startDateStatistik,
        endDate: $endDateStatistik,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });
    
    $(document).ready(function (params) {
    //    reloadTableStatistik($startDateStatistik, $endDateStatistik);
    });

    function reloadTableStatistik(start_date, end_date, location=null) {

        if (location == null) {
            $('#statistik-hotel').show()
            $('#statistik-unit').hide()
        } else {
            $('#statistik-hotel').hide()
            $('#statistik-unit').show()
        }

        let start   = start_date.split('/');
        let end     = end_date.split('/');

        // Re-initialize Datatable
        $.ajax({
            url: 'api/dashboard/statistik',
            method: 'get',
            data: {
                    'start_date'    : start[2]+'-'+start[1]+'-'+start[0],
                    'end_date'      : end[2]+'-'+end[1]+'-'+end[0],
                    'location'      : location
            },
            success: function (res) {
                $('#total-hotel-on').html('<small class="text-primary">Active : </small>'+res.total_hotel_on)
                $('#total-hotel-off').html('<small>Inactive : </small>'+res.total_hotel_off)
                $('#total-unit-room').html(res.total_unit)
                $('#total-booking').html(res.total_booking)
                $('#total-revenue').html(res.total_revenue)
                $('#total-review').html(res.total_review)
            },
            error: function (err) {
                console.error(err);
                // $('#showing-message').html("Error occured");
            }
        });
    }

    function filterDate() {
        let start   = $('#input-dateperiod-statistik').data('daterangepicker').startDate._d;
        let end     = $('#input-dateperiod-statistik').data('daterangepicker').endDate._d;

        let start_month   = start.getMonth()+1 < 10 ? '0'+(start.getMonth()+1) : start.getMonth()+1;
        let start_date    = start.getDate() < 10 ? '0'+(start.getDate()) : start.getDate();

        let end_month   = end.getMonth()+1 < 10 ? '0'+(end.getMonth()+1) : end.getMonth()+1;
        let end_date    = end.getDate() < 10 ? '0'+(end.getDate()) : end.getDate();

        $startDateStatistik   = start_date+'/'+start_month+'/'+start.getFullYear();
        $endDateStatistik     = end_date+'/'+end_month+'/'+end.getFullYear();

        let location = localStorage.getItem('passgo_dashboard_location')
        reloadTableStatistik($startDateStatistik, $endDateStatistik, location);
    }
</script>
