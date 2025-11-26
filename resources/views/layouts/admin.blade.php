<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #1e293b; /* dark slate */
            --sidebar-hover: #334155; 
            --sidebar-active: #2563eb; 
            --text-light: #f1f5f9;
            --text-muted: #94a3b8;
            --accent: #2563eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #1e293b; 
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-light);
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar .welcome {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: var(--text-light);
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 15px;
        }

        .sidebar ul li a i {
            font-size: 1.2rem;
        }

        .sidebar ul li a:hover {
            background-color: var(--sidebar-hover);
            color: var(--text-light);
            padding-left: 20px;
        }

        .sidebar ul li.active a {
            background-color: var(--sidebar-active);
            color: #fff;
            font-weight: 600;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            min-height: 100vh;
            background-color: #f9fafb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar-admin')

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
