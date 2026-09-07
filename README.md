# BTL-PT-TKHHTT - HỆ THỐNG QUẢN LÝ TUYỂN DỤNG NHÂN SỰ

> **Bài Tập Lớn: Phân Tích Thiết Kế Hệ Thống Thông Tin (BTL-PT-TKHHTT)**  
> **Repository:** [https://github.com/anhtuyet1306/BTL-PT-TKHHTT](https://github.com/anhtuyet1306/BTL-PT-TKHHTT)  
> **Kiến trúc mã nguồn:** PHP phân tách từng trang độc lập, HTML5, CSS3, MySQL.

---

## 1. Cấu trúc từng trang PHP trong hệ thống:
Hệ thống được chia thành các tệp PHP độc lập, giúp điều hướng trực tiếp qua URL hoặc qua Menu bên trái:

| Tên tệp tin | Chức năng nghiệp vụ | Đường dẫn truy cập trên Apache |
|---|---|---|
| `index.php` | Trang chủ Tổng quan (Dashboard) | `http://localhost/tuyendung/index.php` |
| `vi-tri.php` | Quản lý Vị trí tuyển dụng | `http://localhost/tuyendung/vi-tri.php` |
| `ung-vien.php` | Quản lý Hồ sơ ứng viên | `http://localhost/tuyendung/ung-vien.php` |
| `ho-so.php` | Tiếp nhận hồ sơ & Duyệt CV | `http://localhost/tuyendung/ho-so.php` |
| `lich-phong-van.php` | Sắp xếp lịch phỏng vấn theo tuần | `http://localhost/tuyendung/lich-phong-van.php` |
| `danh-gia.php` | Form chấm điểm tiêu chí ứng viên | `http://localhost/tuyendung/danh-gia.php` |
| `ket-qua.php` | Kết quả tuyển dụng & Job Offer | `http://localhost/tuyendung/ket-qua.php` |
| `bao-cao.php` | Thống kê & Phễu tuyển dụng | `http://localhost/tuyendung/bao-cao.php` |
| `dang-nhap.php` | Đăng nhập hệ thống HR | `http://localhost/tuyendung/dang-nhap.php` |
| `header.php` | Layout đầu trang chung | Tự động nhúng qua `include` |
| `sidebar.php` | Thanh menu đỏ #D32F2F chung | Tự động nhúng qua `include` |
| `footer.php` | Layout chân trang chung | Tự động nhúng qua `include` |
| `ketnoi.php` | Cấu hình MySQL & Dữ liệu mẫu | Tự động kết nối |
| `style.css` | Bảng màu & Định dạng CSS | Dùng chung toàn hệ thống |
| `database.sql` | Script tạo CSDL MySQL | Nhập vào phpMyAdmin |

---

## 2. Cách chạy trên máy tính với Apache (XAMPP)

1. Cài đặt **XAMPP**.
2. Giải nén tất cả các file trên vào thư mục:
   ```
   C:\xampp\htdocs\tuyendung\
   ```
3. Mở **XAMPP Control Panel**, nhấn nút **Start** ở module **Apache**.
4. Mở **Google Chrome** và truy cập bất kỳ trang nào bạn muốn:
   - Trang tổng quan: `http://localhost/tuyendung/index.php`
   - Trang vị trí tuyển dụng: `http://localhost/tuyendung/vi-tri.php`
   - Trang ứng viên: `http://localhost/tuyendung/ung-vien.php`
   - Trang lịch phỏng vấn: `http://localhost/tuyendung/lich-phong-van.php`
   - Trang đánh giá: `http://localhost/tuyendung/danh-gia.php`

---

## 3. Lệnh Git đẩy lên repository GitHub:
```bash
git init
git add .
git commit -m "Phan tach he thong quan ly tuyen dung thanh tung trang PHP doc lap"
git branch -M main
git remote add origin https://github.com/anhtuyet1306/BTL-PT-TKHHTT.git
git push -u origin main
```
