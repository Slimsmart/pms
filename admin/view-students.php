<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])):
  include '../db.php';
  $q = $db->prepare("select username, name, phone from users where staff = ?");
  $staff = 0;
  $q->bind_param('s',$staff);
  $q->execute();
  $q->store_result();
  $n = $q->num_rows;
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
  <link href="../css/bootstrap-responsive.css" rel="stylesheet">
  <link rel="../stylesheet" href="css/bootstrap.css">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="../stylesheet" href="css/font-awesome.css">
  <!-- skin color -->
  <link href="../color/default.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="../shortcut icon" href="img/favicon.ico">
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
  					<h3 class="brand navbar-brand" style=""><a href="#">ADMIN</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
  				<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul id="menu-main" class="nav navbar-nav navbar-right">
              <li><a href="home.php"><span class="fa fa-home"></span> Home</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <span class="fa fa-plus"></span> Register
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="add-student.php">Student</a></li>
                  <li><a href="add-staff.php">Staff</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="fa fa-eye"></span> View
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="view-students.php">Student</a></li>
                  <li><a href="view-staffs.php">Staff</a></li>
                </ul>
              </li>
              <li><a href="allocate.php"><span class=""></span> Allocate</a></li>
              <li><a href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
  		      </ul>
  		    </div>
  			</div>
  		</div>
  	</div>
  </div>

  <section class="spacer blue">
  <div class="container">
  	<div class="row">
  		<div class="span10 offset1 aligncenter" style="">
        <div class="panel">
  				<div class="panel-heading">
    				<h3> <b>Student List</b> </h3>
  				</div>
  				<div class="panel-body">
            <?php if ($n < 1): ?>
              <h3>There are no registered students on the System.</h3>
            <?php else: $q->bind_result($id, $name, $phone); ?>
              <table class="table table-bordered table-responsive" style="color:black">
                <thead>
                  <tr>
                    <th class="aligncenter">Matriculation Number</th>
                    <th class="aligncenter">Name</th>
                    <th class="aligncenter">Phone Number</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($q->fetch()): ?>
                    <tr>
                      <td class="aligncenter"><?php echo $id; ?></td>
                      <td class="aligncenter"><?php echo $name; ?></td>
                      <td class="aligncenter"><?php echo $phone; ?></td>
                      <td> <button type="button" class="btn btn-danger" name="button" onclick="con('<?php echo $name; ?>','<?php echo $id; ?>','false')">Remove</button> </td>
                    </tr>
                  <?php endwhile; $q->free_result(); $q->close(); $db->close(); ?>
                </tbody>
              </table>
            <?php endif; ?>
  				</div>
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
  <script src="js/confirm-delete.js"></script>
  <!--<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bgdark icon-2x"></i></a>-->
  <!-- jQuery -->
  <script src="../js/jquery.js"></script>
  <script src="../js/jquery.localscroll-1.2.7-min.js"></script>
  <!-- bootstrap -->
  <script src="../js/bootstrap.js"></script>
  <!-- custom functions -->
  <script src="../js/custom.js"></script>
  </body>
  </html>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
