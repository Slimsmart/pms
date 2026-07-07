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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        margin: 0;
    }
    .overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(59,130,246,0.85) 100%);
        z-index: 1;
    }
    .login-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 450px;
        padding: 15px;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.3);
        padding: 40px;
        border: 1px solid rgba(255,255,255,0.4);
    }
    .login-header {
        text-align: center;
        margin-bottom: 35px;
    }
    .login-header h2 {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .form-floating input {
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        background-color: rgba(255, 255, 255, 0.6) !important;
        transition: all 0.2s ease-in-out;
    }
    .form-floating input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        background-color: #ffffff !important;
    }
    .form-floating label {
        color: #64748b !important;
    }
    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label {
        color: #2563eb !important;
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.4);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    .back-link:hover {
        color: #2563eb;
    }
  </style>
</head>
<body>

<div class="overlay"></div>

<div class="container login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="mb-3">
                <i class="fa-solid fa-shield-halved fa-3x" style="color: #2563eb;"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="text-muted">Sign in to your portal</p>
        </div>

        <?php if (isset($_SESSION['errmsg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlentities($_SESSION['errmsg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['errmsg']); endif; ?>

        <form method="post" action="auth.php">
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Username" required autofocus>
                <label for="inputUsername"><i class="fa-regular fa-user me-2"></i>Username</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required>
                <label for="inputPassword"><i class="fa-solid fa-lock me-2"></i>Password</label>
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
