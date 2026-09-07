# BTL-PT-TKHHTT - HỆ THỐNG QUẢN LÝ TUYỂN DỤNG NHÂN SỰ

> **Bài Tập Lớn: Phân Tích Thiết Kế Hệ Thống Thông Tin (BTL-PT-TKHHTT)**  
> **Repository:** [https://github.com/anhtuyet1306/BTL-PT-TKHHTT](https://github.com/anhtuyet1306/BTL-PT-TKHHTT)  
> **Cấu trúc:** Kiến trúc phân tầng Module chuyên nghiệp (config, includes, pages, assets, database).

---

## 1. Cấu trúc thư mục dự án chuẩn:
```
tuyendung/
├── index.php                 # Trang chủ Tổng quan (Dashboard)
├── config/
│   └── database.php          # Cấu hình kết nối MySQL và dữ liệu mẫu
├── includes/
│   ├── header.php            # Phần đầu trang dùng chung
│   ├── sidebar.php           # Thanh menu đỏ (#D32F2F) điều hướng
│   └── footer.php            # Phần chân trang dùng chung
├── pages/                    # Các trang nghiệp vụ tuyển dụng
│   ├── vi-tri.php            # Quản lý vị trí tuyển dụng
│   ├── ung-vien.php          # Quản lý hồ sơ ứng viên
│   ├── ho-so.php             # Tiếp nhận hồ sơ & duyệt CV
│   ├── lich-phong-van.php    # Lịch phỏng vấn tuần
│   ├── danh-gia.php          # Đánh giá & chấm điểm tiêu chí
│   ├── ket-qua.php           # Kết quả tuyển dụng & Job Offer
│   ├── bao-cao.php           # Báo cáo thống kê & phễu tuyển dụng
│   └── dang-nhap.php         # Đăng nhập hệ thống HR
├── assets/
│   ├── css/
│   │   └── style.css         # CSS màu đỏ chuẩn #D32F2F
│   └── js/
│       └── script.js         # JavaScript tính điểm & tương tác
├── database/
│   └── database.sql          # File CSDL MySQL
└── README.md                 # Tài liệu thuyết minh đề tài
```

---

## 2. Hướng dẫn chạy trên máy tính với Apache (XAMPP)

1. Cài đặt **XAMPP** và khởi động **Apache**.
2. Đặt toàn bộ thư mục vào:
   - **Windows:** `C:\xampp\htdocs\tuyendung\`
   - **MacOS:** `/Applications/XAMPP/htdocs/tuyendung/`
3. Mở **Google Chrome** và truy cập:
   ```
   http://localhost/tuyendung/index.php
   ```
   Hoặc truy cập trực tiếp từng phân hệ:
   ```
   http://localhost/tuyendung/pages/vi-tri.php
   http://localhost/tuyendung/pages/ung-vien.php
   http://localhost/tuyendung/pages/lich-phong-van.php
   http://localhost/tuyendung/pages/danh-gia.php
   http://localhost/tuyendung/pages/ket-qua.php
   ```

---

## 3. Hướng dẫn đẩy lên GitHub (Nhánh `main`)

> **Hỏi: Có phải tạo branch riêng không hay trực tiếp trên `main`?**  
> **Trả lời:** Với Bài tập lớn (BTL), bạn nên **đẩy trực tiếp lên nhánh `main`**. Khi giảng viên mở link GitHub `https://github.com/anhtuyet1306/BTL-PT-TKHHTT` sẽ thấy ngay đầy đủ mã nguồn và cấu trúc thư mục sạch đẹp ở trang đầu tiên!

```bash
# 1. Mở Git Bash tại thư mục này
git init

# 2. Thêm tất cả thư mục và file
git add .

# 3. Commit mã nguồn
git commit -m "Khoi tao cau truc thu muc he thong tuyen dung BTL PT TKHHTT"

# 4. Đặt tên nhánh chính là main
git branch -M main

# 5. Thêm remote GitHub
git remote add origin https://github.com/anhtuyet1306/BTL-PT-TKHHTT.git

# 6. Đẩy lên nhánh main
git push -u origin main
```
