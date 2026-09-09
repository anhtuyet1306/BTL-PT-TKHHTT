<?php
declare(strict_types=1);

$basePath = '../';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $hoTen = trim($_POST['ho_ten'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $hoTen === '' || $password === '' || $confirmPassword === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    } elseif (strlen($username) < 3) {
        $error = 'Tên đăng nhập phải có ít nhất 3 ký tự!';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } elseif ($password !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp!';
    } else {
        try {
            $stmt = $pdo->prepare("
                SELECT id
                FROM users
                WHERE username = ?
                LIMIT 1
            ");
            $stmt->execute([$username]);

            if ($stmt->fetch()) {
                $error = 'Tên đăng nhập đã tồn tại!';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO users
                    (
                        username,
                        password,
                        ho_ten,
                        role
                    )
                    VALUES (?, ?, ?, 'nhanvien')
                ");

                $stmt->execute([
                    $username,
                    $hashedPassword,
                    $hoTen
                ]);

                $success = 'Đăng ký tài khoản thành công! Bạn có thể đăng nhập ngay.';
            }
        } catch (PDOException $e) {
            $error = 'Lỗi đăng ký: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">

<div class="login-card">

    <h2>Đăng ký tài khoản</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($success === ''): ?>

        <form method="POST" action="dang-ky.php">

            <div class="form-group">
                <label>Họ và tên</label>
                <input
                    type="text"
                    name="ho_ten"
                    value="<?= htmlspecialchars($_POST['ho_ten'] ?? '') ?>"
                    placeholder="Nhập họ và tên"
                    required
                >
            </div>

            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input
                    type="text"
                    name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    placeholder="Nhập tên đăng nhập"
                    required
                >
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Ít nhất 6 ký tự"
                    required
                >
            </div>

            <div class="form-group">
                <label>Xác nhận mật khẩu</label>
                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Nhập lại mật khẩu"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Đăng ký
            </button>

        </form>

    <?php endif; ?>

    <p>
        Đã có tài khoản?
        <a href="dang-nhap.php">Đăng nhập</a>
    </p>

    <p>
        <a href="../index.php">← Quay về trang chủ</a>
    </p>

</div>

</body>
</html>