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

  $approved= 1;

  $q = $db->prepare("select id, topic, abstract, approved from projects where username = ? && approved = ?");
  $q->bind_param('ss',$id,$approved);
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
          <h3 class="navbar-brand fw-bold" style=""><a href="#">Project Management System</a></h3>
        </div>
        <!-- Responsive navbar -->

        <!-- navigation -->
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
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
    <div class="col-md-10 offset1 aligncenter" style="">
        <h3><b> <?php echo "$name PROJECT"; ?> </b></h3>
        <?php if ($n > 0):
          $q->bind_result($topicid, $topic, $abstract, $approved);
          $q->fetch();
          $q->close();

          $q0 = $db->prepare("select id, chapter, approved, link, comment from chapters where username = ?");
          $q0->bind_param('s',$id);
          $q0->execute();
          $q0->store_result();
        ?>
          ifelse
          <h4> <?php echo $topic; ?> </h4>
          <table class="table table-bordered table-responsive" style="color:black">
            <?php if ($q0->num_rows > 0):
              $x = 0;
              $q0->bind_result($chapterId, $chapter, $approved, $link, $comment);
            ?>
              <thead>
                <tr>
                  <th class="aligncenter">#</th>
                  <th class="aligncenter">Chapter</th>
                  <th class="aligncenter">Status</th>
                  <th></th>
                  <th colspan="2"></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php while ($q0->fetch()):
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
                  <td> <?php echo ++$x; ?> </td>
                  <td class="aligncenter"> <b> <?php echo $chapter; ?> </b> </td>

                  <td class="aligncenter"><?php echo $status; ?></td>
                  <td class="aligncenter"> <a href="../uploads/chapters/<?php echo $link; ?>" target="_blank">View</a></td>
                  <td><a href="approve-chapter.php?chapter_id=<?php echo $chapterId; ?>&student_id=<?php echo $id; ?>" class="btn btn-success <?php echo $disabled; ?>">Approve</a></td>
                  <td><a href="decline-chapter.php?chapter_id=<?php echo $chapterId; ?>&student_id=<?php echo $id; ?>" class="btn btn-danger <?php echo $disabled; ?>">Decline</a></td>
                  <td class="aligncenter"> <a href="check_chapter.php?id=<?php echo $chapterId; ?>" target="_blank">Check for plagiarism</a></td>
                </tr>
              <?php endwhile; $q0->close(); $db->close(); ?>
              </tbody>
            <?php else: ?>

            <?php endif; ?>

          </table>
        <?php else: ?>
          <h4>This student hasn't submited any topic that has been approved yet.</h4>
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
  header("Location:index.php");
  exit;
?>
<?php endif; ?>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
