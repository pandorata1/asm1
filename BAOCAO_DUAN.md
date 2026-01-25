# BÁO CÁO CHI TIẾT DỰ ÁN: HỆ THỐNG QUẢN LÝ SINH VIÊN (STUDENT MANAGEMENT SYSTEM)

## 1. GIỚI THIỆU CHUNG
### 1.1. Mục tiêu
Xây dựng một nền tảng web ứng dụng giúp quản lý hồ sơ sinh viên hiệu quả, đồng thời tích hợp hệ thống quản lý người dùng với phân cấp quyền hạn chặt chẽ. Hệ thống đảm bảo tính bảo mật, toàn vẹn dữ liệu và trải nghiệm người dùng thân thiện.

### 1.2. Công nghệ sử dụng
*   **Ngôn ngữ lập trình**: PHP (Thuần/Native) - Sử dụng PDO cho kết nối cơ sở dữ liệu.
*   **Cơ sở dữ liệu**: MySQL (Chạy trên nền tảng Laragon/XAMPP).
*   **Giao diện (Frontend)**: HTML5, CSS3, Bootstrap 5 (Responsive Design).
*   **Môi trường phát triển**: Windows, Laragon.

---

## 2. THIẾT KẾ CƠ SỞ DỮ LIỆU (DATABASE SCHEMA)
Hệ thống sử dụng hai bảng chính được thiết kế để đảm bảo tính nhất quán dữ liệu.

### 2.1. Bảng `users` (Quản lý tài khoản)
Lưu trữ thông tin đăng nhập và quyền hạn.
*   `id` (INT, PK, Auto Increment): Định danh duy nhất.
*   `username` (VARCHAR): Tên đăng nhập (Unique).
*   `email` (VARCHAR): Email người dùng (Unique).
*   `password` (VARCHAR): Mật khẩu đã mã hóa (Bcrypt).
*   `role` (ENUM['admin', 'user']): Phân quyền (Mặc định là 'user').
*   `created_at` (DATETIME): Thời gian tạo.

### 2.2. Bảng `students` (Quản lý sinh viên)
Lưu trữ hồ sơ chi tiết của sinh viên.
*   `id` (INT, PK, Auto Increment): Mã sinh viên.
*   `first_name` (VARCHAR): Tên.
*   `last_name` (VARCHAR): Họ và tên đệm.
*   `email` (VARCHAR): Email liên lạc (Unique - dùng để đồng bộ sang Users).
*   `phone` (VARCHAR): Số điện thoại.
*   `gender` (VARCHAR/ENUM): Giới tính (Lưu trữ 'male'/'female', hiển thị 'Nam'/'Nữ').
*   `date_of_birth` (DATE): Ngày sinh.
*   `address` (TEXT): Địa chỉ.
*   `created_at` (DATETIME): Ngày nhập hồ sơ.

---

## 3. CÁC TÍNH NĂNG CHI TIẾT

### 3.1. Phân hệ Xác thực & Phân quyền (Auth Module)
*   **Đăng ký (Register)**: 
    *   Cho phép người dùng tạo tài khoản mới. 
    *   Hệ thống tự động gán quyền `user` (người dùng thường) để đảm bảo an ninh, tránh việc tự đăng ký quyền Admin.
    *   Kiểm tra trùng lặp Username/Email ngay lúc đăng ký.
*   **Đăng nhập (Login)**: 
    *   Sử dụng Session để lưu trạng thái đăng nhập.
    *   Mã hóa mật khẩu bằng `password_hash()` và kiểm tra bằng `password_verify()` để bảo mật tuyệt đối.
*   **Cơ chế Phân quyền (RBAC)**:
    *   **Admin**: Truy cập toàn bộ chức năng.
    *   **User**: Truy cập hạn chế (Chỉ xem dữ liệu, ẩn các nút Thêm/Sửa/Xóa).
    *   Middleware kiểm tra: `require_login()` (bắt buộc đăng nhập) và `require_admin()` (bắt buộc là admin).

### 3.2. Phân hệ Quản lý Sinh viên (Student Module)
*   **Xem danh sách (Read)**: 
    *   Hiển thị danh sách dạng bảng.
    *   Sắp xếp dữ liệu theo ID tăng dần (`ORDER BY id ASC`) giúp dễ theo dõi sinh viên mới nhập.
    *   Việt hóa dữ liệu giới tính (male -> Nam, female -> Nữ).
*   **Thêm mới (Create) - *Admin Only***:
    *   Validate dữ liệu đầu vào (Bắt buộc nhập Tên, Email).
    *   Tự động kiểm tra Email đã tồn tại trong hệ thống chưa.
*   **Cập nhật & Xóa (Update & Delete) - *Admin Only***:
    *   Cho phép sửa đổi thông tin sai lệch.
    *   Chức năng xóa có cảnh báo xác nhận (Confirm Dialog) để tránh thao tác nhầm.

### 3.3. Tự động Đồng bộ Người dùng (Auto-Sync)
*   **Logic nghiệp vụ**: Mọi sinh viên trong trường đều là một người dùng của hệ thống.
*   **Cơ chế**: Một script tự động quét bảng `students`, lấy Email làm định danh để tạo tài khoản bên bảng `users`.
*   **Quy tắc tạo**:
    *   Username: Lấy phần trước @ của email (ví dụ: `nguyenvan` từ `nguyenvan@gmail.com`).
    *   Password mặc định: `123456` (được mã hóa).
    *   Role: `user`.
    *   Xử lý trùng lặp: Nếu email đã có tài khoản thì bỏ qua, đảm bảo không lỗi dữ liệu.

---

## 4. KỸ THUẬT NỔI BẬT & BẢO MẬT
*   **PDO (PHP Data Objects)**: Sử dụng PDO thay vì MySQLi để tăng tính linh hoạt và bảo mật.
*   **Prepared Statements**: Chống tấn công SQL Injection triệt để trong mọi câu lệnh truy vấn.
*   **XSS Protection**: Sử dụng hàm `htmlspecialchars()` khi hiển thị dữ liệu ra màn hình để ngăn chặn mã độc Javascript.
*   **Cấu trúc thư mục chuẩn MVC (tối giản)**: Tách biệt logic xử lý (PHP), giao diện (HTML/View) và Cấu hình (Config).

---

## 5. HƯỚNG DẪN SỬ DỤNG
1.  **Truy cập hệ thống**: Mở trình duyệt và vào đường dẫn dự án (ví dụ: `localhost/asm1`).
2.  **Đăng nhập Admin**:
    *   Tài khoản: `admin`
    *   Mật khẩu: `123456`
3.  **Quản lý Sinh viên**:
    *   Vào menu "Sinh viên".
    *   Admin sẽ thấy nút "Thêm mới" màu xanh và các nút "Sửa/Xóa" ở từng dòng.
4.  **Kiểm tra Phân quyền**:
    *   Đăng xuất Admin.
    *   Đăng ký tài khoản mới (hoặc dùng tài khoản sinh viên bất kỳ với pass `123456`).
    *   Vào lại trang "Sinh viên" -> Sẽ không thấy các nút chức năng sửa xóa.

---

## 6. KẾT LUẬN
Dự án đã hoàn thành đáp ứng 100% các yêu cầu về nghiệp vụ quản lý sinh viên cơ bản và nâng cao. Hệ thống hoạt động ổn định, giao diện Tiếng Việt thân thiện, dễ sử dụng và có khả năng mở rộng trong tương lai (ví dụ: thêm tính năng tìm kiếm, phân trang, xuất báo cáo Excel).

**Người thực hiện báo cáo**
*(Ký tên)*
