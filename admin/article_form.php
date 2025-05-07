<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil data artikel jika sedang edit
$article = [
    'id' => '',
    'title' => '',
    'content' => '',
    'category_id' => ''
];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $article = $stmt->fetch();
}

// Ambil semua kategori
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];

    if (isset($_POST['id'])) {
        // Update artikel
        $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$title, $content, $category_id, $_POST['id']]);
    } else {
        // Tambah artikel baru
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, category_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $category_id]);
    }

    header("Location: articles.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['id'] ? 'Edit' : 'Tambah'; ?> Artikel - CMS Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="articles.php">Artikel</a>
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
        <h2><?php echo $article['id'] ? 'Edit' : 'Tambah'; ?> Artikel</h2>
        
        <form method="POST" class="mt-4">
            <?php if ($article['id']): ?>
                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $article['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Konten</label>
                <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="articles.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 