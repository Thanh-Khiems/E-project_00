Các file đã được sửa/thêm để admin quản lý thành phố/khu vực:
- app/Models/LocationCity.php
- app/Services/LocationService.php
- app/Http/Controllers/Admin/LocationController.php
- app/Http/Controllers/AuthController.php
- app/Http/Controllers/User/ProfileController.php
- database/migrations/2026_04_12_000001_create_location_cities_table.php
- resources/views/admin/locations/index.blade.php
- resources/views/admin/layouts/app.blade.php
- resources/views/pages/user/profile.blade.php
- routes/web.php

Sau khi chép file vào project, chạy:
php artisan migrate

Ghi chú:
- Migration sẽ tạo bảng location_cities và import dữ liệu mặc định từ config/locations.php.
- Sau đó admin có thể thêm/sửa/xóa thành phố, quận/huyện, phường/xã tại /admin/locations.
- Form đăng ký và chỉnh sửa hồ sơ user sẽ đọc dữ liệu khu vực từ database.

[2026-04-14] Added patient doctor-review flow after appointment completion:
- Created appointment_reviews table + AppointmentReview model.
- Added one-review-per-appointment logic.
- Added POST route: /appointments/{appointment}/review.
- Patient can review completed appointments from patient-appointments page.
- Doctor appointments page now shows rating count, average rating, and review details.
- Admin appointments index/show now display patient reviews.
