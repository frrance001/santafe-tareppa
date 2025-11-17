<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --primary: #0d6efd;
    --sidebar-width: 250px;
    --sidebar-bg: rgba(33, 37, 41, 0.95);
}

/* GENERAL */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f8f9fa;
    transition: background 0.3s ease;
}

/* ========================== SIDEBAR OVERLAY =========================== */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(2px);
    z-index: 1045;
    opacity: 0;
    visibility: hidden;
    transition: opacity .3s ease, visibility .3s ease;
}

.sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* ========================== TOPBAR (MOBILE) =========================== */
.topbar {
    background: #f1f5f9;
    padding: 12px 20px;
    display: none;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.topbar .menu-toggle {
    font-size: 26px;
    cursor: pointer;
}

/* ========================== SIDEBAR =========================== */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    padding: 28px 18px;
    z-index: 1050;
    transition: transform .35s ease;
    transform: translateX(0);
    box-shadow: 2px 0 15px rgba(0,0,0,0.3);
}

.sidebar-content {
    overflow-y: auto;
    height: calc(100vh - 140px);
}

.sidebar.active {
    transform: translateX(0);
}

.sidebar ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.sidebar ul li {
    margin-bottom: 18px;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 15px;
    color: #fff;
    text-decoration: none;
    transition: 0.3s ease;
}

.sidebar ul li a:hover {
    background: rgba(255,255,255,0.1);
    padding-left: 22px;
}

.sidebar ul li.active a {
    background: var(--primary);
    color: white;
}

/* ========================== MAIN CONTENT =========================== */
.main-content {
    margin-left: var(--sidebar-width);
    padding: 40px 30px;
    transition: margin-left .3s ease;
    animation: fadeIn .5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Typography */
.main-content h1 { font-size: 2rem; font-weight: 700; }
.main-content h2 { font-size: 1.6rem; }
.main-content p  { font-size: 15px; color: #333; line-height: 1.6; }

/* ========================== RESPONSIVE =========================== */
@media (max-width: 768px) {
    .topbar { display: flex; }

    .sidebar {
        transform: translateX(-100%);
        width: 230px;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
        padding: 25px 20px;
    }

    .sidebar ul li a {
        font-size: 14px;
        padding: 10px 14px;
    }
}

@media (max-width: 576px) {
    .main-content h1 { font-size: 1.5rem; }
    .main-content p  { font-size: 14px; }
}
</style>

</head>
<body>

<!-- ========================== TOPBAR (Mobile) ========================== -->
<div class="topbar d-md-none">
    <strong>Passenger Dashboard</strong>
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </div>
</div>

<!-- ========================== SIDEBAR OVERLAY ========================== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ========================== SIDEBAR ========================== -->
<div class="sidebar" id="sidebar">
    @include('partials.sidebar-passenger')
</div>

<!-- ========================== MAIN CONTENT ========================== -->
<div class="main-content">
    @yield('content')
</div>

<!-- SCRIPT -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
}

// Close when clicking outside
document.getElementById("sidebarOverlay").addEventListener("click", () => {
    document.getElementById("sidebar").classList.remove("active");
    document.getElementById("sidebarOverlay").classList.remove("active");
});

// Auto-adjust layout on resize
window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
        document.getElementById("sidebar").classList.remove("active");
        document.getElementById("sidebarOverlay").classList.remove("active");
    }
});
</script>

</body>
</html>
