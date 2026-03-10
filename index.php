
	<!DOCTYPE HTML>
	<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Project Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- css -->
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.css">
	<!-- skin color -->
	<link href="color/default.css" rel="stylesheet">
	<!-- Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	</head>
	<body>
	<!-- navbar -->
	<div class="navbar-wrapper">
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h4 class="brand navbar-brand" style=""><a href="#">Intelligent Project Duplication Detection System</a></h4>
					</div>
					<!-- Responsive navbar -->

					<!-- navigation -->
					<div class="collapse navbar-collapse" id="myNavbar">
						<ul id="menu-main" class="nav navbar-nav navbar-right">
							<li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
							<li><a href="#"><span class="fa fa-book"></span> About</a></li>
							<li><a href="login.php"><span class="fa fa-lock"></span> Login</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>



	<section class="spacer blue mtb30"  style="margin-top: 100px">
	<div class="container">
		<div class="row">
			<div class="span12 aligncenter flyUp">
				<h2 class="pagetitle"><strong>Search Projects</strong></h2>
			</div>

			<div class="span8 offset2 aligncenter" style="margin-bottom:50px">
				<form class="" action="search-result.php" method="get">
					<div class="form-group">
						<input type="text" name="q" class="form-control input-lg aligncenter" placeholder="Enter project topic/ Project area" style="height:100%; font-size:20px; padding:12px;">
					</div>
				</form>
			</div>
		</div>
	</div>
	</section>

	<footer>
	<div class="container">
		<div class="row">
			<div class="span6 offset3">
				<p class="copyright">
					&copy; <?php echo date('Y'); ?>. All rights reserved.
				</p>
			</div>
		</div>
	</div>
	<!-- ./container -->
	</footer>
	<!--<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bgdark icon-2x"></i></a>-->
	<!-- jQuery -->
	<script src="js/jquery.js"></script>
	<script src="js/jquery.localscroll-1.2.7-min.js"></script>
	<!-- bootstrap -->
	<script src="js/bootstrap.js"></script>
	<!-- custom functions -->
	<script src="js/custom.js"></script>
	</body>
	</html>
