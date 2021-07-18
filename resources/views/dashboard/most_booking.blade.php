<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h5 class="panel-title text-semibold">Total Booking</h6>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Search Period : </span>
                    <span class="input-group-addon"><i class="icon icon-calendar"></i></span>
                    <input id="input-dateperiod-booking" type="text" class="form-control daterange" value="">
                    {{-- <a class="input-group-addon btn btn-warning" onclick="resetDate()"><i class="icon-cross"></i></a> --}}
                    <a class="input-group-addon btn btn-primary bg-primary" onclick="filterDateBooking()">Search</a>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="datatable-booking" class="table table-bordered table-framed table-xxs">
                <thead class="bg-slate">
                    <tr>
                        <th width="50px">No</th>
                        <th>Hotel</th>
                        <th>Total Booking</th>
                    </tr>
                </thead>
            </table>
        </div>
        <a class="float-right" href="/booking">More detail...</a>
    </div>
</div>

<script>
    let $startDateBooking   = '01/01/'+new Date().getFullYear();
    let $endDateBooking     = '31/12/'+new Date().getFullYear();

    let tableBooking = $('#datatable-booking').DataTable({
                    fixedHeader: true,
                    autoWidth: false,
                    searching: false,
                    lengthChange: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        orderable: false,
                        targets: [0,1,2]
                    }],
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    language: {
                    //     search: '<span>Search  : </span> _INPUT_',
                    //     lengthMenu: '<span>Show : </span> _MENU_',
                        // paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                    },
                    order: []
                })

    $('.daterange').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        startDate: $startDateBooking,
        endDate: $endDateBooking,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $(document).ready(function (params) {
    //    reloadTableBooking($startDateBooking, $endDateBooking);
    });
    
    function reloadTableBooking(start_date, end_date, location=null) {

        let start   = start_date.split('/');
        let end     = end_date.split('/');

        // Clear Datatable
        tableBooking.clear();
        tableBooking.draw();

        // Re-initialize Datatable
        $.ajax({
           url: 'api/dashboard/booking',
           method: 'get',
           data: {
                'start_date'    : start[2]+'-'+start[1]+'-'+start[0],
                'end_date'      : end[2]+'-'+end[1]+'-'+end[0],
                'location'      : location
           },
           success: function (res) {
               if (res.bookings.length > 0) {
                    res.bookings.forEach((book, i) => {
                        tableBooking.row.add([
                            i+1,
                            book.placename,
                            book.total_booking
                        ]).draw(false);
                    });
                } else{
                    $('#showing-message').html("No data found");
                }
            },
            error: function (err) {
                console.error(err);
                $('#showing-message').html("Error occured");
            }
        });
    }

    function filterDateBooking() {
        let start   = $('#input-dateperiod-booking').data('daterangepicker').startDate._d;
        let end     = $('#input-dateperiod-booking').data('daterangepicker').endDate._d;

        let start_month   = start.getMonth()+1 < 10 ? '0'+(start.getMonth()+1) : start.getMonth()+1;
        let start_date    = start.getDate() < 10 ? '0'+(start.getDate()) : start.getDate();

        let end_month   = end.getMonth()+1 < 10 ? '0'+(end.getMonth()+1) : end.getMonth()+1;
        let end_date    = end.getDate() < 10 ? '0'+(end.getDate()) : end.getDate();

        $startDateBooking   = start_date+'/'+start_month+'/'+start.getFullYear();
        $endDateBooking     = end_date+'/'+end_month+'/'+end.getFullYear();
        
        let location = localStorage.getItem('passgo_dashboard_location')
        reloadTableBooking($startDateBooking, $endDateBooking, location);
    }
</script>
