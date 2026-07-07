<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Project Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/pms/css/modern-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(30,58,138,0.9) 100%), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=2000&q=80') center/cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            color: #cbd5e1;
            margin-bottom: 40px;
            font-weight: 400;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            padding: 10px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 700px;
            margin: 0 auto;
            transition: all 0.3s;
        }
        .search-container:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.3);
        }
        .search-input {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-size: 1.2rem !important;
            padding-left: 20px !important;
            box-shadow: none !important;
        }
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .search-btn {
            border-radius: 40px !important;
            padding: 15px 30px !important;
            font-size: 1.1rem !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .custom-navbar {
            background: transparent !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: white !important;
        }
    </style>
</head>
<body>

<!-- Transparent Navbar -->
<nav class="navbar navbar-expand-lg fixed-top custom-navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-shield-halved text-primary me-2"></i>Duplication Detection System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light rounded-pill px-4" href="login.php">Sign In</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h1 class="hero-title">Protect Academic Integrity</h1>
                <p class="hero-subtitle">Intelligently search millions of records to detect project duplication instantly.</p>
                
                <form action="search-result.php" method="get">
                    <div class="search-container d-flex align-items-center">
                        <i class="fa-solid fa-magnifying-glass ms-3 text-white-50 fs-5"></i>
                        <input type="text" name="q" class="form-control search-input" placeholder="Enter project topic or research area..." required>
                        <button type="submit" class="btn btn-primary search-btn">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="text-center py-4 text-muted bg-dark" style="position: relative; z-index: 10;">
    <div class="container">
        <small>&copy; <?php echo date('Y'); ?> Project Duplication Detection System. All rights reserved.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
