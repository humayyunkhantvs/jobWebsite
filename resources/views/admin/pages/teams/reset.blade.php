<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="CRMS - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">

        <title>Reset Password</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('admin/assets/img/favicon.png')}}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{url('admin/assets/css/bootstrap.min.css')}}">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{url('admin/assets/css/font-awesome.min.css')}}">

        <!-- Feathericon CSS -->
		<link rel="stylesheet" href="{{url('admin/assets/css/feather.css')}}">

        <!--font style-->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap" rel="stylesheet">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{url('admin/assets/css/style.css')}}" class="themecls">
		<script src="{{url('admin/assets/js/jquery-3.6.0.min.js')}}"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body class="account-page">
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				
				<div class="container">
				
					<!-- Account Logo -->
					<div class="account-logo">
						<a href="index.html"><img src="{{url('admin/assets/img/logo.png')}}" alt="Dreamguy's Technologies"></a>
					</div>
					<!-- /Account Logo -->
					
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Reset Password Form</h3>
							{{-- <p class="account-subtitle">Access to our dashboard</p> --}}
							<div class="success"></div>
							<!-- Account Form -->
                        <form id="reset1" action="{{ route('user.password.update') }}" >
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">
                         
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Password</label>
                                    </div>
                                    <div class="col-auto">
                                        <a class="text-muted" href="forgot-password.html">
                                            Forgot password?
                                        </a>
                                    </div>
                                </div>
                                <input class="form-control" type="password" name="password">
                                <div class="password_error all_errors"></div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Confirm Password</label>
                                    </div>
                                </div>
                                <input class="form-control" type="password" name="confirm_password">
                                <div class="confirm_password_error all_errors"></div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Reset Password</button>
                            </div>
                            <div class="account-footer">
                                <p>Already have an account? <a href="register.html">login</a></p>
                            </div>
                        </form>
                            
							<!-- /Account Form -->
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
		<script src="{{ url('admin/developer_js/teams.js')}}"></script>

		<!-- Bootstrap Core JS -->
        <script src="{{url('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
		
		<!-- Custom JS -->
		<!-- theme JS -->
		<script src="{{url('admin/assets/js/theme-settings.js')}}"></script>
		<!-- Custom JS -->
		<script src="{{url('admin/assets/js/app.js')}}"></script>
        
    </body>
</html>