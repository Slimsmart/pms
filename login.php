<?php
	session_start();
	if (isset($_SESSION['stupass'])) {
		header("Location:student/");
		exit;
	}
	elseif (isset($_SESSION['stapass'])) {
		header("Location:staff/");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Project Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=2000&q=80') no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(59,130,246,0.8) 100%);
        z-index: 1;
    }
    .login-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 450px;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        padding: 40px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .login-header h2 {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
    }
    .form-floating input {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
    }
    .form-floating input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    .back-link:hover {
        color: #3b82f6;
    }
  </style>
</head>
<body>

<div class="overlay"></div>

<div class="container login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="mb-3">
                <i class="fa-solid fa-shield-halved fa-3x" style="color: #3b82f6;"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="text-muted">Sign in to the Duplication Detection System</p>
        </div>

        <?php if (isset($_SESSION['errmsg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlentities($_SESSION['errmsg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['errmsg']); endif; ?>

        <form method="post" action="auth.php">
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Username" required autofocus>
                <label for="inputUsername"><i class="fa-regular fa-user me-2 text-muted"></i>Username</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required>
                <label for="inputPassword"><i class="fa-solid fa-lock me-2 text-muted"></i>Password</label>
            </div>
            
            <button type="submit" class="btn btn-login" name="login">
                Sign In <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>
        
        <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> Back to Homepage</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
