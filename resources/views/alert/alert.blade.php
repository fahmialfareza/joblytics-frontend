<script type="text/javascript" src="{{asset('assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>

@if (session('error_alert') != null)
    <script>
        let error_message = @php echo json_encode(session('error_alert')) @endphp;
        swal({
            type: 'error',
            title: 'Error',
            text: error_message,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: true,
            focusConfirm: true
        })
    </script>
@endif
@if (session('success_alert') != null)
    <script>
        let success_message = @php echo json_encode(session('success_alert')) @endphp;
        swal({
            type: 'success',
            title: '',
            text: success_message,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: true,
            focusConfirm: true
        })
    </script>
@endif
@if (session('warning_alert') != null)
    <script>
        let warning_message = @php echo json_encode(session('warning_alert')) @endphp;
        swal({
            type: 'warning',
            title: '',
            text: warning_message,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: true,
            focusConfirm: true
        })
    </script>
@endif
@if (session('info_alert') != null)
    <script>
        let info_message = @php echo json_encode(session('info_alert')) @endphp;
        swal({
            type: 'info',
            title: '',
            text: info_message,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: true,
            focusConfirm: true
        })
    </script>
@endif
