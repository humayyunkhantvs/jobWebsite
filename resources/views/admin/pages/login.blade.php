<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="CRMS - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Login - CRMS admin template</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css')}}">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css')}}">

        <!-- Feathericon CSS -->
		<link rel="stylesheet" href="{{ asset('admin/assets/css/feather.css')}}">

        <!--font style-->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap" rel="stylesheet">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css')}}" class="themecls">
		
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
						<a href="index.html"><img src="{{ asset('admin/assets/img/JOB GENIE LOGO 2_png.png') }}" alt="Dreamguy's Technologies"></a>
					</div>
					<!-- /Account Logo -->
				
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>
							<div class="error_div all_errors text-danger text-center"></div>
							@if (request()->has('message'))
							<div class="alert alert-success">
								{{ request('message') }}
							</div>
						@endif
							<!-- Account Form --> 
							<form id="login-form" action="{{ route('login_process') }}">
                                @csrf
								<div class="form-group">
									<label>Email Address</label>
									<input class="form-control" type="text" name="email">
                                    <div class="email_error all_errors text-danger"></div>
								</div>
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
                                    <div class="password_error all_errors text-danger"></div>
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
								<div class="account-footer">
									<p>Don't have an account yet? <a href="{{ route('signup') }}">Register</a></p>
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
        <script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{ url('admin/developer_js/login.js')}}"></script>
		<!-- Bootstrap Core JS -->
        <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
		<!-- Custom JS -->
		<!-- theme JS -->
		<script src="{{ asset('admin/assets/js/theme-settings.js')}}"></script>
		<!-- Custom JS -->
		<script src="{{ asset('admin/assets/js/app.js')}}"></script>
       
    </body>
</html>