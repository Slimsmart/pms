<?php
	session_start();
	if (isset($_SESSION['pass'])) {
		header("Location:home.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login - Project Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/pms/css/modern-theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80') no-repeat center center fixed;
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
        background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(30,41,59,0.9) 100%);
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
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        padding: 40px;
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
    }
    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .login-header h2 {
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }
    .form-floating input {
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.2);
        background-color: rgba(15, 23, 42, 0.5) !important;
        color: #f8fafc !important;
    }
    .form-floating input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        background-color: rgba(15, 23, 42, 0.7) !important;
    }
    .form-floating label {
        color: #94a3b8 !important;
    }
    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label {
        color: #a5b4fc !important;
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4);
        color: white;
        transition: all 0.3s ease;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        color: #94a3b8;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    .back-link:hover {
        color: #a5b4fc;
    }
  </style>
</head>
<body>

<div class="overlay"></div>

<div class="container login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="mb-3">
                <i class="fa-solid fa-user-gear fa-3x text-indigo" style="color: #818cf8;"></i>
            </div>
            <h2>Admin Control</h2>
            <p class="text-slate-400" style="color: #94a3b8;">Sign in to manage the platform</p>
        </div>

        <?php if (isset($_SESSION['errmsg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3" style="background-color: rgba(239, 68, 68, 0.2); color: #fca5a5;" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlentities($_SESSION['errmsg']); ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
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
                Access Panel <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </form>
        
        <a href="../" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> Public Portal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
