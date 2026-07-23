<?php
require_once 'functions.php';

$user = null;
$username = isset($_GET['user']) ? $_GET['user'] : '';

if (!empty($username)) {
    $user = getUserByUsername($username);
}

if (!$user) {
    $user = getDefaultUser();
}

if (!$user) {
    die("Belum ada CV yang ditentukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV - <?php echo htmlspecialchars($user['nama_lengkap']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .card { border-radius: 10px; margin-bottom: 20px; }
        .profile-img { width: 150px; height: 150px; object-fit: cover; border: 4px solid #007bff; border-radius: 50%; }
        .section-title { border-bottom: 3px solid #007bff; padding-bottom: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" class="profile-img mb-3" alt="Foto">
                    <h4><?php echo htmlspecialchars($user['nama_lengkap']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($user['username']); ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary text-white">Tentang Saya</div>
                <div class="card-body">
                    <?php echo nl2br(htmlspecialchars($user['tentang'])); ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Pendidikan</div>
                <div class="card-body">
                    <?php echo nl2br(htmlspecialchars($user['pendidikan'])); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary text-white">Keahlian Teknis</div>
                <div class="card-body">
                    <?php echo nl2br(htmlspecialchars($user['keahlian'])); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary text-white">Proyek</div>
                <div class="card-body">
                    <?php echo nl2br(htmlspecialchars($user['proyek'])); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>