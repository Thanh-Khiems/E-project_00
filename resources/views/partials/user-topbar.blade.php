<div class="topbar reveal-up">
    <div class="container topbar-inner">
        <div class="topbar-left">
            <div class="topbar-item">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M4 5h16a2 2 0 0 1 2 2v.4l-10 6.25L2 7.4V7a2 2 0 0 1 2-2Zm18 4.75-9.47 5.92a1 1 0 0 1-1.06 0L2 9.75V17a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9.75Z"/>
                </svg>
                <span>mediconnect@gmail.com</span>
            </div>

            <div class="topbar-item">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.3 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.5 21 3 13.5 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.3 1l-2.2 2.2Z"/>
                </svg>
                <span>1900 115 115</span>
            </div>
        </div>

        <div class="topbar-right">
            <a href="#" class="topbar-item topbar-link">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.33 0-6 2.02-6 4.5 0 .28.22.5.5.5h11a.5.5 0 0 0 .5-.5C18 16.02 15.33 14 12 14Z"/>
                </svg>
                <span>Profile</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="topbar-item topbar-button">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M10 17a1 1 0 0 1 0-2h5.59l-1.3-1.29a1 1 0 0 1 1.42-1.42l3 3a1 1 0 0 1 0 1.42l-3 3a1 1 0 1 1-1.42-1.42L15.59 17Zm-5-11h6a1 1 0 0 1 0 2H6v8h5a1 1 0 0 1 0 2H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
