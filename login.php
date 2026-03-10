<?php
	session_start();
	if (isset($_SESSION['stupass'])) {
		header("Location:student/");
		exit;
	}
	elseif (isset($_SESSION['stapass'])) {
		header("Location:staff/");
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
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.css">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!-- skin color -->
<link href="color/default.css" rel="stylesheet">
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
					<h5 class="brand navbar-brand" style=""><a href="index.php">Project Duplication Detection System</a></h5>
		    </div>
				<!-- Responsive navbar -->

				<!-- navigation -->
				<div class="collapse navbar-collapse" id="myNavbar">
		      <ul id="menu-main" class="nav navbar-nav navbar-right">
            <li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
		        <li><a href="#"><span class="fa fa-book"></span> About</a></li>
		        <li><a href="login.php"><span class="fa fa-lock"></span> Login</a></li>
						<li><a href="admin/"><span class="fa fa-user"></span> Admin</a></li>
		      </ul>
		    </div>
			</div>
		</div>
	</div>
</div>



<section class="spacer blue mtb30"  style="margin-top:70px">
<div class="container">
	<div class="row">
		<div class="span6 offset3 aligncenter" style="">
      <div class="panel panel-default">
        <form class="form-vertical" method="post" action="auth.php">
  						<div class="panel-heading">
  							<h3>Sign In</h3>
								<?php if (isset($_SESSION['errmsg'])): ?>
									<span style="color:red; text-align: center;" class="aligncenter" >
										<?php echo htmlentities($_SESSION['errmsg']); ?>
									</span>
								<?php endif; unset($_SESSION['errmsg'])?>
  						</div>
              <?php if (isset($_SESSION['errmsg'])): ?>
                <span style="color:red; text-align: center;" >
                  <?php echo htmlentities($_SESSION['errmsg']); ?>
                </span>
              <?php endif; unset($_SESSION['errmsg'])?>
  						<div class="panel-body">
  							<div class="control-group">
  								<div class="controls row-fluid">
  									<input class="span12 input-lg form-control aligncenter" type="text" id="inputEmail" name="username" placeholder="Username" autofocus required>
  								</div>
  							</div>
  							<div class="control-group">
  								<div class="controls row-fluid">
  						      <input class="span12 input-lg form-control aligncenter" type="password" id="inputPassword" name="password" placeholder="Password" required>
  								</div>
  							</div>
  						</div>
  						<div class="panel-footer">
  							<div class="control-group">
  								<div class="controls clearfix">
  									<button type="submit" class="btn btn-primary pull-right input-lg" name="login">Login</button>
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
