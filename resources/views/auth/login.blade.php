<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pass GO - CMS</title>
    <link rel="shortcut icon" href="{{asset('assets/images/passgo_logo_box.png')}}" type="image/x-icon">
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/core.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/css/colors.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>

</head>

<body class="login-container">
    <!-- Main navbar -->
	<div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                Pass GO - CMS
            </a>
        </div>
    </div>
    <!-- /main navbar -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Simple login form -->
					<form id="form-login" method="POST" action="{{route('auth.submit.login')}}">
						<div class="panel panel-body login-form">
							<h5 class="content-group">Login</h5>
                            {{ csrf_field() }}
							<div class="form-group has-feedback has-feedback-left">
								<input id="input-email" name="user_email" type="text" class="form-control" placeholder="Email" required>
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								{{-- <input id="input-password" type="password" class="form-control" placeholder="Password" required> --}}

                                <div class="input-group has-feedback-left">
                                    <input id="input-password" name="user_password" type="password" class="form-control" placeholder="Password" required>
                                    <a class="input-group-addon" onclick="peekPassword()">
                                        <i class="icon-eye text-muted"></i>
                                    </a>
                                </div>
                                <div class="form-control-feedback" >
                                    <i class="icon-lock text-muted"></i>
                                </div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Login <i class="icon-circle-right2 position-right"></i></button>
							</div>
						</div>
					</form>
					<!-- /simple login form -->

                    <!-- Footer -->
                    <div class="footer text-muted text-center">
                        &copy; 2021. <a href="https://passgo.id" target="_blank">Pass GO Hotel</a> by <a href="https://passgo.id" target="_blank">PT Generasi Epik Nusantara</a>
                    </div>
                    <!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
    @include('alert.alert')

	<script>
		function peekPassword() {
			let inputPass = document.getElementById('input-password')
            if (inputPass.type === 'password') {
                inputPass.type = 'text'
                $('.icon-eye').addClass('text-primary')
            } else {
                inputPass.type = 'password'
                $('.icon-eye').removeClass('text-primary')
            }
		}
	</script>
</body>
</html>
