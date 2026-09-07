<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($email)) {
        $_SESSION['user'] = $email;
        header('Location: ../index.php');
        exit;
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin đăng nhập!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống Quản lý Tuyển dụng</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">&#128101;</div>
            <h2>ĐĂNG NHẬP</h2>
            <p>Hệ thống Quản lý Tuyển dụng Nhân sự</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="dang-nhap.php">
            <div class="form-group">
                <label>Email / Tên đăng nhập</label>
                <input type="text" name="email" value="admin@congty.com" class="input-control" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" value="123456" class="input-control" required>
            </div>
            <div class="form-check">
                <label><input type="checkbox" checked> Ghi nhớ đăng nhập</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">ĐĂNG NHẬP</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="../index.php" style="color: #64748b; font-size: 12px; text-decoration: none;">&larr; Quay về Trang chủ</a>
            </div>
        </form>
    </div>
</body>
</html>
