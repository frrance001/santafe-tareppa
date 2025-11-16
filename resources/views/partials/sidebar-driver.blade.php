<style>
    :root {
        --sidebar-width: 260px;
        --bg-gradient: linear-gradient(180deg, #56ccf2, #2f80ed); /* Sky-blue gradient */
        --bg-hover: rgba(255, 255, 255, 0.15); /* Hover overlay */
        --accent: #ffffff; /* Active item text color */
        --text-light: #ffffff;
        --text-muted: #d0e7ff; /* Muted icon color */
        --transition-speed: 0.3s;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--bg-gradient);
        color: var(--text-light);
        position: fixed;
        top: 0;
        left: 0;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.2);
        font-family: 'Inter', sans-serif;
        z-index: 1000;
    }

    .sidebar .welcome {
        font-size: 15px;
        margin-bottom: 30px;
        color: var(--text-light);
        text-align: center;
        font-weight: 600;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    /* Profile picture style */
    .driver-profile {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 15px auto;
        display: block;
        border: 2px solid #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin-bottom: 10px;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-light);
        transition: all var(--transition-speed) ease;
        font-size: 15px;
    }

    .sidebar ul li a i {
        font-size: 18px;
        color: var(--text-muted);
        transition: color var(--transition-speed);
    }

    .sidebar ul li a:hover {
        background-color: var(--bg-hover);
        color: var(--accent);
    }

    .sidebar ul li.active a {
        background: rgba(255,255,255,0.25);
        color: var(--accent);
        font-weight: 600;
        box-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    .sidebar ul li a:hover i {
        color: var(--accent);
    }

    .sidebar-footer {
        margin-top: auto;
        font-size: 12px;
        color: var(--text-light);
        text-align: center;
        padding-top: 20px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
        }

        .sidebar ul {
            display: flex;
            flex-direction: row;
            gap: 8px;
        }

        .sidebar ul li {
            margin-bottom: 0;
        }

        .sidebar ul li a {
            padding: 10px;
            font-size: 14px;
        }
    }
</style>

<div class="sidebar">
    @if(Auth::check() && Auth::user()->role === 'Driver')
        <!-- Driver profile picture -->
        <img src="{{ Auth::user()->profile_picture ?? asset('images/default-profile.png') }}" 
             alt="Driver Profile" class="driver-profile">

        <div class="welcome">
            Welcome, <strong>{{ Auth::user()->fullname }}</strong>
        </div>
    @endif

    <ul>
        <li class="{{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <a href="{{ route('driver.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
           <a href="{{ route('driver.showAvailability') }}">
                <i class="bi bi-calendar-check"></i> Set Availability
            </a>
        </li>
        <li class="{{ request()->routeIs('driver.accept-rides') ? 'active' : '' }}">
            <a href="{{ route('driver.accept-rides') }}">
                <i class="bi bi-check2-circle"></i> Accept Ride Request
            </a>
        </li>
        <li class="{{ request()->routeIs('driver.pickup') ? 'active' : '' }}">
            <a href="{{ route('driver.pickup') }}">
                <i class="bi bi-person-walking"></i> Pick Up Passenger
            </a>
        </li>
        <li>
            <a href="{{ route('driver.completed.rides') }}">Completed Rides</a>
        </li>
        
    </ul>

    <!-- Logout button at the bottom -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} DriverZone Admin
    </div>
</div>
