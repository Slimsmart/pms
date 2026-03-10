<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])): ?>
  <?php if (isset($_GET['staffid'])):
    $staffid = $_GET['staffid'];

    include "../db.php";
    $q = $db->prepare("select name from users where username = ?");
    $q->bind_param('s', $staffid);
    $q->execute();
    $q->store_result();
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

    <div class="container" style="margin-top: 130px;">
      <div class="row">
        <div class="span8 offset2">
          <?php if ($q->num_rows > 0):
            $q->bind_result($staff_name);
            $q->fetch();
            $q->close();

            $q = $db->prepare("select student from allocation where staff = ?");
            $q->bind_param('s', $staffid);
            $q->execute();
            $q->store_result();
          ?>
            <?php if ($q->num_rows > 0):
              $q->bind_result($student_id);
              $sn = 0;

              $q1 = $db->prepare("select name from users where username = ?");
            ?>
              <h4> <b>List of students allocated to <?php echo $staff_name; ?></b> </h4>
              <table class="table table-responsive table-hover table-bordered">
                <thead>
                  <tr>
                    <th>SN</th>
                    <th>Student Name</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($q->fetch()):
                    ++$sn;
                    $q1->bind_param('s', $student_id);
                    $q1->execute();
                    $q1->bind_result($student_name);
                    $q1->fetch();
                  ?>
                    <tr>
                      <td> <?php echo $sn; ?></td>
                      <td> <?php echo $student_name; ?></td>
                    </tr>
                  <?php endwhile; $q1->close(); ?>
                </tbody>
              </table>
            <?php else: ?>
              <h4> <b>There are no students allocated to <?php echo $staff_name; ?></b> </h4>
            <?php endif; $q->close(); ?>

          <?php else: ?>
            <h3> <b>Staff not found, please check and try again!</b> </h3>
          <?php endif; $db->close(); ?>
        </div>
      </div>
    </div>

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
    header("Location:./");
    exit;
  ?>
  <?php endif; ?>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
