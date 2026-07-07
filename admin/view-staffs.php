<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])):
  include '../db.php';
  $q = $db->prepare("select username, name, phone from users where staff = ?");
  $staff = 1;
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
  					<h3 class="navbar-brand fw-bold" style=""><a href="#">ADMIN</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
  				<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
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
  		<div class="col-md-10 offset1 aligncenter" style="">
        <div class="panel">
  				<div class="card-header bg-white border-bottom-0 pt-4 pb-2">
    				<h3><b>Staff List</b></h3>
  				</div>
  				<div class="card-body p-4">
            <?php if ($n < 1): ?>
              <h3>There are no registered staffs on the System.</h3>
            <?php else: $q->bind_result($id, $name, $phone); ?>
              <table class="table table-bordered table-responsive" style="color:black">
                <thead>
                  <tr>
                    <th class="aligncenter">Staff Id</th>
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
                      <td> <button type="button" class="btn btn-danger" name="button" onclick="con('<?php echo $name; ?>','<?php echo $id; ?>','true')">Remove</button> </td>
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
  <script src="js/confirm-delete.js"></script>
  <script src="../js/jquery.localscroll-1.2.7-min.js"></script>
  <!-- bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- custom functions -->
  <script src="../js/custom.js"></script>
  </body>
  </html>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
