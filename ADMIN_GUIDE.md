# 📚 Hệ Thống Quản Lý Thi Thử THPT - Hướng Dẫn Sử Dụng

## 🎯 Tổng Quan Chức Năng

Hệ thống này được chia thành 3 vai trò chính:
- **👨‍🎓 Học Sinh**: Tham gia làm bài thi
- **👨‍🏫 Giáo Viên**: Tạo câu hỏi và đề thi (cần được Admin duyệt)
- **⚙️ Admin**: Quản lý hệ thống

---

## 📋 Hướng Dẫn Đăng Ký & Đăng Nhập

### Cho Học Sinh:
1. Truy cập trang đăng ký: `/register`
2. Chọn "👨‍🎓 Học Sinh"
3. Điền thông tin:
   - Họ và tên
   - Tên đăng nhập
   - Email
   - Mật khẩu (tối thiểu 6 ký tự)
4. Nhấn "Đăng ký"
5. Tài khoản sẽ hoạt động ngay lập tức

### Cho Giáo Viên:
1. Truy cập trang đăng ký: `/register`
2. Chọn "👨‍🏫 Giáo Viên"
3. Điền thông tin tương tự như học sinh
4. Nhấn "Đăng ký"
5. **⚠️ QUAN TRỌNG**: Tài khoản giáo viên **phải được Admin duyệt** trước khi có thể sử dụng
6. Đợi email hoặc thông báo từ Admin

---

## 🔐 Đăng Nhập Admin

### Thông tin tài khoản Admin:
- **URL đăng nhập**: `/login` (giao diện bình thường)
- **Tên đăng nhập**: `admin`
- **Mật khẩu**: `admin1`

⚠️ **Bảo mật**: Hãy thay đổi mật khẩu sau khi đăng nhập lần đầu

### Quy trình:
1. Truy cập `/login`
2. Nhập username: `admin` và password: `admin1`
3. Nhấn "Đăng nhập"
4. Tự động được chuyển đến giao diện Admin Dashboard

---

## 📊 Hướng Dẫn Quản Lý Admin

### 1️⃣ Dashboard
- Hiển thị thống kê chung của hệ thống:
  - Tổng số người dùng
  - Số lượng học sinh
  - Số lượng giáo viên
  - Số lượng giáo viên chờ duyệt
  - Số lượng người dùng bị chặn
- Các nút hành động nhanh

### 2️⃣ Duyệt Tài Khoản Giáo Viên
**Đường dẫn**: Admin > Duyệt Giáo Viên

**Chức năng**:
- Xem danh sách giáo viên chờ duyệt
- **Duyệt (✅)**: Cho phép giáo viên sử dụng hệ thống
- **Từ Chối (❌)**: Từ chối đơn xin cấp tài khoản giáo viên

**Lưu ý**: Giáo viên chỉ có thể đăng nhập sau khi được Admin duyệt

### 3️⃣ Quản Lý Người Dùng
**Đường dẫn**: Admin > Quản Lý Người Dùng

**Tính năng tìm kiếm**:
- Tìm kiếm theo tên, username hoặc email
- Lọc theo vai trò (Học Sinh, Giáo Viên, Admin)
- Lọc theo trạng thái (Hoạt Động, Bị Chặn)

**Hành động có sẵn**:
- **👁️ Xem**: Xem chi tiết người dùng
- **🚫 Chặn**: Chặn tài khoản (người dùng không thể đăng nhập)
- **✅ Bỏ Chặn**: Cho phép người dùng sử dụng lại
- **❌ Xóa**: Xóa tài khoản người dùng (không thể hoàn tác)

⚠️ **Không thể**: Chặn, xóa tài khoản Admin

---

## 🔄 Quy Trình Tài Khoản Giáo Viên

```
Giáo viên đăng ký → Tài khoản ở trạng thái "Chờ Duyệt"
    ↓
Admin xem danh sách giáo viên chờ duyệt
    ↓
Admin duyệt hoặc từ chối
    ↓
Nếu duyệt → Giáo viên có thể đăng nhập
Nếu từ chối → Giáo viên không thể đăng nhập
```

---

## 👤 Xem Chi Tiết Người Dùng

Khi nhấn "👁️ Xem" trên một người dùng, Admin có thể:
- Xem toàn bộ thông tin của người dùng:
  - Mã ID
  - Họ tên
  - Email
  - Vai trò
  - Trạng thái tài khoản
  - Ngày tạo tài khoản
  - Trạng thái duyệt (nếu là giáo viên)
- Thực hiện các hành động (chặn, bỏ chặn, xóa)

---

## 🛡️ Quản Lý Trạng Thái Tài Khoản

### Trạng Thái Chính:
1. **✅ Hoạt Động**: Tài khoản bình thường, có thể đăng nhập
2. **🚫 Bị Chặn**: Không thể đăng nhập, bị khóa bởi Admin

### Trạng Thái Giáo Viên:
1. **⏳ Chờ Duyệt**: Đang chờ Admin xem xét
2. **✅ Đã Duyệt**: Đã được phê duyệt, có thể sử dụng
3. **❌ Bị Từ Chối**: Đơn xin bị từ chối

---

## ⚙️ Các Điểm Lưu Ý

1. **Bảo mật mật khẩu Admin**: Thay đổi mật khẩu ngay sau lần đăng nhập đầu tiên
2. **Duyệt giáo viên**: Kiểm tra thông tin giáo viên trước khi duyệt
3. **Chặn người dùng**: Chỉ chặn khi có lý do chính đáng (spam, vi phạm quy tắc)
4. **Xóa tài khoản**: Cần cẩn thận vì không thể hoàn tác

---

## 📞 Hỗ Trợ & Liên Hệ

Nếu có vấn đề hoặc câu hỏi, vui lòng liên hệ với quản trị viên hệ thống.

---

**Cập nhật lần cuối**: 15/05/2026
