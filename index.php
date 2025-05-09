<?php
require_once 'config/database.php';

// Ambil semua artikel dengan kategori
$stmt = $pdo->query("SELECT articles.*, categories.name as category_name 
                     FROM articles 
                     LEFT JOIN categories ON articles.category_id = categories.id 
                     ORDER BY articles.created_at DESC");
$articles = $stmt->fetchAll();

// Ambil semua kategori untuk sidebar
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">CMS Sederhana</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4">Artikel Terbaru</h1>
                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">Belum ada artikel yang dipublikasikan.</div>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h2>
                                <p class="text-muted">
                                    Kategori: <?php echo htmlspecialchars($article['category_name']); ?> | 
                                    Tanggal: <?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?>
                                </p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 300) . '...')); ?></p>
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Kategori</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted">Belum ada kategori.</p>
                        <?php else: ?>
                            <ul class="list-unstyled">
                                <?php foreach ($categories as $category): ?>
                                    <li class="mb-2">
                                        <a href="category.php?id=<?php echo $category['id']; ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> tidak bisa login 