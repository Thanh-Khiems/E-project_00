Các chỉnh sửa đã áp dụng:
1. Sửa lỗi đường dẫn avatar của bệnh nhân và bác sĩ để ảnh upload hiển thị đúng.
2. Thêm accessor avatar_url trong User model để dùng chung cho mọi màn hình.
3. Sửa trang chủ lấy đúng danh sách bác sĩ nổi bật/đã duyệt, tăng số lượng hiển thị lên 6.
4. Cập nhật giao diện section bác sĩ nổi bật ở trang chủ.

Các file đã chỉnh:
- app/Models/User.php
- app/Http/Controllers/HomeController.php
- app/Http/Controllers/DashboardController.php
- resources/views/pages/user/profile.blade.php
- resources/views/components/doctors-section.blade.php
- resources/views/components/feedback-section.blade.php
- resources/views/pages/user/doctor-list.blade.php
- resources/views/pages/user/doctor-booking.blade.php
- resources/css/app.css

Lưu ý:
- Nếu môi trường chạy thực tế chưa map thư mục public/storage tới storage/app/public thì cần chạy:
  php artisan storage:link
- Trong môi trường kiểm tra này, lệnh artisan route:list không chạy được do PHP thiếu extension mbstring.
