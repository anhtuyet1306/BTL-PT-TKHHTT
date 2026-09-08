# Bộ CSDL Microsoft Access – Vị trí và Kết quả tuyển dụng

## Tệp sử dụng

- `TuyenDung_ViTri_KetQua.accdb`: CSDL mẫu đã có bảng, quan hệ và dữ liệu.
- `01_tao_bang_access.sql`: DDL tạo lại bảng bằng Query Design > SQL View.
- `02_du_lieu_mau_access.sql`: lệnh thêm dữ liệu minh họa.
- `03_truy_van_mau_access.sql`: query phục vụ xem vị trí, kết quả và thống kê.
- `tao_csdl_access.ps1`: tạo lại `.accdb` khi máy có Microsoft Access Database Engine.
- `kiem_tra_csdl_access.ps1`: kiểm tra số bản ghi, phép nối, khóa ngoại và chỉ mục duy nhất.

## Kiểm tra nhanh

1. Mở `TuyenDung_ViTri_KetQua.accdb` bằng Microsoft Access.
2. Chọn **Database Tools > Relationships** để xem quan hệ.
3. Mở từng bảng để xem dữ liệu mẫu.
4. Tạo query mới, chuyển **SQL View**, sao chép từng truy vấn trong `03_truy_van_mau_access.sql` và lưu với tên gợi ý.

## Nếu tạo thủ công

1. Tạo Blank Database.
2. Chạy **một câu lệnh mỗi lần** trong `01_tao_bang_access.sql`.
3. Chạy lần lượt `02_du_lieu_mau_access.sql`.
4. Trong Relationships, bật **Enforce Referential Integrity** và **Cascade Update Related Fields**; không bật **Cascade Delete Related Records**.
5. Ở Design View, đặt Validation Rule/Default Value theo tài liệu chính.

Access dùng `#...#` cho ngày trong SQL. Dữ liệu mẫu dùng `#yyyy-mm-dd#`; biểu mẫu có thể hiển thị `dd/mm/yyyy`.

Script tạo CSDL tự thử lại bằng PowerShell 32-bit nếu máy cài ACE/Access 32-bit. Nếu vẫn báo thiếu provider, cài Microsoft Access Database Engine cùng kiến trúc 32/64-bit với Office đang dùng.

Để sao lưu, đóng Access rồi chép tệp `.accdb` sang thư mục backup.
