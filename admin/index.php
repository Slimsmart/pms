<?php
	session_start();
	if (isset($_SESSION['pass'])) {
		header("Location:home.php");
		exit;
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Project Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- css -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link rel="../stylesheet" href="css/font-awesome.css">
<!-- skin color -->
<link href="../color/default.css" rel="stylesheet">
<!-- Favicon -->
<link rel="../shortcut icon" href="img/favicon.ico">

<link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; } .spacer { padding-top: 80px; padding-bottom: 40px; }</style>
</head>
<body>
<!-- navbar -->
<div class="navbar-wrapper">
	<div class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
		<div class="container">
			<div class="container">
				<div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		        <span class="navbar-toggler-icon"></span>
		      </button>
					<h4 class="navbar-brand fw-bold" style=""><a href="#">Project Duplication Detection System</a></h4>
		    </div>
				<!-- Responsive navbar -->

				<!-- navigation -->
				<div class="collapse navbar-collapse" id="myNavbar">
		      <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
						<li><a href="../"><span class="fa fa-arrow-left"></span> Back</a></li>
		      </ul>
		    </div>
			</div>
		</div>
	</div>
</div>

<section class="spacer blue mtb30"  style="margin-top:80px">
<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3 aligncenter" style="">
      <div class="card shadow-sm border-0">
        <form class="form-vertical" method="post" action="auth.php">
  						<div class="card-header bg-white border-bottom-0 pt-4 pb-2">
  							<h3>Sign In</h3>
  						</div>
              <?php if (isset($_SESSION['errmsg'])): ?>
                <span style="color:red; text-align: center;" >
                  <?php echo htmlentities($_SESSION['errmsg']); ?>
                </span>
              <?php endif; unset($_SESSION['errmsg'])?>
  						<div class="card-body p-4">
  							<div class="mb-3">
  								<div class=" row">
  									<input class="col-12 form-control-lg form-control aligncenter" type="text" id="inputEmail" name="username" placeholder="Username" autofocus required>
  								</div>
  							</div>
  							<div class="mb-3">
  								<div class=" row">
  						      <input class="col-12 form-control-lg form-control aligncenter" type="password" id="inputPassword" name="password" placeholder="Password" required>
  								</div>
  							</div>
  						</div>
  						<div class="card-footer bg-white border-top-0 pb-4">
  							<div class="mb-3">
  								<div class=" clearfix">
  									<button type="submit" class="btn btn-primary pull-right form-control-lg" name="login">Login</button>
  								</div>
  							</div>
  						</div>
  					</form>
      </div>
		</div>
	</div>
</div>
</section>


<footer>
<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3">
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
<script src="../js/jquery.js"></script>
<script src="../js/jquery.localscroll-1.2.7-min.js"></script>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- custom functions -->
<script src="../js/custom.js"></script>
</body>
</html>
