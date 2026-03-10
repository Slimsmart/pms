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
  					<h3 class="brand navbar-brand" style=""><a href="#">STAFF</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
  				<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul id="menu-main" class="nav navbar-nav navbar-right">
              <li><a href="index.php"><span class="fa fa-home"></span> Home</a></li>
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
  		<div class="span10 offset1 aligncenter" style="">
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
