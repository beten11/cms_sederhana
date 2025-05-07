<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Hapus kategori
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit();
}

// Proses form tambah/edit kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    
    if (isset($_POST['id'])) {
        // Update kategori
        $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$name, $_POST['id']]);
    } else {
        // Tambah kategori baru
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    
    header("Location: categories.php");
    exit();
}

// Ambil semua kategori
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - CMS Sederhana</title>
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
                        <a class="nav-link" href="articles.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="categories.php">Kategori</a>
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
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tambah Kategori Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    onclick="editCategory(<?php echo htmlspecialchars(json_encode($category)); ?>)">
                                                Edit
                                            </button>
                                            <a href="categories.php?delete=<?php echo $category['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editCategory(category) {
            document.getElementById('edit_id').value = category.id;
            document.getElementById('edit_name').value = category.name;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
    </script>
</body>
</html> 