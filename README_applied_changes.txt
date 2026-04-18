The following files were modified/added so the admin can manage cities/locations:
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

After copying the files into the project, run:
php artisan migrate

Notes:
- The migration will create the location_cities table and import default data from config/locations.php.
- After that, the admin can add/edit/delete cities, districts, and wards at /admin/locations.
- The registration form and user profile edit form will load location data from the database.

[2026-04-14] Added patient doctor-review flow after appointment completion:
- Created appointment_reviews table + AppointmentReview model.
- Added one-review-per-appointment logic.
- Added POST route: /appointments/{appointment}/review.
- Patient can review completed appointments from patient-appointments page.
- Doctor appointments page now shows rating count, average rating, and review details.
- Admin appointments index/show now display patient reviews.
