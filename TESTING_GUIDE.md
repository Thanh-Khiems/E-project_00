# MediConnect Testing Guide

> Practical QA and developer testing documentation for the **MediConnect** system.  
> This guide is intended for manual testers, developers, and future contributors who need a clear structure for verifying behavior and expanding test coverage.

---

## Table of Contents

- [1. Purpose](#1-purpose)
- [2. Test Scope](#2-test-scope)
- [3. Current Automation Status](#3-current-automation-status)
- [4. Test Environment Setup](#4-test-environment-setup)
- [5. Recommended Test Accounts](#5-recommended-test-accounts)
- [6. Test Strategy and Priority](#6-test-strategy-and-priority)
- [7. Recommended Test Data Baseline](#7-recommended-test-data-baseline)
- [8. Environment and Installation Checklist](#8-environment-and-installation-checklist)
- [9. Functional Test Cases by Module](#9-functional-test-cases-by-module)
- [10. Permission and Security Checks](#10-permission-and-security-checks)
- [11. Data Integrity Checks](#11-data-integrity-checks)
- [12. Regression Smoke Checklist](#12-regression-smoke-checklist)
- [13. Suggested Automation Roadmap](#13-suggested-automation-roadmap)
- [14. Defect Reporting Template](#14-defect-reporting-template)
- [15. Summary](#15-summary)

---

## 1. Purpose

This document explains how to test the MediConnect system in a structured way.

It is intended to help the team:

- verify that the application can be installed and run locally
- validate the main business flows manually
- perform consistent regression checks after code changes
- create a foundation for future automated testing

This guide is also written so that **new team members can join the project and start testing immediately**.

---

## 2. Test Scope

### In scope

- public pages
- authentication
- user profile management
- doctor verification workflow
- doctor list and appointment booking
- appointment lifecycle
- prescription creation
- blog management
- admin modules
- file uploads
- validation and edge cases
- role and permission checks

### Not currently a high-priority scope

- performance benchmarking
- penetration testing
- API testing, because the project currently does not expose a separate public API layer
- browser/device matrix testing beyond normal responsive smoke checks

---

## 3. Current Automation Status

The current project contains only the default Laravel sample tests:

- `tests/Feature/ExampleTest.php`
- `tests/Unit/ExampleTest.php`

### What this means

There is currently **no meaningful automated coverage** for:

- registration and login workflow
- doctor approval workflow
- booking validation rules
- appointment confirmation/cancellation
- prescription creation
- admin CRUD modules
- role-based access protection

### Practical impact

For now, quality assurance depends mainly on:

- manual testing
- regression checklists
- careful verification of permissions and data consistency

---

## 4. Test Environment Setup

### 4.1 Required software

- PHP `8.3+`
- Composer
- Node.js + npm
- MySQL or SQLite
- web browser for manual testing

### 4.2 Required PHP extensions

At minimum, ensure these extensions are available:

- `dom`
- `json`
- `libxml`
- `mbstring`
- `tokenizer`
- `xml`
- `xmlwriter`

### 4.3 Standard setup commands

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

For frontend development:

```bash
npm run dev
```

### 4.4 Important environment notes from the inspected snapshot

During source inspection, the following command failed:

```bash
php artisan test
```

because the environment was missing these PHP extensions:

- `dom`
- `mbstring`
- `xml`
- `xmlwriter`

This is an **environment issue**, not direct proof of a business-logic defect in the app.

### Recommendation

Before starting formal QA, first verify:

- PHP version is correct
- required extensions are enabled
- database connection works
- migrations and seeders run successfully

---

## 5. Recommended Test Accounts

### 5.1 Admin

| Email | Password |
|---|---|
| `admin@gmail.com` | `Admin@123456` |

### 5.2 Doctors

| Email | Password |
|---|---|
| `doctor1@gmail.com` | `Doctor@123456` |
| `doctor2@gmail.com` | `Doctor@123456` |
| `doctor3@gmail.com` | `Doctor@123456` |
| `doctor4@gmail.com` | `Doctor@123456` |

### 5.3 Users

| Email | Password |
|---|---|
| `customer1@gmail.com` | `User@123456` |
| `customer2@gmail.com` | `User@123456` |

### 5.4 Additional recommended manual accounts

Create these manually during testing:

- **new user account** with no doctor verification request
- **user with pending doctor verification request**
- **user with rejected doctor verification request**
- **user with completed appointment and review**
- **doctor with at least one active schedule**
- **doctor with no schedules** for negative tests

---

## 6. Test Strategy and Priority

### 6.1 Priority levels

#### P1 - Critical flows

- registration
- login/logout
- doctor verification submission
- doctor approval/rejection
- appointment booking
- doctor confirmation/cancellation
- prescription creation
- role and permission separation

#### P2 - Important supporting flows

- user profile update
- avatar upload
- specialty and patient admin management
- medication catalog management
- location management
- blog admin management

#### P3 - Lower priority but still important

- UI consistency
- filter and sort behavior
- copy/content validation
- non-critical edge-case polish

### 6.2 Recommended execution order

1. environment setup
2. login and seeded accounts
3. public pages
4. registration
5. profile module
6. doctor verification
7. admin approval/rejection
8. schedule creation
9. booking flow
10. appointment lifecycle
11. prescription flow
12. review flow
13. admin CRUD modules
14. regression smoke test

---

## 7. Recommended Test Data Baseline

After `php artisan migrate --seed`, the project should contain:

- 1 admin account
- 4 doctor accounts
- 2 normal user accounts
- 3 specialties
- 3 fixed degree definitions in code
- 3 medicine types
- 3 medications

### Additional test data to prepare manually

- 1 doctor verification request in `pending`
- 1 doctor verification request in `rejected`
- 1 pending appointment
- 1 confirmed appointment
- 1 completed appointment
- 1 completed appointment with review
- 1 cancelled appointment
- 1 blog draft
- 1 published blog with thumbnail
- 1 extra medicine type with multiple medications

---

## 8. Environment and Installation Checklist

| ID | Test case | Steps | Expected result |
|---|---|---|---|
| TC-ENV-01 | Dependency installation | Run `composer install` and `npm install` | Dependencies install without blocking errors |
| TC-ENV-02 | App key generation | Run `php artisan key:generate` | `APP_KEY` is generated |
| TC-ENV-03 | Migration | Run `php artisan migrate` | Database schema is created successfully |
| TC-ENV-04 | Seeding | Run `php artisan migrate --seed` | Seeded accounts and sample data are created |
| TC-ENV-05 | Frontend build | Run `npm run build` | Assets build successfully |
| TC-ENV-06 | Application startup | Run `php artisan serve` and open homepage | Application loads successfully |
| TC-ENV-07 | Storage link | Run `php artisan storage:link` if needed | Public storage symlink works for storage-backed files |
| TC-ENV-08 | Automated test runner | Run `php artisan test` | Either tests run, or missing extension issues are clearly resolved beforehand |

---

## 9. Functional Test Cases by Module

### 9.1 Public pages

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-PUB-01 | Homepage loads | Visit `/` while logged out | Homepage displays hero, doctor/blog/feedback sections or equivalent content |
| TC-PUB-02 | About page loads | Visit `/about` | About content is displayed |
| TC-PUB-03 | Services page loads | Visit `/services` | Services content is displayed |
| TC-PUB-04 | Blog listing loads | Visit `/blog` | Blog list and featured content are visible |
| TC-PUB-05 | Blog detail while logged out | Visit `/blog/{slug}` logged out | User is redirected to login or blocked by auth |
| TC-PUB-06 | Homepage redirect for authenticated user | Log in as `user` or `doctor`, then open `/` | User is redirected to the correct dashboard |

---

### 9.2 Authentication

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-AUTH-01 | Register valid account | Submit registration with valid location and unique email | Account is created, user is logged in, redirected to user dashboard |
| TC-AUTH-02 | Duplicate email registration | Re-register using an existing email | Validation error appears |
| TC-AUTH-03 | Invalid location selection | Submit inconsistent province/district/ward | Request is rejected with invalid location error |
| TC-AUTH-04 | Login as user | Sign in with `customer1@gmail.com` | Redirect to user dashboard |
| TC-AUTH-05 | Login as doctor | Sign in with `doctor1@gmail.com` | Redirect to doctor dashboard |
| TC-AUTH-06 | Login as admin | Sign in with `admin@gmail.com` | Redirect to `/admin` |
| TC-AUTH-07 | Wrong password | Submit wrong password | Authentication error message appears |
| TC-AUTH-08 | Logout | Click logout | Session is invalidated and user returns to homepage |
| TC-AUTH-09 | Forgot password OTP generation | Submit existing email on forgot password screen | Redirects to OTP screen and shows generated demo OTP |
| TC-AUTH-10 | Forgot password invalid OTP | Enter wrong OTP | Validation error or incorrect OTP message |
| TC-AUTH-11 | Forgot password success | Enter correct OTP and reset password | User can log in with the new password |

---

### 9.3 User profile

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-PRO-01 | View profile | Log in as user and open `/user/profile` | Profile info, appointments, completed appointments are visible |
| TC-PRO-02 | Update basic profile | Change full name, phone, location, address | User row is updated and patient profile remains synchronized |
| TC-PRO-03 | Partial invalid location | Submit only province or incomplete location set | Request is rejected with location completeness message |
| TC-PRO-04 | Change password success | Provide correct current password and matching new password | Password is updated |
| TC-PRO-05 | Change password wrong current password | Submit invalid current password | Error is shown |
| TC-PRO-06 | Upload valid avatar | Upload supported image under 2 MB | File is stored and avatar updates |
| TC-PRO-07 | Upload invalid avatar | Upload unsupported file or oversized file | Validation error is shown |
| TC-PRO-08 | Replace avatar | Upload another valid avatar | Old file is removed and new file is used |

---

### 9.4 Doctor verification workflow

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-VER-01 | Submit valid doctor verification | Log in as a normal user and submit all required data/files | Doctor record is created or updated with `pending` status |
| TC-VER-02 | Missing required document | Submit form without one of the required images | Validation error is shown |
| TC-VER-03 | Invalid degree selection | Submit an unsupported degree value | Validation fails |
| TC-VER-04 | Duplicate pending request | Submit a second request while one is already pending | System blocks the request |
| TC-VER-05 | Specialty auto-create | Submit a specialty that does not yet exist | Specialty is created and linked |

---

### 9.5 Admin doctor approval

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-ADM-DR-01 | View pending approvals | Open `/admin/doctor-approvals` | Pending doctor applications are listed |
| TC-ADM-DR-02 | Approve doctor | Approve a pending application | Doctor approval status becomes approved, user role becomes `doctor` |
| TC-ADM-DR-03 | Reject doctor | Reject a pending application with note | Doctor becomes rejected, user role remains/returns to `user`, rejection reason is saved |
| TC-ADM-DR-04 | Toggle doctor status | Lock or unlock an approved doctor | `status` changes between `active` and `inactive` |
| TC-ADM-DR-05 | Delete doctor profile | Delete doctor profile from admin | Doctor record is removed and linked user role reverts to `user` |

---

### 9.6 Schedule management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-SCH-01 | Create valid schedule | Log in as approved doctor and create schedule | Schedule is saved successfully |
| TC-SCH-02 | Invalid end date | Set `end_date < start_date` | Validation error |
| TC-SCH-03 | Invalid time range | Set `end_time <= start_time` | Validation error |
| TC-SCH-04 | No days selected | Submit without work days | Validation error |
| TC-SCH-05 | Update schedule | Edit existing schedule | Changes persist correctly |
| TC-SCH-06 | Delete schedule | Delete schedule | Record is removed |

---

### 9.7 Doctor list and booking

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-BKG-01 | Doctor list loads | Open `/doctor` or `/doctor-list` | Approved active doctors are shown |
| TC-BKG-02 | Filter by specialty | Choose specialty filter | Only matching doctors are shown |
| TC-BKG-03 | Filter by city | Choose city filter | Only matching city doctors are shown |
| TC-BKG-04 | Search by keyword | Search by doctor name/email/phone relevance | Matching results are returned |
| TC-BKG-05 | Booking page loads | Open `/doctor-booking/{doctor}` as logged-in user | Available future slots are shown |
| TC-BKG-06 | Book valid slot | Select valid slot and submit | Appointment is created with `pending` status |
| TC-BKG-07 | Book same slot twice | Try booking the same doctor/time again as same user | System blocks duplicate booking |
| TC-BKG-08 | Book over capacity | Fill schedule to `max_patients`, then book once more | Capacity error is shown |
| TC-BKG-09 | Book past slot | Manipulate request to use a past time | Request is rejected |
| TC-BKG-10 | Book beyond 7 days | Manipulate request to use date beyond next 7 days | Request is rejected |

---

### 9.8 Appointment lifecycle

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-APP-01 | Patient appointment list | Open `/my-appointments` | Current-week appointments are listed |
| TC-APP-02 | Doctor appointment list | Open `/doctor-appointments` as doctor | Doctor’s appointments and stats are visible |
| TC-APP-03 | Confirm pending appointment | Doctor confirms pending appointment | Status becomes `confirmed` |
| TC-APP-04 | Confirm already confirmed appointment | Confirm appointment already in confirmed state | Operation stays valid without corrupting state |
| TC-APP-05 | Cancel pending appointment | Doctor/admin cancels | Status becomes `cancelled` |
| TC-APP-06 | Cancel completed appointment | Attempt to cancel completed appointment | System blocks action |
| TC-APP-07 | Complete cancelled appointment | Attempt to complete a cancelled appointment | System blocks action |
| TC-APP-08 | Expired appointment cleanup | Access screens after an expired non-completed appointment exists | Expired appointment is removed |
| TC-APP-09 | Unauthorized doctor action | Doctor tries to manage another doctor’s appointment | Action is blocked |
| TC-APP-10 | Admin view appointment details | Open admin appointment detail | Doctor/patient/history/prescription data is visible |

---

### 9.9 Prescription flow

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-RX-01 | Open prescription screen for confirmed appointment | Doctor opens prescription creation route | Form loads successfully |
| TC-RX-02 | Open prescription screen for cancelled appointment | Try with cancelled appointment | User is redirected with error |
| TC-RX-03 | Save valid prescription | Enter diagnosis and at least one medication item | Prescription is created and appointment becomes `completed` |
| TC-RX-04 | Save prescription with no items | Submit empty items array | Validation error |
| TC-RX-05 | Save prescription with invalid medication | Use invalid medication ID | Validation error |
| TC-RX-06 | Unauthorized prescription access | Another doctor tries to access same appointment | Request is blocked with 403 |

---

### 9.10 Review flow

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-REV-01 | Submit valid review | Patient reviews their own completed appointment | Review is saved |
| TC-REV-02 | Review non-completed appointment | Try reviewing pending or confirmed appointment | Action is rejected |
| TC-REV-03 | Review another user’s appointment | Manipulate request | Action is rejected |
| TC-REV-04 | Submit second review | Review same appointment again | Action is rejected |
| TC-REV-05 | Rating out of range | Submit `0` or `6` | Validation error |

---

### 9.11 Blog module

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-BLOG-01 | Admin creates published blog | Create blog with valid content and status=published | Blog is saved and visible in listing |
| TC-BLOG-02 | Admin creates draft blog | Save blog as draft | Blog exists but is not treated as published |
| TC-BLOG-03 | Blog slug uniqueness | Create second blog with same slug | Validation error |
| TC-BLOG-04 | Upload valid thumbnail | Attach valid image | Thumbnail file is stored |
| TC-BLOG-05 | Future publish date normalization | Save published blog with future `published_at` | Stored publish time is normalized to now |
| TC-BLOG-06 | Delete blog | Delete existing blog | Blog and thumbnail are removed |

---

### 9.12 Specialty, patient, staff, location, medication

#### Specialty management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-SPC-01 | Create specialty | Add new specialty | Record is created |
| TC-SPC-02 | Update specialty | Edit specialty fields | Changes persist |
| TC-SPC-03 | Delete specialty | Delete a specialty | Record is removed |

#### Degree management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-DEG-01 | Open degree management | Visit admin degree page | Fixed code-defined degree list is displayed |
| TC-DEG-02 | Attempt create/update/delete degree | Use UI actions if available | System reports that degree list is hard-coded |

#### Patient management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-PAT-01 | View patient list | Open admin patient page | Patients are listed |
| TC-PAT-02 | Edit patient data | Update patient-linked user profile fields | User and patient data stay aligned |
| TC-PAT-03 | Convert patient to admin | Change role to admin | Staff record is created/updated |
| TC-PAT-04 | Delete patient | Delete patient record | Patient record is removed according to current implementation |

#### Location management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-LOC-01 | View locations page | Open admin locations page | Existing location cities are listed |
| TC-LOC-02 | Create location city | Add city with districts/wards structure | Record is stored correctly |
| TC-LOC-03 | Update location city | Edit city/district JSON data | Changes persist |
| TC-LOC-04 | Delete location city | Delete location | Record is removed if allowed by UI flow |

#### Medication and medicine type management

| ID | Scenario | Steps | Expected result |
|---|---|---|---|
| TC-MED-01 | Create medicine type | Add new medicine type | Record is created |
| TC-MED-02 | Delete used medicine type | Try deleting a type that still has medications | System blocks deletion |
| TC-MED-03 | Create medication | Add medication linked to medicine type | Record is created |
| TC-MED-04 | Update medication | Edit medication data | Changes persist |
| TC-MED-05 | Delete unused medication | Delete medication with no prescription usage | Record is removed |
| TC-MED-06 | Delete used medication | Delete medication already referenced by prescription items | System blocks deletion |

---

## 10. Permission and Security Checks

These checks are especially important because the app is role-driven.

| ID | Check | Expected result |
|---|---|---|
| SEC-01 | Guest opens admin routes | Redirect to login |
| SEC-02 | Normal user opens admin routes | Redirect away with permission error |
| SEC-03 | Normal user accesses doctor-only appointment management | Blocked |
| SEC-04 | Doctor accesses another doctor’s appointment or prescription route | Blocked |
| SEC-05 | User submits review for another user’s appointment | Blocked |
| SEC-06 | User books appointment without login | Blocked by auth |
| SEC-07 | File upload validation | Invalid mime types or oversize files are rejected |
| SEC-08 | Unsafe redirect attempts in login redirect parameter | Should not redirect to unsafe external path |
| SEC-09 | Admin-only content via direct URL | Must remain protected by middleware |

---

## 11. Data Integrity Checks

After functional tests, verify the database and file system where applicable.

| ID | Check | Expected result |
|---|---|---|
| DI-01 | Registration creates patient profile | New user has synchronized patient row |
| DI-02 | Profile update sync | Patient profile reflects user changes |
| DI-03 | Doctor verification pending state | Doctor row and user verification flags are both updated |
| DI-04 | Doctor approval | Doctor row approved and user role changes to `doctor` |
| DI-05 | Booking creates appointment | Appointment row contains correct doctor, patient, schedule, times |
| DI-06 | Prescription completion | Appointment becomes completed and prescription rows are inserted |
| DI-07 | Review uniqueness | One appointment cannot have more than one review |
| DI-08 | Avatar replacement | Old avatar file is removed and new file exists |
| DI-09 | Blog thumbnail deletion | Removing blog also removes thumbnail if applicable |
| DI-10 | Medication deletion restriction | Used medications stay protected |

---

## 12. Regression Smoke Checklist

Run this checklist after major changes or before demo/release.

- homepage loads
- login works for admin, doctor, user
- user dashboard opens
- doctor dashboard opens
- admin dashboard/routes open
- doctor list displays approved doctors
- booking valid appointment works
- duplicate booking is blocked
- doctor confirms appointment
- doctor completes appointment via prescription flow
- patient can see completed appointment
- patient can submit review once
- admin can approve/reject doctor request
- admin can create/update/delete blog
- admin can manage medication catalog
- avatar upload still works
- no broken images on homepage/profile/blog

---

## 13. Suggested Automation Roadmap

The best next step is to add **Feature tests** for the highest-risk business flows.

### Suggested order

1. **Authentication tests**
   - register
   - login
   - password reset

2. **Doctor verification tests**
   - submit verification
   - approve
   - reject

3. **Booking tests**
   - valid booking
   - duplicate slot protection
   - capacity limit
   - past slot rejection
   - beyond-7-days rejection

4. **Appointment lifecycle tests**
   - confirm
   - cancel
   - completion guard rules

5. **Prescription tests**
   - successful prescription creation
   - validation for medicine items
   - state transition to completed

6. **Authorization tests**
   - admin-only routes
   - doctor-only appointment actions
   - review ownership

### Suggested technical approach

- use Laravel Feature tests
- use factories/seeders for deterministic setup
- use `RefreshDatabase`
- separate happy-path and permission tests
- add file upload fakes for avatar/blog/verification documents

---

## 14. Defect Reporting Template

Use the template below for consistent bug tracking.

```md
### Bug Title
Short, specific summary

### Environment
- Branch:
- Commit:
- PHP version:
- Database:
- Browser:

### Preconditions
Describe required seeded data, account, or setup.

### Steps to Reproduce
1.
2.
3.

### Expected Result
What should happen.

### Actual Result
What actually happened.

### Severity
- Critical / High / Medium / Low

### Notes
Optional extra details, screenshots, logs, SQL state, stack trace, etc.
```

---

## 15. Summary

MediConnect already supports a meaningful end-to-end business flow:

- registration
- user profile management
- doctor verification
- doctor approval
- schedule management
- appointment booking
- prescription issuance
- appointment completion
- doctor review
- admin operations

However, the current project still depends heavily on **manual testing** because automated coverage is almost empty.

The most important QA focus areas are:

- role protection
- booking validation
- appointment state transitions
- prescription completion
- data synchronization between user, patient, and doctor records

This guide should be updated whenever new modules, states, permissions, or data relationships are introduced.
