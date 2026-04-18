Changes in this version:
- Moved Profile / Logout into the mobile dropdown menu.
- Added Login / Register to the mobile menu when the user is not signed in.
- Hidden the Profile / Logout group in the mobile topbar for a cleaner layout.
- Increased the z-index and adjusted the mobile menu so About / Services content no longer overlaps it.
- Kept the Home / About / Services / Doctor / Blog / Contact links stable on mobile.
- Improved responsiveness for the Services page: large tablets/mobile show 2 columns, small phones show 1 column.

Updated files:
- resources/views/partials/navbar.blade.php
- resources/views/partials/navbar-user.blade.php
- resources/views/partials/navbar-doctor.blade.php
- resources/views/partials/user-topbar.blade.php
- resources/views/partials/doctor-topbar.blade.php
- resources/css/app.css
- resources/js/app.js
