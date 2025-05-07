<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil jumlah artikel
$stmt = $pdo->query("SELECT COUNT(*) as total FROM articles");
$totalArticles = $stmt->fetch()['total'];

// Ambil jumlah kategori
$stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
$totalCategories = $stmt->fetch()['total'];

// Ambil jumlah user
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CMS Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body.bg-light {
            background-color: #f8f9fa !important;
        }
        .dashboard-header {
            background: linear-gradient(90deg, #1976d2 0%, #21cbf3 100%);
            color: #fff;
            padding: 2.5rem 0 2rem 0;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 4px 24px 0 rgba(33,203,243,0.08);
        }
        .dashboard-divider {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 2rem;
        }
        .dashboard-card {
            border-radius: 1.5rem;
            box-shadow: 0 2px 16px 0 rgba(0,0,0,0.06);
            transition: box-shadow 0.3s, transform 0.3s;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            border: 0 !important;
            /* background: #f8f9fa !important; */
        }
        .dashboard-card:hover {
            box-shadow: 0 8px 32px 0 rgba(33,203,243,0.15);
            transform: translateY(-4px) scale(1.03);
        }
        .dashboard-icon {
            font-size: 2rem;
            opacity: 0.10;
            position: absolute;
            top: 1.2rem;
            right: 1.5rem;
            pointer-events: none;
        }
        .dashboard-card-title {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 0.3rem;
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
            font-weight: 400;
            letter-spacing: 0.5px;
        }
        .dashboard-card .display-4 {
            font-size: 2.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light min-vh-100">
    <div class="dashboard-header mb-4 shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold mb-0 text-center">DASHBOARD ADMIN</h1>
        </div>
    </div>
    <div class="dashboard-divider"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="articles.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Kategori</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        
        <div class="row mt-4 g-4">
            <div class="col-md-4">
                <div class="card bg-success text-white dashboard-card position-relative border-0">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text dashboard-icon"></i>
                        <h5 class="card-title dashboard-card-title">Total Artikel</h5>
                        <p class="card-text display-4"><?php echo $totalArticles; ?></p>
                        <a href="articles.php" class="btn btn-light">Kelola Artikel</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white dashboard-card position-relative border-0">
                    <div class="card-body">
                        <i class="bi bi-tags dashboard-icon"></i>
                        <h5 class="card-title dashboard-card-title">Total Kategori</h5>
                        <p class="card-text display-4"><?php echo $totalCategories; ?></p>
                        <a href="categories.php" class="btn btn-light">Kelola Kategori</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white dashboard-card position-relative border-0">
                    <div class="card-body">
                        <i class="bi bi-people dashboard-icon"></i>
                        <h5 class="card-title dashboard-card-title">Total User</h5>
                        <p class="card-text display-4"><?php echo $totalUsers; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 