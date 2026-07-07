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
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/font-awesome.css">
  <!-- skin color -->
  <link href="../color/default.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/favicon.ico">
  
<link rel="stylesheet" href="/pms/css/student-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>.spacer { padding-top: 100px; padding-bottom: 60px; }</style>
</head>
  <body>
  <!-- navbar -->
  <div class="navbar-wrapper">
  	<div class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm" style="background: rgba(15, 23, 42, 0.85) !important; backdrop-filter: blur(12px);">
  		<div class="container">
  			<div class="navbar-header d-flex align-items-center w-100 justify-content-between">
  				<h3 class="navbar-brand fw-bold mb-0" style="margin:0;"><a href="/" style="color: white; text-decoration: none;">STUDENT</a></h3>
  		      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNavbar">
  		        <span class="navbar-toggler-icon"></span>
  		      </button>
  		    </div>
  			<div class="collapse navbar-collapse" id="myNavbar">
  		      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
  		        <li class="nav-item"><a class="nav-link text-white me-3" href="preferences.php"><i class="fa-solid fa-list-ol"></i> Preferences</a></li><li class="nav-item"><a class="nav-link text-white" href="logout.php"><i class="fa-solid fa-lock"></i> Logout</a></li>
  		      </ul>
  		    </div>
  		</div>
  	</div>
  </div>

  <section class="spacer">
  <div class="container">
    <?php if ($n < 1): ?>
      <div class="glass-card text-center py-5">
        <i class="fa-solid fa-user-clock fa-4x mb-3" style="color: var(--warning);"></i>
        <h3>Awaiting Supervisor Assignment</h3>
        <p class="text-muted">You haven't been assigned a supervisor yet. Please check back later.</p>
      </div>
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
    
    <div class="dashboard-hero text-center text-md-start">
      <h2>Welcome back, <?php echo htmlspecialchars($student_name); ?>! 👋</h2>
      <p>Track your project progress and submit chapters for review.</p>
    </div>

    <div class="row gx-lg-5">
      <!-- Left Column: Supervisor Details & Submit Form -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="glass-card">
          <div class="glass-card-header">
            <i class="fa-solid fa-user-tie"></i>
            <h4>Supervisor Details</h4>
          </div>
          <div class="supervisor-details">
            <p><i class="fa-solid fa-user"></i> <b>Name:</b> <?php echo htmlspecialchars($supervisor_name); ?></p>
            <p><i class="fa-solid fa-phone"></i> <b>Phone:</b> <?php echo htmlspecialchars($supervisor_number); ?></p>
          </div>
        </div>

        <div class="glass-card">
          <?php if ($project_approved == 1): ?>
            <form action="submit-chapter.php" method="post" enctype="multipart/form-data">
              <div class="glass-card-header">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <h4>Submit Chapter</h4>
              </div>
              <?php if (isset($_SESSION['errmsg'])): ?>
                <div class="alert alert-danger py-2 px-3 rounded-3" style="font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlentities($_SESSION['errmsg']); ?>
                </div>
              <?php endif; unset($_SESSION['errmsg'])?>

              <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success py-2 px-3 rounded-3" style="font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-check"></i> <?php echo htmlentities($_SESSION['msg']); ?>
                </div>
              <?php endif; unset($_SESSION['msg'])?>
              
              <div class="modern-form-group">
                <label for="chapter">Select Chapter</label>
                <select class="modern-input" name="chapter" required>
                  <option value="1">Chapter 1</option>
                  <option value="2">Chapter 2</option>
                  <option value="3">Chapter 3</option>
                  <option value="4">Chapter 4</option>
                  <option value="5">Chapter 5</option>
                  <option value="6">Full Project</option>
                </select>
              </div>
              <div class="modern-form-group">
                <label for="abstract">Upload Document</label>
                <input type="file" name="file" required class="modern-input" style="padding: 0.5rem 1rem;">
              </div>
              <input type="hidden" name="projectid" value="<?php echo $projectId; ?>">
              <button type="submit" name="pcb" class="modern-btn mt-3">
                <i class="fa-solid fa-paper-plane"></i> Submit Chapter
              </button>
            </form>
          <?php else: ?>
            <form action="submit-topic.php" method="post" enctype="multipart/form-data">
              <div class="glass-card-header">
                <i class="fa-solid fa-lightbulb"></i>
                <h4>Submit Proposal</h4>
              </div>
              <?php if (isset($_SESSION['errmsg'])): ?>
                <div class="alert alert-danger py-2 px-3 rounded-3" style="font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlentities($_SESSION['errmsg']); ?>
                </div>
              <?php endif; unset($_SESSION['errmsg'])?>

              <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success py-2 px-3 rounded-3" style="font-size: 0.9rem;">
                  <i class="fa-solid fa-circle-check"></i> <?php echo htmlentities($_SESSION['msg']); ?>
                </div>
              <?php endif; unset($_SESSION['msg'])?>
              
              <div class="modern-form-group">
                <label for="topic">Project Topic</label>
                <textarea required name="topic" id="topic" class="modern-input" rows="3" placeholder="Enter project topic here.."></textarea>
              </div>
              <div class="modern-form-group">
                <label for="abstract">Upload Abstract</label>
                <input type="file" name="abstract" required class="modern-input" style="padding: 0.5rem 1rem;">
              </div>
              <button type="submit" name="pp" class="modern-btn mt-3">
                <i class="fa-solid fa-paper-plane"></i> Submit Proposal
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- Right Column: Project Progress Table -->
      <div class="col-lg-8">
        <div class="glass-card" style="padding: 2rem;">
          <div class="glass-card-header mb-4">
            <i class="fa-solid fa-bars-progress"></i>
            <h4>Project Progress</h4>
          </div>
          
          <?php if ($np > 0):
            $x = 0; $q->bind_result($projectId, $topic, $abstract, $approved, $comment); ?>
            
            <div class="modern-grid">
              <div class="modern-grid-header d-none d-md-grid">
                <div>#</div>
                <div>Topic/Chapter</div>
                <div>Status</div>
                <div class="text-md-end">Action</div>
              </div>
              
              <?php while ($q->fetch()): $x++;?>
                <div class="modern-grid-row">
                  <div class="grid-col-id d-none d-md-block"><?php echo $x; ?></div>
                  <div class="grid-col-topic">
                    <span class="d-md-none text-muted mb-1" style="font-size: 0.8rem; font-weight: 700;">PROPOSAL</span>
                    <?php echo htmlspecialchars($topic); ?>
                    <?php if (!empty($comment)): ?>
                      <small><i class="fa-solid fa-comment-dots"></i> <?php echo htmlspecialchars($comment); ?></small>
                    <?php endif; ?>
                  </div>
                  <div>
                    <?php 
                      if ($approved == "0"){
                        echo '<span class="status-badge pending"><i class="fa-regular fa-clock"></i> Pending</span>';
                      }
                      elseif ($approved == "1") {
                        echo '<span class="status-badge accepted"><i class="fa-solid fa-check"></i> Accepted</span>';
                      }
                      else {
                        echo '<span class="status-badge declined"><i class="fa-solid fa-xmark"></i> Declined</span>';
                      }
                    ?>
                  </div>
                  <div class="text-md-end mt-3 mt-md-0">
                    <a href="../uploads/<?php echo urlencode($abstract); ?>" target="_blank" class="action-link">
                      <i class="fa-regular fa-eye"></i> View
                    </a>
                  </div>
                </div>
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
                  
                  $statusClass = 'notsubmitted';
                  $statusText = 'Not Submitted';
                  $statusIcon = 'fa-solid fa-minus';
                  
                  if ($approved_chapter !== null) {
                    if ($approved_chapter == "-1"){
                      $statusClass = 'declined'; $statusText = 'Declined'; $statusIcon = 'fa-solid fa-xmark';
                    } elseif ($approved_chapter == "1") {
                      $statusClass = 'accepted'; $statusText = 'Accepted'; $statusIcon = 'fa-solid fa-check';
                    } else {
                      $statusClass = 'pending'; $statusText = 'Pending'; $statusIcon = 'fa-regular fa-clock';
                    }
                  }
                ?>
                  <div class="modern-grid-row">
                    <div class="grid-col-id d-none d-md-block"><?php echo ++$x; ?></div>
                    <div class="grid-col-topic">
                      <span class="d-md-none text-muted mb-1" style="font-size: 0.8rem; font-weight: 700;">CHAPTER <?php echo $i; ?></span>
                      <?php echo ($i==6) ? 'Full Project' : 'Chapter '.$i; ?>
                      <?php if (!empty($comments)): ?>
                        <small><i class="fa-solid fa-comment-dots"></i> <?php echo htmlspecialchars($comments); ?></small>
                      <?php endif; ?>
                    </div>
                    <div>
                      <span class="status-badge <?php echo $statusClass; ?>">
                        <i class="<?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                      </span>
                    </div>
                    <div class="text-md-end mt-3 mt-md-0">
                      <?php if ($approved_chapter == null): ?>
                        <span class="text-muted">-</span>
                      <?php else: ?>
                        <a href="../uploads/chapters/<?php echo urlencode($chapter_link); ?>" target="_blank" class="action-link">
                          <i class="fa-regular fa-file-pdf"></i> View
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php if ($approved_chapter != 1) break; ?>
                <?php endfor; $q0->close(); ?>
              <?php endif; ?>
            </div>
            
          <?php else: ?>
            <div class="text-center py-5">
              <i class="fa-solid fa-folder-open fa-4x mb-3" style="color: var(--text-muted); opacity: 0.5;"></i>
              <h5 class="text-muted fw-bold">No submissions yet</h5>
              <p class="text-muted mb-0">Submit your project proposal to get started.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
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
