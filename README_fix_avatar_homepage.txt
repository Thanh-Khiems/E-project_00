Applied changes:
1. Fixed the patient and doctor avatar path issue so uploaded images display correctly.
2. Added the avatar_url accessor in the User model for shared use across screens.
3. Fixed the homepage to load the correct featured/approved doctors list and increased the display count to 6.
4. Updated the featured doctors section UI on the homepage.

Updated files:
- app/Models/User.php
- app/Http/Controllers/HomeController.php
- app/Http/Controllers/DashboardController.php
- resources/views/pages/user/profile.blade.php
- resources/views/components/doctors-section.blade.php
- resources/views/components/feedback-section.blade.php
- resources/views/pages/user/doctor-list.blade.php
- resources/views/pages/user/doctor-booking.blade.php
- resources/css/app.css

Note:
- If the actual runtime environment has not mapped public/storage to storage/app/public, run:
  php artisan storage:link
- In this test environment, the artisan route:list command cannot run because PHP is missing the mbstring extension.
