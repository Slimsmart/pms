<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])):
  include '../db.php';
  $q = $db->prepare("select username, name from users where staff = ?");
  $staff = 1;
  $q->bind_param('s',$staff);
  $q->execute();
  $q->store_result();
  $n = $q->num_rows;
  $staffs = array();
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

  <section class="spacer blue mtb30" style="margin-top:30px">
  <div class="container">
  	<div class="row">
  		<div class="span11 offset1 aligncenter" style="">
    		<h3><b>Student allcation made</b></h3>
        <?php if ($n < 1): ?>
          <h3>There are no staffs on the system to allocate students to!!!</h3>
        <?php else: $q->bind_result($staffid, $staffname); ?>
          <div class="row-fluid">
            <div class="span6">
              <table class="table table-bordered table-responsive" style="color:black">
                <thead>
                  <tr>
                    <th class="aligncenter">Staff Name</th>
                    <th class="aligncenter" colspan="2">Number of students allocated to staff</th>

                  </tr>
                </thead>
                <tbody>
                  <?php while ($q->fetch()):
                    $staffs["$staffid"] = $staffname;
                    $q1 = $db->prepare("select count(id) from allocation where staff = ?");
                    $q1->bind_param('s', $staffid);
                    $q1->execute();
                    $q1->bind_result($number_of_students);
                    $q1->fetch();
                    $q1->free_result();
                    $q1->close();
                  ?>
                  <tr>
                    <td class="aligncenter"><?php echo $staffname; ?></td>
                    <td class="aligncenter"><?php echo $number_of_students; ?></td>
                    <td> <a href="project-students.php?staffid=<?php echo $staffid; ?>">View students</a> </td>
                  </tr>
                <?php endwhile;?>
                  <?php $q->free_result(); $q->close(); ?>
                </tbody>
              </table>
            </div>
            <div class="span6">
              <?php
                $unstudents = array();
                $q = $db->prepare("select name, username from users where staff = ?");
                $staff = 0;
                $q->bind_param('s',$staff);
                $q->execute();
                $q->store_result();
                $n = $q->num_rows;
              ?>
              <?php if ($n < 1): ?>
                <h5>There are no Registered students on the system.</h5>
              <?php else:
                $q->bind_result($studentname, $studentid);
                while ($q->fetch()){
                  $q0 = $db->prepare("select count(id) from allocation where student = ?");
                  $q0->bind_param('s',$studentid);
                  $q0->execute();
                  $q0->bind_result($student_allocated);
                  $q0->fetch();
                  $q0->free_result();
                  $q0->close();
                  if ($student_allocated < 1) {
                    $unstudents["$studentid"] = $studentname;
                  }
                }
                $q->close();
                $db->close();
              ?>
                <?php if (count($unstudents) > 0): ?>
                  <form class="" action="allocate-script.php" method="post">
                    <?php foreach ($unstudents as $key => $value): ?>
                      <div class="checkbox alignleft">
                        <label><input type="checkbox" value="<?php echo "$key"; ?>" name="<?php echo "$key"; ?>"> <?php echo "$value"; ?> </label>
                      </div>
                    <?php endforeach; ?>
                    <div class="form-group">
                      <select class="form-control" name="staff" required>
                        <option value="">Select staff</option>
                        <?php foreach ($staffs as $key => $value): ?>
                          <option value="<?php echo "$key"; ?>"> <?php echo "$value"; ?> </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="submit" name="allocate-form" value="Assign" class="btn btn-primary">
                    </div>
                  </form>
                <?php else: ?>
                  <h5>All students have been assigned project supervisors.</h5>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
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
