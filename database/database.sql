-- CƠ SỞ DỮ LIỆU BÀI TẬP LỚN: HỆ THỐNG QUẢN LÝ TUYỂN DỤNG
-- MySQL / MariaDB - XAMPP

CREATE DATABASE IF NOT EXISTS quanly_tuyen_dung
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE quanly_tuyen_dung;

-- 1. BẢNG PHÒNG BAN
CREATE TABLE IF NOT EXISTS phongban (
    MaPhongBan INT AUTO_INCREMENT PRIMARY KEY,
    TenPhongBan VARCHAR(100) NOT NULL UNIQUE,
    DangHoatDong TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO phongban (MaPhongBan, TenPhongBan, DangHoatDong) VALUES
(1, 'Công nghệ thông tin', 1),
(2, 'Kinh doanh', 1),
(3, 'Kế toán', 1),
(4, 'Nhân sự', 1);

-- 2. BẢNG NGƯỜI DÙNG
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'nhanvien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. BẢNG ỨNG VIÊN
CREATE TABLE IF NOT EXISTS ungvien (
    MaUV INT AUTO_INCREMENT PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    NgaySinh DATE,
    GioiTinh VARCHAR(10),
    Email VARCHAR(100),
    SoDienThoai VARCHAR(15),
    DiaChi VARCHAR(255),
    KinhNghiem TEXT,
    KyNang TEXT,
    CV VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. BẢNG VỊ TRÍ TUYỂN DỤNG
CREATE TABLE IF NOT EXISTS vitrituyendung (
    MaVT INT AUTO_INCREMENT PRIMARY KEY,
    MaPhongBan INT,
    TenViTri VARCHAR(100) NOT NULL,
    SoLuong SMALLINT NOT NULL DEFAULT 1,
    MoTa TEXT,
    YeuCau TEXT,
    QuyenLoi TEXT,
    DiaDiem VARCHAR(150),
    HinhThucLamViec VARCHAR(30),
    LuongTu DECIMAL(15,2),
    LuongDen DECIMAL(15,2),
    ThoaThuanLuong TINYINT(1) NOT NULL DEFAULT 0,
    NgayDang DATETIME,
    Luong VARCHAR(50),
    HanNop DATE,
    TrangThai VARCHAR(30),
    HienThi TINYINT(1) NOT NULL DEFAULT 1,
    NgayTao DATETIME,
    NgayCapNhat DATETIME,
    CONSTRAINT FK_ViTri_PhongBan
        FOREIGN KEY (MaPhongBan)
        REFERENCES phongban(MaPhongBan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX IX_ViTri_MaPhongBan
ON vitrituyendung(MaPhongBan);

CREATE INDEX IX_ViTri_TrangThai
ON vitrituyendung(TrangThai);

CREATE INDEX IX_ViTri_HanNop
ON vitrituyendung(HanNop);

-- 5. BẢNG HỒ SƠ ỨNG TUYỂN
CREATE TABLE IF NOT EXISTS ungtuyen (
    MaUT INT AUTO_INCREMENT PRIMARY KEY,
    MaUV INT NOT NULL,
    MaVT INT NOT NULL,
    NgayUngTuyen DATE,
    TrangThai VARCHAR(30),
    CONSTRAINT FK_UngTuyen_UngVien
        FOREIGN KEY (MaUV)
        REFERENCES ungvien(MaUV),
    CONSTRAINT FK_UngTuyen_ViTri
        FOREIGN KEY (MaVT)
        REFERENCES vitrituyendung(MaVT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. BẢNG PHỎNG VẤN
CREATE TABLE IF NOT EXISTS phongvan (
    MaPV INT AUTO_INCREMENT PRIMARY KEY,
    MaUT INT NOT NULL,
    UserID INT,
    NgayPhongVan DATE NOT NULL,
    GioPhongVan TIME NOT NULL,
    VongPhongVan VARCHAR(50),
    HinhThuc VARCHAR(50),
    DiaDiem VARCHAR(255),
    NguoiPhongVan VARCHAR(100),
    TrangThai VARCHAR(30) DEFAULT 'Đã lên lịch',
    GhiChu TEXT,
    CONSTRAINT FK_PhongVan_UngTuyen
        FOREIGN KEY (MaUT)
        REFERENCES ungtuyen(MaUT),
    CONSTRAINT FK_PhongVan_Users
        FOREIGN KEY (UserID)
        REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. BẢNG ĐÁNH GIÁ
CREATE TABLE IF NOT EXISTS danhgia (
    MaDG INT AUTO_INCREMENT PRIMARY KEY,
    MaPV INT NOT NULL,
    UserID INT,
    DiemChuyenMon DECIMAL(4,1),
    DiemKyNang DECIMAL(4,1),
    DiemThaiDo DECIMAL(4,1),
    DiemTrungBinh DECIMAL(4,1),
    NhanXet TEXT,
    NguoiDanhGia VARCHAR(100),
    NgayDanhGia DATE,
    CONSTRAINT FK_DanhGia_PhongVan
        FOREIGN KEY (MaPV)
        REFERENCES phongvan(MaPV),
    CONSTRAINT FK_DanhGia_Users
        FOREIGN KEY (UserID)
        REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. BẢNG KẾT QUẢ TUYỂN DỤNG
CREATE TABLE IF NOT EXISTS ketqua (
    MaKQ INT AUTO_INCREMENT PRIMARY KEY,
    MaUT INT NOT NULL,
    KetQua VARCHAR(30) NOT NULL,
    NgayQuyetDinh DATE,
    LyDo TEXT,
    GhiChu TEXT,
    LuongDeXuat DECIMAL(15,2),
    NgayNhanViecDuKien DATE,
    TrangThaiPhanHoi VARCHAR(30) NOT NULL DEFAULT 'Chưa gửi',
    NguoiDuyet VARCHAR(100),
    CongBo TINYINT(1) NOT NULL DEFAULT 0,
    NgayTao DATETIME,
    NgayCapNhat DATETIME,
    CONSTRAINT FK_KetQua_UngTuyen
        FOREIGN KEY (MaUT)
        REFERENCES ungtuyen(MaUT)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;