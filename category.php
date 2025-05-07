<?php
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Ambil detail kategori
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$_GET['id']]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: index.php");
    exit();
}

// Ambil artikel berdasarkan kategori
$stmt = $pdo->prepare("SELECT articles.*, categories.name as category_name 
                       FROM articles 
                       LEFT JOIN categories ON articles.category_id = categories.id 
                       WHERE articles.category_id = ? 
                       ORDER BY articles.created_at DESC");
$stmt->execute([$_GET['id']]);
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori: <?php echo htmlspecialchars($category['name']); ?> - CMS Sederhana</title>
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($category['name']); ?></li>
                    </ol>
                </nav>

                <h1 class="mb-4">Kategori: <?php echo htmlspecialchars($category['name']); ?></h1>

                <?php if (empty($articles)): ?>
                    <div class="alert alert-info">Belum ada artikel dalam kategori ini.</div>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h2>
                                <p class="text-muted">
                                    Tanggal: <?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?>
                                </p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 300) . '...')); ?></p>
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 