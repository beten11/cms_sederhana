<?php
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$article_id = $_GET['id'];

// Proses rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = (int)$_POST['rating'];
    if ($rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO ratings (article_id, rating) VALUES (?, ?)");
        $stmt->execute([$article_id, $rating]);
    }
}

// Ambil detail artikel
$stmt = $pdo->prepare("SELECT articles.*, categories.name as category_name 
                       FROM articles 
                       LEFT JOIN categories ON articles.category_id = categories.id 
                       WHERE articles.id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: index.php");
    exit();
}

// Ambil rata-rata rating
$stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_rating FROM ratings WHERE article_id = ?");
$stmt->execute([$article_id]);
$ratingData = $stmt->fetch();
$avg_rating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 2) : 0;
$total_rating = $ratingData['total_rating'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - CMS Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .star-rating .bi-star-fill {
            color: #ffc107;
            font-size: 1.5rem;
        }
        .star-rating .bi-star {
            color: #ccc;
            font-size: 1.5rem;
        }
        .star-rating-form button {
            background: none;
            border: none;
            padding: 0;
        }
    </style>
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
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($article['title']); ?></li>
                    </ol>
                </nav>

                <article>
                    <h1 class="mb-3"><?php echo htmlspecialchars($article['title']); ?></h1>
                    <p class="text-muted mb-4">
                        Kategori: <?php echo htmlspecialchars($article['category_name']); ?> | 
                        Tanggal: <?php echo date('d/m/Y H:i', strtotime($article['created_at'])); ?>
                    </p>
                    <div class="content mb-4">
                        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                    </div>
                </article>

                <!-- RATING SECTION -->
                <div class="mb-4">
                    <h5>Rating Artikel</h5>
                    <div class="star-rating mb-2">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= round($avg_rating)) {
                                echo '<i class="bi bi-star-fill"></i>';
                            } else {
                                echo '<i class="bi bi-star"></i>';
                            }
                        }
                        ?>
                        <span class="ms-2"><?php echo $avg_rating; ?> / 5 (<?php echo $total_rating; ?> rating)</span>
                    </div>
                    <form method="POST" class="star-rating-form">
                        <span>Beri rating:</span>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="submit" name="rating" value="<?php echo $i; ?>" title="<?php echo $i; ?> bintang">
                                <i class="bi bi-star"></i>
                            </button>
                        <?php endfor; ?>
                    </form>
                </div>
                <!-- END RATING SECTION -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 