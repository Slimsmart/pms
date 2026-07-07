<?php session_start(); ?>
<?php if (isset($_SESSION['pass'])): ?>
  <!DOCTYPE HTML>
  <html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Register Staff - Admin Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f8f9fa; 
    }
    .spacer { 
        padding-top: 100px; 
        padding-bottom: 40px; 
    }
    .navbar {
        background-color: #0f172a !important;
    }
    .card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }
    .form-floating input {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
    }
    .form-floating input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    .btn-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
    }
  </style>
</head>
  <body>
  
  <!-- Navigation Header -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
      <div class="container">
          <a class="navbar-brand fw-bold" href="home.php">
              <i class="fa-solid fa-user-gear text-primary me-2"></i>ADMIN PORTAL
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#myNavbar" aria-controls="myNavbar" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                  <li class="nav-item"><a class="nav-link" href="home.php"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa-solid fa-user-plus me-1"></i> Register
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                          <li><a class="dropdown-item" href="add-student.php">Register Student</a></li>
                          <li><a class="dropdown-item" href="add-staff.php">Register Staff</a></li>
                      </ul>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa-solid fa-eye me-1"></i> View Records
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                          <li><a class="dropdown-item" href="view-students.php">Students List</a></li>
                          <li><a class="dropdown-item" href="view-staffs.php">Staff List</a></li>
                      </ul>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="allocate.php"><i class="fa-solid fa-circle-nodes me-1"></i> Allocate</a></li>
                  <li class="nav-item ms-2"><a class="btn btn-outline-light btn-sm rounded-pill px-3" href="logout.php"><i class="fa-solid fa-lock me-1"></i> Logout</a></li>
              </ul>
          </div>
      </div>
  </nav>

  <section class="spacer mtb30">
  <div class="container">
  	<div class="row justify-content-center">
  		<div class="col-md-6">
        <div class="card p-4">
          <form method="post" action="add-staff-script.php">
    						<div class="border-bottom-0 pb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3 text-primary">
                                        <i class="fa-solid fa-user-tie fa-2x"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0 fw-bold text-slate-800">Register Staff</h3>
                                        <p class="text-muted mb-0 small">Create a new supervisor account</p>
                                    </div>
                                </div>
    						</div>
                            
                            <?php if (isset($_SESSION['errmsg'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mt-2" role="alert">
                                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                                    <?php echo htmlentities($_SESSION['errmsg']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php unset($_SESSION['errmsg']); endif; ?>
                            
    						<div class="card-body px-0 py-3">
    							<div class="form-floating mb-4">
    								<input class="form-control" type="text" id="staffid" name="staffid" placeholder="Staff Id" autofocus required>
                                    <label for="staffid"><i class="fa-solid fa-id-card me-2 text-muted"></i>Staff ID (Login Username)</label>
    							</div>
    							<div class="form-floating mb-4">
    						        <input class="form-control" type="text" id="name" name="name" placeholder="Full Name" required>
                                    <label for="name"><i class="fa-solid fa-user me-2 text-muted"></i>Full Name</label>
    							</div>
                                <div class="form-floating mb-4">
    						        <input class="form-control" type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                                    <label for="phone"><i class="fa-solid fa-phone me-2 text-muted"></i>Phone Number</label>
    							</div>
    						</div>
    						<div class="border-top-0 pt-2 d-flex justify-content-end">
    							<button type="submit" class="btn-submit" name="addsf">
                                    Register Staff <i class="fa-solid fa-plus ms-2"></i>
                                </button>
    						</div>
    					</form>
        </div>
  		</div>
  	</div>
  </div>
  </section>

  <footer class="text-center py-4 text-muted bg-white border-top mt-5">
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
