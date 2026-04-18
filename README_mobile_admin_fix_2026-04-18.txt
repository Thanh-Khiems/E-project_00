Mobile admin UI adjustments applied:

1. Reworked the admin layout for phones/tablets.
   - Desktop keeps the left sidebar.
   - Mobile now uses a Bootstrap offcanvas menu triggered by a menu button.

2. Fixed the admin content area alignment.
   - Removed the old left margin behavior that caused the admin content to shift and look cramped on mobile.

3. Improved general responsiveness for admin pages.
   - Better topbar wrapping and title scaling.
   - Buttons no longer force a fixed width.
   - Cards/panels use smaller padding on small screens.
   - Table areas scroll horizontally when needed instead of breaking the layout.
   - Action buttons stack more cleanly on narrow screens.
   - Meta/stat blocks collapse into a single column on small devices.

Files changed:
- resources/views/admin/layouts/app.blade.php
- resources/views/admin/layouts/partials/sidebar-nav.blade.php
- public/admin-ui/css/admin-hospital.css
