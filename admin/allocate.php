<?php
session_start();
if (!isset($_SESSION['pass'])):
  header("Location: logout.php");
  exit;
endif;

include '../db.php';

// Fetch staff members
$q = $db->prepare("select username, name, capacity from users where staff = ?");
$staff = 1;
$q->bind_param('s', $staff);
$q->execute();
$q->store_result();
$n = $q->num_rows;
$staffs = array();

// Handle messages
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$alert_message = "";
if ($msg == "allocated") {
    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle"></i> Students manually allocated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == "capacities_updated") {
    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle"></i> Supervisor capacities updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == "gale_shapley_run") {
    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-magic"></i> Gale-Shapley Stable Matching allocation completed successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Student Allocation - Admin - PMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/font-awesome.css">
  <link href="../color/default.css" rel="stylesheet">
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8f9fa;
    }
    .spacer {
        padding-top: 80px;
        padding-bottom: 40px;
    }
  </style>
</head>
<body>
  <!-- navbar -->
  <div class="navbar-wrapper">
    <div class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
      <div class="container">
        <div class="navbar-header">
          <h3 class="navbar-brand fw-bold mb-0"><a href="home.php">ADMIN Dashboard</a></h3>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="home.php"><span class="fa fa-home"></span> Home</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"><span class="fa fa-plus"></span> Register</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="add-student.php">Student</a></li>
                <li><a class="dropdown-item" href="add-staff.php">Staff</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"><span class="fa fa-eye"></span> View</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="view-students.php">Student</a></li>
                <li><a class="dropdown-item" href="view-staffs.php">Staff</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link active" href="allocate.php">Allocate</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <section class="spacer">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-11">
          <h2 class="mb-4 fw-bold text-center"><i class="fa fa-link text-primary"></i> Supervisor Student Allocations</h2>
          
          <?php echo $alert_message; ?>

          <!-- Gale-Shapley Auto-Allocation Section -->
          <div class="glass-card mb-5 p-4 text-center">
              <h4 class="fw-bold mb-2"><i class="fa fa-wand-magic-sparkles text-warning me-2"></i>Automated Matching Engine</h4>
              <p class="text-muted mx-auto" style="max-width: 700px;">
                  Instantly run the <strong>Gale-Shapley Stable Matching algorithm</strong> to align student preferences with supervisor choices and capacities. Running this will automatically rebuild all student assignments based on preferences.
              </p>
              <form action="allocate-script.php" method="post" onsubmit="return confirm('Are you sure you want to run the automated Gale-Shapley allocation? This will overwrite all current supervisor assignments.');">
                  <button type="submit" name="run-gale-shapley" class="btn btn-warning px-4 py-2 fw-semibold">
                      <i class="fa fa-cogs me-1"></i> Run Gale-Shapley Auto-Allocation
                  </button>
              </form>
          </div>

          <div class="row">
            <!-- Left Column: Staff capacity & allocations -->
            <div class="col-lg-6 mb-4">
              <div class="glass-card p-3">
                <h4 class="mb-3 fw-bold"><i class="fa fa-users text-secondary me-2"></i>Supervisor Capacities</h4>
                <?php if ($n < 1): ?>
                  <div class="alert alert-warning">There are no staff members registered on the system!</div>
                <?php else: $q->bind_result($staffid, $staffname, $capacity); ?>
                  <form action="allocate-script.php" method="post">
                    <div class="table-responsive">
                      <table class="table align-middle">
                        <thead class="table-light">
                          <tr>
                            <th>Supervisor</th>
                            <th class="text-center">Allocated</th>
                            <th style="width: 110px;" class="text-center">Capacity</th>
                            <th>Action</th>
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
                            <td>
                              <span class="fw-semibold"><?php echo htmlspecialchars($staffname); ?></span><br>
                              <small class="text-muted"><?php echo htmlspecialchars($staffid); ?></small>
                            </td>
                            <td class="text-center">
                              <span class="badge <?php echo ($number_of_students >= $capacity) ? 'bg-danger' : 'bg-primary'; ?>">
                                  <?php echo $number_of_students; ?>
                              </span>
                            </td>
                            <td>
                              <input type="number" min="1" name="capacities[<?php echo htmlspecialchars($staffid); ?>]" value="<?php echo htmlspecialchars($capacity); ?>" class="form-control form-control-sm text-center">
                            </td>
                            <td> 
                              <a href="project-students.php?staffid=<?php echo htmlspecialchars($staffid); ?>" class="btn btn-outline-primary btn-sm">
                                  <i class="fa fa-eye"></i> View
                              </a> 
                            </td>
                          </tr>
                        <?php endwhile;?>
                          <?php $q->free_result(); $q->close(); ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-end mt-2">
                        <button type="submit" name="update-capacities" class="btn btn-secondary btn-sm">
                            <i class="fa fa-save me-1"></i> Save Capacities
                        </button>
                    </div>
                  </form>
                <?php endif; ?>
              </div>
            </div>

            <!-- Right Column: Manual Student allocation -->
            <div class="col-lg-6 mb-4">
              <div class="glass-card p-3">
                <h4 class="mb-3 fw-bold"><i class="fa fa-plus-circle text-secondary me-2"></i>Manual Allocation</h4>
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
                  <div class="alert alert-info">There are no registered students on the system.</div>
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
                    <form class="mt-2" action="allocate-script.php" method="post">
                      <p class="text-muted small mb-3">Select the students you want to assign, and pick a supervisor:</p>
                      <div class="mb-3 p-3 bg-light rounded" style="max-height: 250px; overflow-y: auto;">
                        <?php foreach ($unstudents as $key => $value): ?>
                          <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($key); ?>" name="<?php echo htmlspecialchars($key); ?>" id="chk_<?php echo htmlspecialchars($key); ?>">
                            <label class="form-check-label" for="chk_<?php echo htmlspecialchars($key); ?>">
                              <?php echo htmlspecialchars($value); ?> <small class="text-muted">(<?php echo htmlspecialchars($key); ?>)</small>
                            </label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <div class="mb-3">
                        <select class="form-select" name="staff" required>
                          <option value="">-- Choose Supervisor --</option>
                          <?php foreach ($staffs as $key => $value): ?>
                            <option value="<?php echo htmlspecialchars($key); ?>"> <?php echo htmlspecialchars($value); ?> </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="text-end">
                        <button type="submit" name="allocate-form" class="btn btn-primary">
                            <i class="fa fa-link me-1"></i> Assign Selected
                        </button>
                      </div>
                    </form>
                  <?php else: ?>
                    <div class="alert alert-success mb-0"><i class="fa fa-info-circle"></i> All registered students have been assigned supervisors.</div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container text-center">
      <p class="copyright">&copy; <?php echo date('Y'); ?>. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
