<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h5 class="panel-title text-semibold">Bookings</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Search Period : </span>
                    <span class="input-group-addon"><i class="icon icon-calendar"></i></span>
                    <input id="input-dateperiod-books" type="text" class="form-control daterange" value="">
                    {{-- <a class="input-group-addon btn btn-warning" onclick="resetDate()"><i class="icon-cross"></i></a> --}}
                    <a class="input-group-addon btn btn-primary bg-primary" onclick="filterDateBooks()">Search</a>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="chart-container">
            <div class="chart has-fixed-height" id="stacked_lines"></div>
        </div>
    </div>
</div>

<script>
    let $startDateBooks   = '01/01/'+new Date().getFullYear();
    let $endDateBooks     = '31/12/'+new Date().getFullYear();

    let tableBooks = $('#datatable-booked').DataTable({
                    fixedHeader: true,
                    autoWidth: true,
                    searching: false,
                    lengthChange: false,
                    paging: false,
                    info: false,
                    columnDefs: [
                        { orderable: false, targets: [0,1,2,3,4,5,6] },
                        {className:"text-right", targets:[5]}
                    ],
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    language: {
                        search: '<span>Search  : </span> _INPUT_',
                        lengthMenu: '<span>Show : </span> _MENU_',
                        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                    },
                    order: []
                })

    $('.daterange').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
        startDate: $startDateBooks,
        endDate: $endDateBooks,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $(document).ready(function (params) {
    //    reloadTableBooks($startDateBooks, $endDateBooks);
    });
    
    function reloadTableBooks(start_date, end_date, location=null) {

        let start   = start_date.split('/');
        let end     = end_date.split('/');

        // Clear Datatable
        tableBooks.clear();
        tableBooks.draw();

        // Re-initialize Datatable
        $.ajax({
           url: 'api/dashboard/booked',
           method: 'get',
           data: {
                'start_date'    : start[2]+'-'+start[1]+'-'+start[0],
                'end_date'      : end[2]+'-'+end[1]+'-'+end[0],
                'location'      : location,
                'limit'         : 5
           },
           success: function (res) {
               if (res.bookings.length > 0) {
                    res.bookings.forEach((book, i) => {
                        tableBooks.row.add([
                            i+1,
                            book.order_no,
                            book.booking_date,
                            book.placename,
                            book.roomname,
                            currency(book.total_price),
                            book.status
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

    function filterDateBooks() {
        let start   = $('#input-dateperiod-books').data('daterangepicker').startDate._d;
        let end     = $('#input-dateperiod-books').data('daterangepicker').endDate._d;

        let start_month   = start.getMonth()+1 < 10 ? '0'+(start.getMonth()+1) : start.getMonth()+1;
        let start_date    = start.getDate() < 10 ? '0'+(start.getDate()) : start.getDate();

        let end_month   = end.getMonth()+1 < 10 ? '0'+(end.getMonth()+1) : end.getMonth()+1;
        let end_date    = end.getDate() < 10 ? '0'+(end.getDate()) : end.getDate();

        $startDateBooks   = start_date+'/'+start_month+'/'+start.getFullYear();
        $endDateBooks     = end_date+'/'+end_month+'/'+end.getFullYear();
        
        let location = localStorage.getItem('passgo_dashboard_location')
        reloadTableBooks($startDateBooks, $endDateBooks, location);
    }

    function currency(money) {
        let bilangan = parseInt(money);
		
        let	reverse = bilangan.toString().split('').reverse().join(''),
            ribuan 	= reverse.match(/\d{1,3}/g);
            ribuan	= ribuan.join('.').split('').reverse().join('');

        // Cetak hasil	
        return 'Rp '+ribuan;
    }
</script>
