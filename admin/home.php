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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - Project Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; } .spacer { padding-top: 100px; padding-bottom: 40px; }</style>
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
                <li class="nav-item"><a class="nav-link active" href="home.php"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="plagiarism-checker.php"><i class="fa-solid fa-magnifying-glass me-1"></i> Plagiarism Check</a></li>
                
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
    
    <!-- Welcome Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold" style="color: #0f172a;">Welcome back, Admin 👋</h2>
            <p class="text-muted">Here's what's happening with your projects today.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Statistics Widget -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-chart-pie me-2"></i>System Overview</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 text-center">
                        <div class="col-sm-4">
                            <div class="p-4 rounded-4" style="background: rgba(59,130,246,0.1);">
                                <i class="fa-solid fa-chalkboard-user fa-2x text-primary mb-3"></i>
                                <h3 class="fw-bold text-primary mb-0"><?php echo $number_of_staffs; ?></h3>
                                <p class="text-muted mb-0 small text-uppercase fw-semibold">Staff</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-4 rounded-4" style="background: rgba(16,185,129,0.1);">
                                <i class="fa-solid fa-user-graduate fa-2x text-success mb-3"></i>
                                <h3 class="fw-bold text-success mb-0"><?php echo $number_of_students; ?></h3>
                                <p class="text-muted mb-0 small text-uppercase fw-semibold">Students</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-4 rounded-4" style="background: rgba(245,158,11,0.1);">
                                <i class="fa-solid fa-folder-open fa-2x text-warning mb-3"></i>
                                <h3 class="fw-bold text-warning mb-0"><?php echo $number_of_projects; ?></h3>
                                <p class="text-muted mb-0 small text-uppercase fw-semibold">Projects</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Topic Widget -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 16px;">
                <form action="add-topic.php" method="post" enctype="multipart/form-data" class="h-100 d-flex flex-column">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-file-arrow-up me-2"></i>Quick Submit</h5>
                    </div>
                    
                    <div class="card-body p-4 flex-grow-1">
                        <?php if (isset($_SESSION['errmsg'])): ?>
                            <div class="alert alert-danger rounded-3 p-2 mb-3"><i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo htmlentities($_SESSION['errmsg']); ?></div>
                        <?php endif; unset($_SESSION['errmsg'])?>

                        <?php if (isset($_SESSION['msg'])): ?>
                            <div class="alert alert-success rounded-3 p-2 mb-3"><i class="fa-solid fa-circle-check me-1"></i> <?php echo htmlentities($_SESSION['msg']); ?></div>
                        <?php endif; unset($_SESSION['msg'])?>
                        
                        <div class="mb-3">
                            <label for="topic" class="form-label fw-semibold text-secondary">Project Topic</label>
                            <textarea required name="topic" id="topic" class="form-control" rows="2" placeholder="Enter topic proposal..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="abstract" class="form-label fw-semibold text-secondary">Upload Abstract (.docx/.pdf)</label>
                            <input type="file" name="abstract" required class="form-control form-control-lg bg-light">
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 pb-4 px-4">
                        <button type="submit" name="ab" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">Submit Proposal <i class="fa-solid fa-arrow-right ms-1"></i></button>
                    </div>
                </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php else:
  header("Location:logout.php");
  exit;
?>
<?php endif; ?>
