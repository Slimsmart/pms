<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])):
  include '../db.php';
  $q = $db->prepare("select count(id) from users where staff = ?");
  $staff = 0;
  $q->bind_param('s',$staff);
  $q->execute();
  $q->bind_result($number_of_students);
  $q->fetch();

  $staff = 1;
  $q->bind_param('s',$staff);
  $q->execute();
  $q->bind_result($number_of_staffs);
  $q->fetch();
  $q->close();

  $q = $db->prepare("select count(id) from projects where approved = ?");
  $approved = 1;
  $q->bind_param('i',$approved);
  $q->execute();
  $q->bind_result($number_of_projects);
  $q->fetch();
  $q->close();
  $db->close();
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
  <link rel="../shortcut icon" href="../img/favicon.ico">
  </head>
  <body>
  <!-- navbar -->
  <div class="navbar-wrapper">
  	<div class="navbar navbar-inverse navbar-fixed-top">
  		<div class="navbar-inner" style="background-color:#00000A8f">
  			<div class="container">
  				<div class="navbar-header">
  		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
  		        <span class="icon-bar"></span>
  		        <span class="icon-bar"></span>
  		        <span class="icon-bar"></span>
  		      </button>
  					<h4 class="brand navbar-brand" style=""><a href="#">Project Management System</a></h4>
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

  <section class="spacer blue mtb30"  style="margin-top:50px">
  <div class="container">
  	<div class="row">
  		<div class="span6 aligncenter" style="">
        <div class="panel">
  				<div class="panel-heading">
    				<h3><b>System statistics</b></h3>
  				</div>
  				<div class="panel-body">
            <table class="table table-bordered table-responsive" style="color:black">
              <thead>
                <tr>
                  <th class="aligncenter">Staffs</th>
                  <th class="aligncenter">Students</th>
                  <th class="aligncenter">Approved Projects</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="aligncenter"><?php echo $number_of_staffs; ?></td>
                  <td class="aligncenter"><?php echo $number_of_students; ?></td>
                  <td class="aligncenter"><?php echo $number_of_projects; ?></td>
                </tr>
              </tbody>
            </table>
  				</div>
        </div>
  		</div>
      <div class="span5">
        <div class="panel panel-default ">
          <form class="" action="add-topic.php" method="post" enctype="multipart/form-data">
            <div class="panel-heading aligncenter">
              <h4> <b>Submit project topic proposal</b> </h4>
            </div>
            <?php if (isset($_SESSION['errmsg'])): ?>
              <span style="color:red; text-align: center;" class="aligncenter" >
                <?php echo htmlentities($_SESSION['errmsg']); ?>
              </span>
            <?php endif; unset($_SESSION['errmsg'])?>

            <?php if (isset($_SESSION['msg'])): ?>
              <span style="color:lightgreen; text-align: center;" class="aligncenter">
                <?php echo htmlentities($_SESSION['msg']); ?>
              </span>
            <?php endif; unset($_SESSION['msg'])?>
            <div class="panel-body">
              <div class="form-group">
                <label for="topic">Project topic</label>
                <textarea required name="topic" id="topic" class="form-control" rows="2" placeholder="Enter project topic here.."></textarea>
              </div>
              <div class="form-group">
                <label for="abstract">Upload Abstract</label>
                <input type="file" name="abstract" required class="form-control">
              </div>
            </div>
            <div class="panel-footer">
              <input type="submit" name="ab" value="Submit" class="btn btn-primary">
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
