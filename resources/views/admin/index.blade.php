@extends('master')

@section('header')
    <script type="text/javascript" src="{{asset('assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/pickadate/picker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins/pickers/pickadate/picker.time.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>

	<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
@endsection

@section('page-bar')
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <a href="{{url()->previous()}}"><i class="icon-arrow-left52 position-left"></i></a>
                <span class="text-semibold">Admin</span>
            </h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="/admin"><i class="icon-newspaper2 position-left"></i> Master Data</a></li>
            <li class="text-muted">Admin</li>
        </ul>
    </div>
@endsection
@php
    $USER   = session('userdata') ?? null;
    $admins = $data['admins'];
@endphp
@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Basic datatable -->
        <div class="panel panel-flat">
            <div class="panel-heading">
                <a href="/admin/add" class="btn btn-primary">Create &plus;</a>
            </div>

            <div class="panel-body">
                <table id="datatable-admin" class="table table-bordered">
                    <thead class="bg-slate">
                        <tr>
                            <th width="50px">No</th>
                            <th class="text-center">Admin Name</th>
                            <th class="text-center">Admin Email</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Role</th>
                            <th class="text-center" width="150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $i=>$adm)
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>{{$adm->_full_name}}</td>
                                <td>{{$adm->_email}}</td>
                                <td>{{$adm->place}}</td>
                                <td>{{$adm->role}}</td>
                                <td class="text-center">
                                    @if ($USER->role == 'SUPER ADMIN' || $adm->role != 'SUPER ADMIN')
                                        <a href="/admin/edit/{{$adm->cms_user_id}}" class="btn btn-xs btn-primary"><i class="icon-pencil5"></i></a>              
                                    @endif
                                    @if ($USER->user_id != $adm->cms_user_id && $adm->role != 'SUPER ADMIN')
                                        <a class="btn btn-xs btn-danger"
                                            onclick="deleteAdmin({{$i}})">
                                            <i class="icon-trash"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /basic datatable -->

        {{-- Invisible Delete form --}}
        <form id="form-admin-delete" action="{{route('admin.delete')}}" method="POST">
            {{ csrf_field() }}
            <input id="input-user-id" type="hidden" name="user_id" value="">
        </form>
    </div>
</div>
<script>
    let admins = @php echo json_encode($admins); @endphp

    $('#datatable-admin').DataTable({
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [1, 2, 5] },
            {className: "text-center", targets: [0]}
        ],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search  : </span>',
            lengthMenu: '<span>Show : </span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });

    function deleteAdmin(index) {

        swal({
            title: "Delete",
            text: "Delete this Admin : "+admins[index]._full_name+"?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $('#input-user-id').val(admins[index].cms_user_id);
                $('#form-admin-delete').submit();
            }
        });
    }
</script>
@endsection
