<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary: #0d6efd;
            --dark-bg: #343a40;
            --sidebar-width: 250px;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .topbar {
            background-color: var(--dark-bg);
            color: white;
            padding: 12px 20px;
            display: none;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .topbar .menu-toggle {
            font-size: 24px;
            cursor: pointer;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: rgba(33, 37, 41, 0.95);
            backdrop-filter: blur(10px);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar .welcome {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #adb5bd;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 18px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 22px;
        }

        .sidebar ul li.active a {
            background-color: var(--primary);
            color: #fff;
            font-weight: 600;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px 30px;
            transition: margin-left 0.3s ease;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Rich Text Styles */
        .main-content h1, .main-content h2, .main-content h3, .main-content h4 {
            font-weight: 700;
            color: #212529;
            margin-top: 24px;
            margin-bottom: 16px;
        }

        .main-content h1 { font-size: 2rem; }
        .main-content h2 { font-size: 1.6rem; }
        .main-content h3 { font-size: 1.3rem; }
        .main-content h4 { font-size: 1.1rem; }

        .main-content p {
            font-size: 15px;
            color: #343a40;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .main-content a {
            color: var(--primary);
            text-decoration: underline;
        }

        .main-content a:hover {
            color: #0b5ed7;
            text-decoration: none;
        }

        .main-content ul, .main-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .main-content li {
            margin-bottom: 0.5rem;
        }

        .main-content blockquote {
            padding: 12px 20px;
            margin: 20px 0;
            background-color: #e9ecef;
            border-left: 5px solid var(--primary);
            font-style: italic;
            color: #495057;
        }

        .main-content code {
            background-color: #f1f3f5;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            color: #d63384;
        }

        .main-content pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 14px;
            color: #333;
        }

        .main-content hr {
            margin: 2rem 0;
            border: none;
            border-top: 1px solid #dee2e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .topbar {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                height: 100%;
                width: 230px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Topbar for Mobile -->
    <div class="topbar d-md-none">
        <div><strong>Passenger Dashboard</strong></div>
        <div class="menu-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @include('partials.sidebar-passenger')
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    @yield('scripts')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>
