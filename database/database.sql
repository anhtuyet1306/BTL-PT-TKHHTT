-- CƠ SỞ DỮ LIỆU BÀI TẬP LỚN: HỆ THỐNG QUẢN LÝ TUYỂN DỤNG
-- Phù hợp MySQL / MariaDB (XAMPP, WAMP, LAMP)

CREATE DATABASE IF NOT EXISTS tuyendung_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tuyendung_db;

-- 1. BẢNG VỊ TRÍ TUYỂN DỤNG
CREATE TABLE IF NOT EXISTS vi_tri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_vi_tri VARCHAR(255) NOT NULL,
    phong_ban VARCHAR(100) NOT NULL,
    so_luong INT DEFAULT 1,
    han_nop DATE,
    trang_thai VARCHAR(50) DEFAULT 'Đang tuyển',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO vi_tri (ten_vi_tri, phong_ban, so_luong, han_nop, trang_thai) VALUES
('Lập trình viên PHP', 'CNTT', 5, '2024-06-30', 'Đang tuyển'),
('Nhân viên kinh doanh', 'Kinh doanh', 10, '2024-06-25', 'Đang tuyển'),
('Kế toán tổng hợp', 'Kế toán', 2, '2024-06-20', 'Đang tuyển'),
('Nhân viên nhân sự', 'Nhân sự', 2, '2024-06-15', 'Tạm dừng');

-- 2. BẢNG ỨNG VIÊN
CREATE TABLE IF NOT EXISTS ung_vien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20),
    email VARCHAR(100),
    vi_tri_id INT,
    kinh_nghiem VARCHAR(50),
    trang_thai VARCHAR(50) DEFAULT 'Mới',
    ngay_nop DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO ung_vien (ho_ten, so_dien_thoai, email, vi_tri_id, kinh_nghiem, trang_thai, ngay_nop) VALUES
('Nguyễn Văn A', '0901234567', 'vana@gmail.com', 1, '2 năm', 'Mới', '2024-06-10'),
('Trần Thị B', '0902345678', 'btran@gmail.com', 2, '3 năm', 'Đang xét duyệt', '2024-06-09'),
('Lê Văn C', '0903456789', 'c.le@gmail.com', 3, '4 năm', 'Đã phỏng vấn', '2024-06-08'),
('Phạm Thị D', '0904567891', 'dpham@gmail.com', 4, '1 năm', 'Không đạt', '2024-06-07');

-- 3. BẢNG LỊCH PHỎNG VẤN
CREATE TABLE IF NOT EXISTS lich_phong_van (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ung_vien_id INT,
    thoi_gian TIME,
    ngay_phong_van DATE,
    thu_trong_tuan VARCHAR(10),
    vong_phong_van VARCHAR(100),
    phong_hop VARCHAR(50),
    trang_thai VARCHAR(50) DEFAULT 'Đã xác nhận'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. BẢNG ĐÁNH GIÁ & KẾT QUẢ
CREATE TABLE IF NOT EXISTS danh_gia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ung_vien_id INT,
    diem_trung_binh DECIMAL(3,1),
    nhan_xet TEXT,
    ket_qua VARCHAR(50) DEFAULT 'Đạt',
    ngay_quyet_dinh DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
