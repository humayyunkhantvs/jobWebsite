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

        <div class="page-wrapper">
            <div class="account-box">
                <div class="account-wrapper">
                    
                    <!-- Account Form -->
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <div class="text-center">
                            <img src="{{ asset('assets/img/icons/verification.png') }}" width="100" alt="" />
                        </div>
                        <h4 class="mt-5 font-weight-bold text-center">Verify Your Email Address First</h4>
                        <div class="form-group text-center">
                            <button class="btn btn-primary account-btn" type="submit">Request Another Email Verification Link</button>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-light"><i class='bx bx-arrow-back me-1'></i>Back to Login</a>
                        </div>
                    </form>
                    <!-- /Account Form -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
        <!-- /Main Wrapper -->
        <script src="{{url('admin/assets/js/developer_js/blog1.js')}}"></script>
<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="{{url('admin/assets/js/jquery-3.6.0.min.js')}}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{{url('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
		
		<!-- Custom JS -->
		<!-- theme JS -->
		<script src="{{url('admin/assets/js/theme-settings.js')}}"></script>
		<!-- Custom JS -->
		<script src="{{url('admin/assets/js/app.js')}}"></script>
    </body>
</html>