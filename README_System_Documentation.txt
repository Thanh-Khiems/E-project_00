SYSTEM DOCUMENTATION - MEDICONNECT
=================================

1. OVERVIEW
-----------
Project name: MediConnect
Project type: Medical appointment booking and management web system
Platform: Laravel 13 + PHP 8.3+ + Vite + Tailwind CSS 4
Application style: Monolithic server-rendered web application using Blade templates

System goals:
- Provide a public-facing healthcare website with service information and health blog content.
- Allow users to register, log in, manage their profile, and book appointments with doctors.
- Allow doctors to manage work schedules, review appointments, complete consultations, and issue prescriptions.
- Allow administrators to approve doctors and manage core catalog and operational data across the system.

Primary user groups:
- Guest visitors: browse the homepage, about page, services, doctor listings, and blog overview.
- Patients / normal users: register, log in, manage profile, book appointments, view appointment history, and review doctors.
- Doctors: manage schedules, review appointments, confirm/cancel visits, complete consultations, and issue prescriptions.
- Administrators: approve doctors, manage specialties, degrees, patients, staff, locations, appointments, blogs, and medications.

2. TECHNOLOGY STACK
-------------------
Backend:
- PHP 8.3 or above (composer.json requires ^8.3)
- Laravel Framework 13
- Eloquent ORM
- Blade Template Engine

Frontend:
- Vite 8
- Tailwind CSS 4
- Axios
- Traditional server-rendered UI, not a SPA

Testing and developer tools:
- PHPUnit 12
- Laravel Pint
- Laravel Pail
- concurrently

3. IMPORTANT DIRECTORY STRUCTURE
--------------------------------
app/
- Http/Controllers/: request handling for each module
- Models/: core domain models
- Services/LocationService.php: logic for province/city, district, and ward/commune data handling

config/
- locations.php: fallback sample location data

database/
- migrations/: schema creation and update scripts
- seeders/: admin/demo accounts and sample data

resources/views/
- admin/: admin UI
- pages/: public, user, and doctor views
- partials/, components/: reusable Blade parts

routes/
- web.php: main web route definitions

public/
- images/: static images
- uploads/: public uploads such as avatars and blog thumbnails
- storage/: public storage symlink / public disk content if created

tests/
- currently only contains the default example tests for Feature and Unit

4. ARCHITECTURAL COMPONENTS
---------------------------
4.1. Controller layer
The project contains 22 controllers grouped into the following areas:
- AuthController: registration, login, forgot password, reset password, logout
- HomeController, BlogController: public-facing content
- DashboardController: user and doctor dashboards
- AppointmentController: booking, listing, confirmation, cancellation, review, and prescription entry navigation
- ScheduleController: doctor schedule CRUD
- User/ProfileController: profile, password, avatar, doctor verification submission
- User/DoctorListController, User/DoctorBookingController: doctor search and booking pages
- Admin/*Controller: admin management modules
- Doctor/PrescriptionController: prescription creation and saving

4.2. Model layer
Main models in the system:
- User
- Doctor
- Patient
- Staff
- Specialty
- Degree
- Schedule
- Appointment
- AppointmentReview
- Prescription
- PrescriptionItem
- Medication
- MedicineType
- Blog
- LocationCity

4.3. View layer
- Around 59 Blade view files
- Separate areas for admin, user, doctor, and public pages
- No dedicated public JSON/API layer at the moment

5. AUTHORIZATION AND ROLES
--------------------------
Roles are stored in users.role with 3 main values:
- user: patient / standard user
- doctor: approved doctor
- admin: administrator

Route protection:
- Admin routes use auth + AdminMiddleware
- Logged-in user and doctor routes use auth middleware
- AdminMiddleware:
  + redirects guests to login
  + blocks non-admin users and redirects them to the user dashboard

Business rule notes:
- A user submitting a doctor verification request does not become a doctor immediately.
- The role changes to doctor only after admin approval.
- If the request is rejected, the user remains or returns to the user role.

6. CORE BUSINESS MODULES
------------------------
6.1. Public pages module
Main routes:
- /
- /about
- /services
- /blog
- /blog/{slug}

Behavior:
- The homepage displays featured blogs, featured doctors, and recent feedback.
- If an authenticated user lands on the homepage:
  + doctor role -> redirected to doctor dashboard
  + user role -> redirected to user dashboard
- Blog detail pages are currently protected by auth middleware, so users must log in to read blog detail pages.

6.2. Authentication module
Registration:
- A new user account is created with full name, email, phone, gender, date of birth, address, and password.
- Default role is user.
- After registration, the system synchronizes the patient record through Patient::syncFromUser().

Login:
- Successful login redirects by role:
  + admin -> /admin
  + doctor -> doctor dashboard
  + user -> user dashboard

Forgot password:
- Current flow is 3 steps: email -> OTP -> reset password
- OTP is currently generated and exposed through a flash/demo-style mechanism rather than being sent by real email
- This is a demo/internal flow and should be upgraded before production use

6.3. User profile module
Features:
- View and update basic profile information
- Change password
- Upload avatar
- View upcoming appointments
- View completed appointments
- Submit a doctor verification request

Avatar handling:
- Files are stored in public/uploads/avatars
- The system deletes the old avatar when replacing it if the old file exists

Doctor verification request:
The user must provide:
- doctor name
- date of birth
- citizen ID number
- phone number
- degree
- specialty
- years of experience
- city

Required uploads:
- citizen ID front image
- citizen ID back image
- degree image

Storage and effects:
- Files are stored in storage/app/public/doctor-verifications
- A Doctor record is created or updated with approval_status = pending
- user.doctor_verification_status is set to pending

6.4. Doctor management from the user side
Doctor list:
- Supports filtering by specialty, city, and keyword
- Only shows doctors where:
  + approval_status = approved
  + status = active
- Sorting prioritizes featured doctors, more experienced doctors, and newer entries
- Includes review count and average rating

Doctor booking page:
- Loads active schedules for the selected doctor
- Builds appointment slots only for the next 7 days
- Excludes time slots that are already in the past
- Computes booked_count, remaining_slots, and is_full
- Booking form values follow the format: schedule_id|YYYY-MM-DD

6.5. Appointment booking and lifecycle
Booking rules:
- User must be authenticated
- Booking submission receives doctor_id and selected_slot
- The system validates:
  + slot format is valid
  + schedule belongs to the doctor
  + booking is not in the past
  + booking is within the next 7 days only
  + no duplicate booking for the same patient on the same slot unless the prior booking was cancelled
  + max_patients is not exceeded
- On success, an appointment is created with status = pending

Appointment statuses:
- pending
- confirmed
- completed
- cancelled

Important cleanup rule:
- Expired appointments are purged when:
  + the appointment is not completed
  + the date/time slot has already passed
- The system calls Appointment::purgeExpired() from multiple screens to remove old expired records

Confirming and cancelling appointments:
- Only the owning doctor or an admin can perform these actions
- confirmed is allowed from pending or confirmed states
- completed appointments cannot be cancelled
- cancelled appointments cannot be completed

Completing a consultation:
- Clicking complete redirects the doctor to the prescription creation page
- The appointment is actually finalized when the prescription is saved successfully

Doctor review rules:
- Only the patient who owns the appointment can review it
- Only completed appointments can be reviewed
- Each appointment can have at most one review
- Rating range is 1 to 5

6.6. Doctor schedule module
Approved doctors can:
- create schedules
- update schedules
- delete schedules

Schedule fields:
- doctor_id
- start_date, end_date
- type
- days (stored as a string such as Mon,Tue,Fri)
- start_time, end_time
- max_patients
- location
- notes

Validation rules:
- end_date >= start_date
- end_time > start_time
- at least one day must be selected
- max_patients >= 1 when provided

6.7. Prescription module
Doctors create prescriptions for their own appointments.
Conditions:
- Appointment must belong to the logged-in doctor
- Appointment must not be cancelled
- Appointment must be confirmed or completed to access the prescription page

When saving a prescription:
- A database transaction is used
- Appointment is updated with:
  + diagnosis
  + doctor_advice
  + status = completed
  + completed_at = now
- Prescription is created with status = issued
- Prescription items are inserted

Prescription data includes:
- diagnosis
- doctor advice
- prescription notes
- medicine list, dosage, frequency, duration, quantity, instructions, and notes

6.8. Blog module
Public side:
- Displays blogs stored in the database plus 3 hardcoded fallback blog items
- Supports featured blog and related blogs
- If an admin saves a future publish date, it is normalized back to now

Admin side:
- Create, edit, delete blogs
- Upload thumbnail to public/uploads/blogs
- Status values: draft / published
- Supports slug, excerpt, content, is_featured, and published_at

6.9. Admin module
6.9.1. Doctor management
- Doctor list
- Filtering by keyword, specialty, status, approval_status
- Approve or reject doctor verification requests
- Toggle doctor active/inactive status
- View doctor details, reviews, and statistics
- Delete doctor profiles

6.9.2. Specialty management
- Specialty CRUD
- status: active / inactive
- is_featured support
- doctor count and appointment statistics by specialty

6.9.3. Degree management
- Degree CRUD
- Default seeded values include Master and Doctorate
- If a degree name changes, the system updates the degree string currently stored on doctors
- A degree cannot be deleted if doctors are still using it

6.9.4. Patient management
- Patient list with statistics
- View, edit, delete patient records
- Synchronizes user role = user to patient profile
- If role changes to admin, the patient is converted into staff and the patient profile is removed

6.9.5. Staff management
- Staff is currently mainly synchronized from users with admin role
- Includes search and statistics by status/role

6.9.6. Location management
- Manages the location_cities table
- Each city contains a name and districts JSON
- Each district contains a list of wards/communes
- If location_cities has not been migrated yet, the admin is warned to run migration first

6.9.7. Appointment management
- Appointment list
- Filtering by doctor, status, and keyword
- View appointment details
- View completed patient visit history

6.9.8. Medication and medicine type management
- Medication CRUD
- MedicineType CRUD
- Cannot delete a medicine type if medications still reference it
- Cannot delete a medication if it has already been used in prescription_items

7. DATA MODEL SUMMARY
---------------------
users table:
- id
- full_name, email, phone, gender
- province, district, ward, address_detail
- dob, avatar
- role
- doctor_verification_status, doctor_verified_at, doctor_rejection_reason
- password

doctors table:
- id, user_id, specialty_id
- name, email, phone
- degree, doctor_dob
- citizen_id, citizen_id_front, citizen_id_back, degree_image
- license_number, experience_years
- hospital, clinic_address, city, bio, consultation_fee, schedule_text
- status, is_featured
- approval_status, approval_note, verification_status
- submitted_at, approved_at, rejected_at

patients table:
- id, user_id, patient_code
- name, date_of_birth, gender, phone, email, address

staff table:
- id, user_id
- name, email, phone, role, department, shift, status

specialties table:
- id, name, description, status, is_featured

degrees table:
- id, name, description, status

schedules table:
- id, doctor_id
- start_date, end_date
- type, days
- start_time, end_time
- max_patients, location, notes

appointments table:
- id
- patient_id (references users in the current implementation)
- doctor_id, schedule_id
- appointment_date, appointment_day
- start_time, end_time
- type, location, max_patients
- status, notes
- diagnosis, doctor_advice, completed_at

appointment_reviews table:
- appointment_id, patient_id, doctor_id, rating, review, reviewed_at
- unique by appointment_id

prescriptions table:
- appointment_id, doctor_id, patient_id
- diagnosis, advice, notes, status, issued_at

prescription_items table:
- prescription_id, medication_id
- dosage, frequency, duration, quantity, instructions, notes

medications table:
- name, dosage, medicine_type_id, category

medicine_types table:
- name, description

blogs table:
- title, slug, excerpt, content, thumbnail
- status, is_featured, published_at

location_cities table:
- name, districts (JSON)

8. MAIN DATA RELATIONSHIPS
--------------------------
- User 1 - 1 Patient (in many cases where user.role = user)
- User 1 - 1 Doctor (when the user is approved as a doctor)
- User 1 - 1 Staff (for admin users)
- Specialty 1 - n Doctor
- Doctor 1 - n Schedule
- Doctor 1 - n Appointment
- User(patient) 1 - n Appointment through patient_id
- Appointment 1 - n Prescription
- Prescription 1 - n PrescriptionItem
- Appointment 1 - 1 AppointmentReview
- Medication 1 - n PrescriptionItem
- MedicineType 1 - n Medication

9. TYPICAL BUSINESS FLOWS
-------------------------
9.1. Registering and becoming a patient
Step 1. User registers an account.
Step 2. System creates user with role = user.
Step 3. System synchronizes a patient record.
Step 4. User logs in and can use patient-facing features.

9.2. User requests to become a doctor
Step 1. User logs in and opens the profile page.
Step 2. User submits the verify-doctor form with supporting documents.
Step 3. System creates or updates a Doctor record with pending status.
Step 4. Admin opens the doctor approvals area.
Step 5. Admin approves -> user.role becomes doctor.
Step 6. The doctor can start creating schedules and receiving appointments.

9.3. Booking an appointment
Step 1. User opens the doctor list.
Step 2. User selects a doctor and opens the doctor-booking page.
Step 3. User chooses a slot within the next 7 days.
Step 4. System validates overlap, expiration, and full-slot rules.
Step 5. System creates a pending appointment.

9.4. Consultation and prescription
Step 1. Doctor opens doctor appointments.
Step 2. Doctor confirms the appointment.
Step 3. During or after the visit, doctor clicks complete.
Step 4. System opens the prescription form.
Step 5. Doctor enters diagnosis and medicines.
Step 6. System saves the prescription and updates appointment to completed.
Step 7. Patient can view the result and leave a review.

10. ROUTE GROUPS
----------------
10.1. Public
- GET /
- GET /about
- GET /services
- GET /blog
- GET /blog/{slug} (currently requires auth)

10.2. Auth
- GET/POST /register
- GET/POST /login
- GET/POST /forgot-password
- GET/POST /forgot-password/otp
- GET /forgot-password/reset
- POST /forgot-password/reset
- POST /logout

10.3. User/Profile
- GET /user/profile
- PUT /user/profile
- POST /user/profile/password
- POST /user/profile/avatar
- POST /user/profile/verify-doctor
- GET /user/dashboard

10.4. Doctor list and booking
- GET /doctor
- GET /doctor-list
- GET /user/doctor-list
- GET /doctor-booking/{doctor}

10.5. Schedule
- GET /schedule
- POST /schedule
- PUT /schedule/{id}
- DELETE /schedule/{id}

10.6. Appointments
- POST /appointments
- GET /my-appointments
- GET /doctor-appointments
- PATCH /appointments/{appointment}/confirm
- PATCH /appointments/{appointment}/cancel
- PATCH /appointments/{appointment}/complete
- POST /appointments/{appointment}/review
- GET /appointments/{appointment}/prescriptions/create
- POST /appointments/{appointment}/prescriptions

10.7. Admin
- /admin/doctors
- /admin/doctor-approvals
- /admin/specialties
- /admin/degrees
- /admin/patients
- /admin/staffs
- /admin/locations
- /admin/appointments
- /admin/medications
- /admin/medicine-types
- /admin/blogs

11. INSTALLATION AND RUNNING THE PROJECT
----------------------------------------
11.1. Environment requirements
- PHP 8.3+
- Composer
- Node.js + npm
- MySQL or SQLite
- Required PHP extensions for Laravel/PHPUnit, especially mbstring, dom, xml, and xmlwriter

11.2. Quick setup
Step 1. Extract the source code archive.
Step 2. Install backend dependencies
   composer install
Step 3. Install frontend dependencies
   npm install
Step 4. Create the environment file if needed
   copy .env.example .env
Step 5. Generate APP_KEY
   php artisan key:generate
Step 6. Configure the database in .env
Step 7. Run migrations and seeders
   php artisan migrate --seed
Step 8. Build the frontend or run dev mode
   npm run build
   or npm run dev
Step 9. Run the application
   php artisan serve

11.3. Available composer scripts
- composer run setup
  -> install dependencies, create .env, generate key, migrate, install npm packages, build assets
- composer run dev
  -> run server + queue + pail + vite together
- composer run test
  -> clear config and run php artisan test

11.4. Current database configuration in the source snapshot
- .env.example defaults to DB_CONNECTION=sqlite
- The .env included in the zip snapshot is configured for MySQL with database name mediconnect
=> The team should align on whether local/dev onboarding uses SQLite or MySQL.

12. SAMPLE ACCOUNTS
-------------------
Default admin account:
- admin@gmail.com / Admin@123456

Demo doctor accounts:
- doctor1@gmail.com / Doctor@123456
- doctor2@gmail.com / Doctor@123456
- doctor3@gmail.com / Doctor@123456

Demo user accounts:
- customer1@gmail.com / User@123456
- customer2@gmail.com / User@123456

Seeder commands:
- php artisan migrate --seed
or
- php artisan db:seed --class=AdminUserSeeder
- php artisan db:seed --class=DefaultDemoAccountsSeeder

13. FILE UPLOADS AND STORAGE
----------------------------
Avatar uploads:
- public/uploads/avatars

Blog thumbnails:
- public/uploads/blogs

Doctor verification files:
- storage/app/public/doctor-verifications

Recommendations:
- For production deployment, configure storage:link if files on the public disk must be web-accessible.
- Apply a clear backup policy, naming convention, and permission strategy for uploaded files.

14. CURRENT STRENGTHS OF THE SYSTEM
-----------------------------------
- The booking -> consultation -> prescription -> review flow is connected end to end.
- Clear role separation between admin, doctor, and user.
- Includes a doctor verification workflow.
- Hardcoded fallback blog data prevents empty public blog UI.
- Synchronization exists between users and patients/staff records.
- Supporting catalog modules already exist for specialty, degree, medication, medicine type, and location.

15. LIMITATIONS AND TECHNICAL NOTES
-----------------------------------
- There are currently no meaningful automated business tests; tests/ still contains only the example tests.
- Forgot-password OTP is a demo implementation and does not send a real email.
- Blog detail route currently requires authentication; this should be reviewed against product requirements.
- Expired appointments are hard-deleted rather than archived.
- Schedule days are stored as a comma-separated string, which may be limiting for more advanced querying later.
- There are two migrations that add user_id to the staff table, although hasColumn guards reduce migration risk.
- User profile updates synchronize patient data, but location validation is not as strict there as it is during registration/admin editing.

16. RECOMMENDED NEXT IMPROVEMENTS
---------------------------------
- Add Feature tests for all critical business modules.
- Extract service/business logic for booking, doctor approval, and prescriptions.
- Add real notifications or emails for OTP and doctor approval.
- Add audit logs for admin actions.
- Archive expired appointments instead of hard-deleting them.
- Build an API layer if a mobile app or decoupled frontend is needed.
- Add pagination and stronger search/filtering for the doctor list and blog module.

17. SUMMARY
-----------
MediConnect is a Laravel-based medical appointment management system with three primary roles: user, doctor, and admin. The current codebase already covers the core workflow for a simplified medical service process: account registration, doctor verification request, schedule creation, appointment booking, consultation completion, prescription issuance, and doctor review. It is a solid foundation for continuing with test coverage, business logic refinement, and production hardening.
