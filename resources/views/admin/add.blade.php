@extends('master')

@section('header')
	<script type="text/javascript" src="{{asset('assets/js/plugins/forms/validation/validate.min.js')}}"></script>


	<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
@endsection
@php
    $roles = $data['roles'];
    $locations = $data['locations'];
@endphp
@section('page-bar')
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <a href="{{url()->previous()}}"><i class="icon-arrow-left52 position-left"></i></a>
                <span class="text-semibold">Admin - Create</span>
            </h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="icon-newspaper2 position-left"></i> Master Data</li>
            <li><a href="/admin"> Admin</a></li>
            <li class="text-muted">Edit</li>
        </ul>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Basic datatable -->
        <div class="panel panel-flat">
            <div class="panel-body">
                <form id="form-add-admin" class="form-horizontal" action="{{route('admin.create')}}" method="post">
                    {{ csrf_field() }}
                    <fieldset class="content-group">
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Admin Name : </label>
                            <div class="col-lg-4">
                                <input id="input-name" name="fullname" type="text" class="form-control" required 
                                    placeholder="Name" value="{{old('fullname')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Admin Email : </label>
                            <div class="col-lg-4">
                                <input id="input-email" name="email" type="email" class="form-control" required 
                                    placeholder="example@mail.com" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Password : </label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input id="input-password" name="password" type="password" class="form-control" required 
                                        placeholder="your secret password" value="{{old('password')}}">
                                    <div class="input-group-addon" onclick="peekPassword('input-password', 'icon-eye-pass')">
                                        <i id="icon-eye-pass" class="icon-eye text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Verify Password : </label>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input id="input-verify-password" name="verifypass" type="password" class="form-control" required 
                                        placeholder="verify your password" value="{{old('verifypass')}}">
                                    <a class="input-group-addon" onclick="peekPassword('input-verify-password', 'icon-eye-verpass')">
                                        <i id="icon-eye-verpass" class="icon-eye text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Location : </label>
                            <div class="col-lg-4">
                                <select id="input-location" name="location" class="form-control" required>
                                    @foreach ($locations as $loc)
                                    <option value="{{$loc->place_id}}" 
                                        @if (old('location')==$loc->place_id) selected @endif>
                                        {{$loc->_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"><code>*</code> Role : </label>
                            <div class="col-lg-4">
                                <select id="input-role" name="role" class="form-control" required>
                                    @foreach ($roles as $role)
                                    <option value="{{$role->role_id}}">{{$role->_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="col-lg-6">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="/admin" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /basic datatable -->
    </div>
</div>
<script>
    function peekPassword(input, icon) {
        let inputPass = document.getElementById(input)
        if (inputPass.type === 'password') {
            inputPass.type = 'text'
            $('#'+icon).addClass('text-primary')
        } else {
            inputPass.type = 'password'
            $('#'+icon).removeClass('text-primary')
        }
    }

    let validator = $("#form-add-admin").validate({
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        // // Different components require proper error label placement
        errorPlacement: function(error, element) {
            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo( element.parent().parent().parent().parent() );
                }
                 else {
                    error.appendTo( element.parent().parent().parent().parent().parent() );
                }
            }
            // Input group, styled file input
            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            else {
                error.insertAfter(element);
            }
        },
        validClass: "validation-valid-label",
        success: function(label) {
            label.addClass("validation-valid-label").text("Success.")
        },
        rules: {
            fullname: {
                minlength: 5
            },
            password: {
                minlength: 5
            },
            verifypass: {
                equalTo: "#input-password"
            },
            email: {
                minlength: 10,
                email: true
            },
            maximum_characters: {
                maxlength: 10
            },
        }
    });
</script>
@endsection
