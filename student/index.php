<?php session_start(); ?>

<?php if (isset($_SESSION['stupass'])):

include '../db.php';

$approved = "";
$studentid = $_SESSION['stupass'];

/* ---------- STUDENT NAME ---------- */
$q = $db->prepare("SELECT name FROM users WHERE username = ?");
$q->bind_param('s', $studentid);
$q->execute();
$q->bind_result($student_name);
$q->fetch();
$q->close();


/* ---------- ALLOCATION CHECK ---------- */
$stmtAlloc = $db->prepare(
    "SELECT staff FROM allocation WHERE student = ?"
);

$stmtAlloc->bind_param("s", $studentid);
$stmtAlloc->execute();
$stmtAlloc->store_result();

$n = $stmtAlloc->num_rows;

if ($n > 0) {
    $stmtAlloc->bind_result($staffid);
    $stmtAlloc->fetch();
}

$stmtAlloc->free_result();
$stmtAlloc->close();


/* ---------- PROJECT APPROVAL ---------- */
$q0 = $db->prepare("SELECT approved FROM projects WHERE username = ?");
$q0->bind_param('s', $studentid);
$q0->execute();          // ✅ execute FIRST
$q0->store_result();     // ✅ THEN store
$q0->bind_result($project_approved);
$q0->fetch();
$q0->free_result();      // ✅ prevent sync issue
$q0->close();

?>
  <!DOCTYPE HTML>
  <html lang="en">
  <head>
  <meta charset="utf-8">
  <title>project Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- css -->
  <link href="../css/bootstrap-responsive.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/font-awesome.css">
  <!-- skin color -->
  <link href="../color/default.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/favicon.ico">
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
  					<h3 class="brand navbar-brand" style=""><a href="/">STUDENT</a></h3>
  		    </div>
  				<!-- Responsive navbar -->

  				<!-- navigation -->
  				<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul id="menu-main" class="nav navbar-nav navbar-right">
  		        <li><a href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
  		      </ul>
  		    </div>
  			</div>
  		</div>
  	</div>
  </div>

  <section class="spacer blue mtb30"  style="color:black; margin-top:80px;">
  <div class="container-fluid">
    <div class="row">
      <div class="span10 offset1" style="">
        <div class="row-fluid">
            <?php if ($n < 1): ?>
              <p>You haven't been assigned a supervisor yet.</p>
            <?php else:
              $q = $db->prepare("select name, phone from users where username = ?");
              $q->bind_param('s',$staffid);
              $q->execute();
              $q->bind_result($supervisor_name, $supervisor_number);
              $q->fetch();
              $q->close();

              $q = $db->prepare("select id, topic, abstract, approved, comments from projects where username = ?");
              $q->bind_param('s',$studentid);
              $q->execute();
              $q->store_result();
              $np = $q->num_rows;
            ?>
            <div class="span4">
              <p>Welcome <?php echo $student_name; ?> </p>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <b>Supervisor Details</b>
                </div>
                <div class="panel-body">
                  <p> <b>Name:</b> <?php echo "$supervisor_name"; ?> </p>
                  <p> <b>Phone:</b> <?php echo "$supervisor_number"; ?> </p>
                </div>
              </div>
            </div>
            <div class="span8">
              <?php if ($np > 0):
                $x = 0; $q->bind_result($projectId, $topic, $abstract, $approved, $comment); ?>
                <table class="table table-bordered table-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Topic/Chapter</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($q->fetch()): $x++;?>
                      <tr>
                        <td> <?php echo $x; ?> </td>
                        <td> <?php echo $topic; ?> </td>
                        <td>
                          <?php if ($approved == "0"){
                                  echo "Pending";
                                }
                                elseif ($approved == "1") {
                                  echo "Accepted";
                                }
                                else {
                                  echo "Declined";
                                }
                          ?>
                        </td>
                        <td> <a href="../uploads/<?php echo $abstract; ?>" target="_blank">View</a> </td>
                      </tr>
                      <tr>
                        <td colspan="4"> <?php echo $comment; ?></td>
                      </tr>
                    <?php endwhile; ?>
                    <?php if ($approved == '1'):
                      $q0 = $db->prepare("select approved, link, comment from chapters where username = ? && chapter = ?");
                    ?>
                      <?php for ($i=1; $i < 7; $i++):
                        $q0->bind_param('ss',$studentid,$i);
                        $q0->execute();
                        $q0->store_result();
                        $q0->bind_result($approved_chapter, $chapter_link, $comments);
                        $q0->fetch();
                      ?>
                        <tr>
                          <td> <?php echo ++$x; ?> </td>
                          <td> <b>Chapter <?php echo $i; ?> </b>  </td>
                          <td>
                            <?php
                              if ($approved_chapter == null) {
                                echo "Not submitted";
                              }
                              elseif ($approved_chapter == "-1"){
                                echo "Declined";
                              }
                              elseif ($approved_chapter == "1") {
                                echo "Accepted";
                              }
                              else {
                                echo "Pending";
                              }
                            ?>
                          </td>
                          <td>
                            <?php if ($approved_chapter == null): ?>
                              -
                            <?php else: ?>
                              <a href="../uploads/chapters/<?php echo $chapter_link; ?>" target="_blank">View</a>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="4"> <?php echo $comments; ?> </td>
                        </tr>
                      <?php if ($approved_chapter != 1) break; ?>

                    <?php endfor; $q0->close(); ?>
                  <?php endif; ?>
                  </tbody>
                </table>
              <?php else: ?>
                <h4 class="aligncenter">No previous submition has been made.</h4>
              <?php endif; ?>
              <div class="panel">
                <?php if ($approved == 1): ?>
                  <form class="" action="submit-chapter.php" method="post" enctype="multipart/form-data">
                    <div class="panel-heading aligncenter">
                      <h4> <b>Submit project (chapter) for assessment</b> </h4>
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
                        <label for="topic">Select Chapter</label>
                        <select class="form-control" name="chapter" required>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">Full Project</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="abstract">Upload</label>
                        <input type="file" name="file" required class="form-control">
                      </div>
                      <input type="hidden" name="projectid" value="<?php echo $projectId; ?>">
                    </div>
                    <div class="panel-footer">
                      <input type="submit" name="pcb" value="Submit" class="btn btn-primary">
                    </div>
                  </form>
                <?php else: ?>
                  <form class="" action="submit-topic.php" method="post" enctype="multipart/form-data">
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
                      <input type="submit" name="pp" value="Submit" class="btn btn-primary">
                    </div>
                  </form>
                <?php endif; ?>

              </div>
            </div>
            <?php endif; ?>
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
