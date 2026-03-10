<?php session_start(); ?>
<?php if (isset($_SESSION['stapass'])): ?>
<?php if (isset($_GET['student_id'])):
  $id = $_GET['student_id'];

  $staffid = $_SESSION['stapass'];
  include '../db.php';
  $q = $db->prepare("select name from users where username = ?");
  $q->bind_param('s',$id);
  $q->execute();
  $q->bind_result($name);
  $q->fetch();
  $q->close();

  $q = $db->prepare("select id, topic, abstract, approved from projects where username = ?");
  $q->bind_param('s',$id);
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
          <h3 class="brand navbar-brand" style=""><a href="#">Project Management System</a></h3>
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

<section class="spacer blue mtb30" style="margin-top:50px;">
<div class="container">
  <div class="row">
    <div class="span10 offset1 aligncenter" style="">
        <h3><b> <?php echo "$name topic(s)"; ?> </b></h3>
        <?php if ($n > 0): $q->bind_result($topicid, $topic, $abstract, $approved); $x = 0; ?>
          <table class="table table-bordered table-responsive" style="color:black">
            <thead>
              <tr>
                <th class="aligncenter">#</th>
                <th class="aligncenter">Topic</th>
                <th class="aligncenter">Abstract</th>
                <th class="aligncenter">Status</th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php while ($q->fetch()):
                $x++;
                $disabled = "disabled";
                if($approved == "0"){
                  $status = "Pending";
                  $disabled = "";
                }
                elseif ($approved == "1") {
                  $status = "Approved";
                }
                else {
                  $status = "Declined";
                }
              ?>
              <tr>
                <td> <?php echo $x; ?> </td>
                <td class="aligncenter"><?php echo $topic; ?></td>
                <td class="aligncenter"> <a href="../uploads/<?php echo $abstract; ?>" target="_blank">View</a></td>
                <td class="aligncenter"><?php echo $status; ?></td>
                <td><a href="approve-topic.php?topic_id=<?php echo $topicid; ?>&student_id=<?php echo $id; ?>" class="btn btn-success <?php echo $disabled; ?>">Approve</a></td>
                <td><a href="decline-topic.php?topic_id=<?php echo $topicid; ?>&student_id=<?php echo $id; ?>" class="btn btn-danger <?php echo $disabled; ?>">Decline</a></td>
                <td> <a href="view-related-topics.php?project_id=<?php echo $topicid; ?>&q=<?php echo $topic; ?>" target="_blank">View related topics</a> </td>
              </tr>
            <?php endwhile; $q->close(); $db->close(); ?>
            </tbody>
          </table>
        <?php else: ?>
          <h4>This student hasn't submited any topic.</h4>
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
  header("Location:index.php");
  exit;
?>
<?php endif; ?>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
