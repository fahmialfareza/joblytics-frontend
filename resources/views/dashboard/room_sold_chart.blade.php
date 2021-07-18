<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="col-md-6">
            <h6 class="panel-title text-semibold">Room Sold</h6>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Search Period : </span>
                    <span class="input-group-addon"><i class="icon icon-calendar"></i></span>
                    <input type="text" class="form-control" id="period-room-sold">
                    <a class="input-group-addon btn btn-primary bg-primary" onclick="filterDateRoomSold()">Search</a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="chart-container" style="margin-bottom:30px">
            <div class="chart" id="room-sold-chart"></div>
        </div>
    </div>
</div>
<script>

    let $startDateRoomSold   = '01/01/'+new Date().getFullYear();
    let $endDateRoomSold     = '31/12/'+new Date().getFullYear();

    $(document).ready(function (params) {
        // reloadChartRoomSold($startDateRoomSold, $endDateRoomSold)
    });

    let roomRoomSoldChart = c3.generate({
        data: { x: 'x', columns: [ [], [] ], },
    });

    $('#period-room-sold').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        startDate: $startDateRoomSold,
        endDate: $endDateRoomSold,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    function reloadChartRoomSold(start_date, end_date, location=null) {

        let start   = start_date.split('/');
        let end     = end_date.split('/');

        // Clear Datatable
        tableBooks.clear();
        tableBooks.draw();

        // Re-initialize Datatable
        $.ajax({
        url: 'api/dashboard/room_sold',
        method: 'get',
        data: {
            'start_date'    : start[2]+'-'+start[1]+'-'+start[0],
            'end_date'      : end[2]+'-'+end[1]+'-'+end[0],
            'location'      : location
        },
        success: function (res) {
            let solds = ['Room Sold']
            let dates = ['x']
            res.room_sold.forEach(r => {
                solds.push(r.total_sold)
                dates.push(r.date)
            });

            roomRoomSoldChart.destroy();

            // Room Sold Chart
            roomRoomSoldChart = c3.generate({
                    bindto: '#room-sold-chart',
                    point: {
                        r: 4
                    },
                    color: {
                        pattern: ['#3949AB']
                    },
                    size: { height: 400 },
                    data: {
                        x: 'x',
                        columns: [
                            dates,
                            solds
                        ],
                        types: {
                            'x'  : 'area',
                            'Room Sold': 'area',
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        y: {
                            show: true
                        }
                    },
                    axis: {
                        x: {
                            type : 'timeseries',
                            tick: {
                                format: '%d/%m/%Y'
                            },
                        },
                        y: {
                            label: {
                                text: 'Room Sold',
                                position: 'outer-middle'
                            }
                        }
                    }
                });
            },
            error: function (err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        });
    }

    function filterDateRoomSold() {
        let start   = $('#period-room-sold').data('daterangepicker').startDate._d;
        let end     = $('#period-room-sold').data('daterangepicker').endDate._d;

        let start_month   = start.getMonth()+1 < 10 ? '0'+(start.getMonth()+1) : start.getMonth()+1;
        let start_date    = start.getDate() < 10 ? '0'+(start.getDate()) : start.getDate();

        let end_month   = end.getMonth()+1 < 10 ? '0'+(end.getMonth()+1) : end.getMonth()+1;
        let end_date    = end.getDate() < 10 ? '0'+(end.getDate()) : end.getDate();

        $startDateRoomSold   = start_date+'/'+start_month+'/'+start.getFullYear();
        $endDateRoomSold     = end_date+'/'+end_month+'/'+end.getFullYear();
        
        let location = localStorage.getItem('passgo_dashboard_location')
        reloadChartRoomSold($startDateRoomSold, $endDateRoomSold, location);
    }
</script>
