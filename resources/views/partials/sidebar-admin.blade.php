<style>
    /* Basic reset */
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background: linear-gradient(to bottom, #38bdf8, #60a5fa, #93c5fd); /* ✅ Skyblue gradient */
        color: #fff;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    /* ✅ Admin logo container */
    .sidebar .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar .logo img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.7);
        background: white;
    }

    .sidebar .welcome {
        margin-bottom: 25px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .sidebar ul {
        list-style: none;
        padding-left: 0;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar ul li {
        margin: 10px 0;
    }

    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.2s;
    }

    .sidebar ul li a:hover {
        background-color: rgba(255, 255, 255, 0.25); /* ✅ lighter hover */
        transform: translateX(5px);
    }

    .sidebar ul li.active a {
        background-color: rgba(255, 255, 255, 0.4); /* ✅ brighter active */
        font-weight: bold;
    }

    .logout-form button {
        width: 100%;
        background-color: #0ea5e9; /* ✅ Skyblue button */
        border: none;
        color: white;
        padding: 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .logout-form button:hover {
        background-color: #0284c7; /* ✅ darker skyblue hover */
    }

    /* Responsive toggle (for mobile view) */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-toggle {
            display: block;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background-color: #38bdf8; /* ✅ skyblue toggle */
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
    }

    @media (min-width: 769px) {
        .sidebar-toggle {
            display: none;
        }
    }
</style>

<!-- Mobile Toggle Button -->
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">
    ☰
</button>

<!-- Sidebar -->
<div class="sidebar">
    <div>
        <!-- ✅ Logo -->
        <div class="logo">
            <img src="/images/admin.png" alt="Admin Logo">
        </div>

        @if(Auth::check() && Auth::user()->role === 'Admin')
            <div class="welcome">
                Welcome,<br>{{ Auth::user()->fullname }}
            </div>
        @endif

        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.manage') ? 'active' : '' }}">
                <a href="{{ route('admin.manage') }}">
                    <i class="bi bi-people"></i> Manage Users
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.view-ride') ? 'active' : '' }}">
                <a href="{{ route('admin.view-ride') }}">
                    <i class="bi bi-map"></i> View Ride
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
                <a href="{{ route('admin.complaints.index') }}">
                    <i class="bi bi-exclamation-diamond"></i> Complaints/Reports
                </a>
            </li>
           
    </div>

    <!-- Logout button at the bottom -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form mt-4">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
