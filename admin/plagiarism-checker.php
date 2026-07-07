<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])):
  include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Plagiarism Checker - Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pms/css/modern-theme.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; } 
  .spacer { padding-top: 100px; padding-bottom: 40px; }
  .plagiarism-btn { padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 8px; }
  .score-badge { padding: 6px 12px; border-radius: 20px; font-weight: 600; color: white; display: inline-block; min-width: 120px; text-align: center; }
  .score-safe { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
  .score-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
  .score-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
  .score-none { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }
</style>
</head>
<body>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">
            <i class="fa-solid fa-shield-halved text-primary me-2"></i>Admin Portal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="home.php"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="plagiarism-checker.php"><i class="fa-solid fa-magnifying-glass me-1"></i> Plagiarism Check</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="fa-solid fa-user-plus me-1"></i> Register</a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="add-student.php">Register Student</a></li>
                        <li><a class="dropdown-item py-2" href="add-staff.php">Register Staff</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="fa-solid fa-eye me-1"></i> View</a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="view-students.php">View Students</a></li>
                        <li><a class="dropdown-item py-2" href="view-staffs.php">View Staff</a></li>
                    </ul>
                </li>
                
                <li class="nav-item"><a class="nav-link" href="allocate.php"><i class="fa-solid fa-link me-1"></i> Allocate</a></li>
                <li class="nav-item ms-lg-3"><a class="btn btn-outline-danger rounded-pill px-4" href="logout.php"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="spacer">
<div class="container">
    
  <div class="row mb-4">
      <div class="col-12">
          <h2 class="fw-bold" style="color: #0f172a;">Plagiarism Checker</h2>
          <p class="text-muted">Analyze submitted chapters against online sources.</p>
      </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="color:black">
              <thead style="background: #f8fafc;">
                <tr>
                  <th class="ps-4 py-3 text-uppercase text-secondary text-sm">Student</th>
                  <th class="py-3 text-uppercase text-secondary text-sm">Project Topic</th>
                  <th class="py-3 text-uppercase text-secondary text-sm">Chapter</th>
                  <th class="py-3 text-uppercase text-secondary text-sm">Score</th>
                  <th class="pe-4 py-3 text-uppercase text-secondary text-sm text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $q = $db->prepare("SELECT c.id, c.chapter, c.plagiarism_score, p.topic, u.name 
                                   FROM chapters c 
                                   JOIN projects p ON c.project_id = p.id 
                                   JOIN users u ON c.username = u.username 
                                   ORDER BY c.id DESC");
                $q->execute();
                $q->bind_result($chap_id, $chapter_num, $score, $topic, $student_name);
                
                while($q->fetch()):
                  $badge_class = 'score-none';
                  $score_text = 'Not Checked';
                  
                  if ($score !== null) {
                      $score_text = $score . '% Duplication';
                      if ($score < 15) $badge_class = 'score-safe';
                      elseif ($score < 40) $badge_class = 'score-warning';
                      else $badge_class = 'score-danger';
                  }
                ?>
                <tr>
                  <td class="ps-4 py-3 fw-medium"><?php echo htmlspecialchars($student_name); ?></td>
                  <td class="py-3 text-muted"><?php echo htmlspecialchars(substr($topic, 0, 50)) . '...'; ?></td>
                  <td class="py-3"><span class="badge bg-light text-dark border">Chapter <?php echo htmlspecialchars($chapter_num); ?></span></td>
                  <td class="py-3" id="score-td-<?php echo $chap_id; ?>">
                      <span class="score-badge <?php echo $badge_class; ?>"><?php echo $score_text; ?></span>
                  </td>
                  <td class="pe-4 py-3 text-end">
                      <button class="btn btn-primary plagiarism-btn shadow-sm" onclick="checkPlagiarism(<?php echo $chap_id; ?>)" id="btn-<?php echo $chap_id; ?>">
                          <i class="fa-solid fa-arrows-rotate me-1"></i> Scan
                      </button>
                  </td>
                </tr>
                <?php endwhile; $q->close(); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<footer class="text-center py-4 text-muted mt-5">
    <div class="container">
        <small>&copy; <?php echo date('Y'); ?> Project Duplication Detection System. All rights reserved.</small>
    </div>
</footer>

<script src="../js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function checkPlagiarism(chapterId) {
    var btn = $('#btn-' + chapterId);
    var td = $('#score-td-' + chapterId);
    
    btn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Scanning...');
    btn.prop('disabled', true);
    
    $.ajax({
        url: 'check-online.php',
        type: 'POST',
        data: { chapter_id: chapterId },
        success: function(response) {
            try {
                var res = JSON.parse(response);
                if(res.status == 'success') {
                    var score = res.score;
                    var badgeClass = 'score-safe';
                    if(score >= 40) badgeClass = 'score-danger';
                    else if(score >= 15) badgeClass = 'score-warning';
                    
                    td.html('<span class="score-badge ' + badgeClass + '">' + score + '% Duplication</span>');
                } else {
                    alert('Error: ' + res.message);
                }
            } catch(e) {
                alert('Invalid server response.');
            }
            btn.html('<i class="fa-solid fa-arrows-rotate me-1"></i> Scan');
            btn.prop('disabled', false);
        },
        error: function() {
            alert('Failed to connect to server.');
            btn.html('<i class="fa-solid fa-arrows-rotate me-1"></i> Scan');
            btn.prop('disabled', false);
        }
    });
}
</script>
</body>
</html>
<?php 
  $db->close();
else:
  header("Location:logout.php");
  exit;
endif; 
?>
