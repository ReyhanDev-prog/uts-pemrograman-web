<?php
require_once '../config.php';
require_once '../functions.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: /project_cv/Admin/login.php");
    exit;
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'setdefault' && isset($_GET['id'])) {
        setDefaultUser($_GET['id']);
        header("Location: /project_cv/Admin/");
        exit;
    }
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        deleteUser($_GET['id']);
        header("Location: /project_cv/Admin/");
        exit;
    }
}

$edit_user = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $tentang = $_POST['tentang'];
    $pendidikan = $_POST['pendidikan'];
    $keahlian = $_POST['keahlian'];
    $proyek = $_POST['proyek'];
    $role = $_POST['role'] ?? 'user';
    $id = $_POST['user_id'] ?? 0;

    if ($id > 0) {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username=?, password=?, nama_lengkap=?, email=?, tentang=?, pendidikan=?, keahlian=?, proyek=?, role=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $hashed, $nama, $email, $tentang, $pendidikan, $keahlian, $proyek, $role, $id]);
        } else {
            $sql = "UPDATE users SET username=?, nama_lengkap=?, email=?, tentang=?, pendidikan=?, keahlian=?, proyek=?, role=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $nama, $email, $tentang, $pendidikan, $keahlian, $proyek, $role, $id]);
        }
    } else {
        createUser($username, $password, $nama, $email, $role);
        $user = getUserByUsername($username);
        if ($user) {
            updateUserCV($user['id'], ['tentang'=>$tentang, 'pendidikan'=>$pendidikan, 'keahlian'=>$keahlian, 'proyek'=>$proyek]);
        }
    }
    header("Location: /project_cv/Admin/");
    exit;
}

$users = getAllUsers();
$default = getDefaultUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Dashboard Admin</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="/project_cv/Admin/logout.php" class="btn btn-danger">Logout</a>
        <a href="/project_cv/" class="btn btn-secondary">Lihat CV Default</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <?php echo $edit_user ? 'Edit User' : 'Tambah User Baru'; ?>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="user_id" value="<?php echo $edit_user ? $edit_user['id'] : 0; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['username']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Password <?php echo $edit_user ? '(kosongkan jika tidak diubah)' : ''; ?></label>
                            <input type="password" name="password" class="form-control" <?php echo $edit_user ? '' : 'required'; ?>>
                        </div>
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['nama_lengkap']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $edit_user ? htmlspecialchars($edit_user['email']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="user" <?php echo ($edit_user && $edit_user['role']=='user') ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo ($edit_user && $edit_user['role']=='admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Tentang</label>
                            <textarea name="tentang" class="form-control" rows="2"><?php echo $edit_user ? htmlspecialchars($edit_user['tentang']) : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Pendidikan</label>
                            <textarea name="pendidikan" class="form-control" rows="2"><?php echo $edit_user ? htmlspecialchars($edit_user['pendidikan']) : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Keahlian</label>
                            <textarea name="keahlian" class="form-control" rows="2"><?php echo $edit_user ? htmlspecialchars($edit_user['keahlian']) : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Proyek</label>
                            <textarea name="proyek" class="form-control" rows="2"><?php echo $edit_user ? htmlspecialchars($edit_user['proyek']) : ''; ?></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" name="save_user" class="btn btn-success">Simpan</button>
                <a href="/project_cv/Admin/" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">Daftar User</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>ID</th><th>Username</th><th>Nama</th><th>Email</th><th>Role</th><th>Default</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td>
                            <?php if ($u['is_default']): ?>
                                <span class="badge bg-success">Default</span>
                            <?php else: ?>
                                <a href="?action=setdefault&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning">Set Default</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $u['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</a>
                            <a href="/project_cv/<?php echo $u['username']; ?>" target="_blank" class="btn btn-sm btn-info">Lihat CV</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>