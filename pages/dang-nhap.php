<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin đăng nhập!';
    } else {
        try {
            $stmt = $pdo->prepare("
                SELECT id, username, password, ho_ten, role
                FROM users
                WHERE username = ?
                LIMIT 1
            ");

            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'ho_ten' => $user['ho_ten'],
                    'role' => $user['role']
                ];

                header('Location: ../index.php');
                exit;
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
            }
        } catch (PDOException $e) {
            $error = 'Không thể kết nối hoặc truy vấn cơ sở dữ liệu!';
        }
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
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="dang-nhap.php">

            <div class="form-group">
                <label>Email / Tên đăng nhập</label>

                <input
                    type="text"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    class="input-control"
                    required
                >
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>

                <input
                    type="password"
                    name="password"
                    class="input-control"
                    required
                >
            </div>

            <div class="form-check">
                <label>
                    <input type="checkbox" name="remember">
                    Ghi nhớ đăng nhập
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                ĐĂNG NHẬP
            </button>

            <div style="text-align: center; margin-top: 15px;">
                <span style="color: #64748b; font-size: 12px;">
                    Chưa có tài khoản?
                </span>

                <a
                    href="dang-ky.php"
                    style="color: #7A2E25; font-size: 12px; text-decoration: none; font-weight: 500;"
                >
                    Đăng ký ngay
                </a>
            </div>

            <div style="text-align: center; margin-top: 12px;">
                <a
                    href="../index.php"
                    style="color: #64748b; font-size: 12px; text-decoration: none;"
                >
                    &larr; Quay về Trang chủ
                </a>
            </div>

        </form>

    </div>

</body>
</html>