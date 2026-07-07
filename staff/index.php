<?php session_start(); ?>
<?php if (isset($_SESSION['stapass'])):
  $staffid = $_SESSION['stapass'];
  include '../db.php';
  $q = $db->prepare("select student from allocation where staff = ?");
  $q->bind_param('s',$staffid);
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
  					<h3 class="navbar-brand fw-bold" style=""><a href="#">STAFF</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
  				<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
              <li><a href="preferences.php"><span class="fa fa-list-ol"></span> Preferences</a></li>
              <li><a href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
  		      </ul>
  		    </div>
  			</div>
  		</div>
  	</div>
  </div>

  <section class="spacer blue mtb30" style="margin-top:30px;">
  <div class="container">
  	<div class="row">
  		<div class="col-md-10 offset1 aligncenter" style="">
    			<h3><b>Project students</b></h3>
          <?php if ($n > 0): $q->bind_result($studentid); $x = 0; ?>
            <table class="table table-bordered table-responsive" style="color:black">
              <thead>
                <tr>
                  <th class="aligncenter">#</th>
                  <th class="aligncenter">Matriculation number</th>
                  <th class="aligncenter">Name</th>
                  <th class="aligncenter">Phone</th>
                  <th colspan="2"> </th>
                </tr>
              </thead>
              <tbody>
                <?php while ($q->fetch()):
                  ++$x;
                  $q0 = $db->prepare("select name, phone from users where username = ?");
                  $q0->bind_param('s',$studentid);
                  $q0->execute();
                  $q0->bind_result($name, $phone);
                  $q0->fetch();
                  $q0->close();
                ?>
                <tr>
                  <td> <?php echo $x; ?> </td>
                  <td class="aligncenter"><?php echo $studentid; ?></td>
                  <td class="aligncenter"><?php echo $name; ?></td>
                  <td class="aligncenter"><?php echo $phone; ?></td>
                  <td><a href="view-topics.php?student_id=<?php echo $studentid; ?>" class="btn btn-success">View topic</a></td>
                  <td><a href="view-chapters.php?student_id=<?php echo $studentid; ?>" class="btn btn-success">View chapters</a></td>
                </tr>
              <?php endwhile; $q->close(); $db->close(); ?>
              </tbody>
            </table>
          <?php else: ?>
            <h4>You have not been assigned project students.</h4>
          <?php endif; ?>
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
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
