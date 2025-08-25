<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - DriverZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: url('/images/tricycle.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 45px 35px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            color: #fff;
            animation: fadeIn 1s ease forwards;
            opacity: 0;
            position: relative;
        }

        @keyframes fadeIn {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: none;
            border-color: #0d6efd;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-label {
            color: #ffffff;
            font-weight: 500;
        }

        .password-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            background-color: rgba(13, 110, 253, 0.85);
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            position: relative;
        }

        .btn-login:hover {
            background-color: rgba(11, 94, 215, 1);
            transform: scale(1.02);
        }

        .custom-loader {
            border: 2px solid transparent;
            border-top: 2px solid #fff;
            border-right: 2px solid #fff;
            border-radius: 50%;
            width: 1.2rem;
            height: 1.2rem;
            animation: spin 0.6s linear infinite;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .btn-loading .custom-loader {
            display: inline-block;
        }

        .btn-loading .btn-text {
            visibility: hidden;
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        .exit-button {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .login-card h2 {
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 28px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card">
    <a href="{{ route('welcome') }}" class="btn btn-outline-light btn-sm exit-button">
        <i class="bi bi-box-arrow-left me-1"></i> Exit
    </a>

    <h2><i class="bi bi-person-circle me-2"></i> Login</h2>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" id="email" class="form-control" placeholder="Enter your email" required autofocus>
        </div>

        <div class="mb-4 password-group">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" id="password" class="form-control" placeholder="Enter your password" required>
            <i class="bi bi-eye toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" id="loginBtn" class="btn btn-login w-100">
            <span class="btn-text"><i class="bi bi-box-arrow-in-right me-1"></i> Login</span>
            <span class="custom-loader" role="status" aria-hidden="true"></span>
        </button>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.classList.add('btn-loading');
        btn.disabled = true;
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePassword.classList.toggle('bi-eye');
        togglePassword.classList.toggle('bi-eye-slash');
    });

    @if(session('status'))
        Swal.fire({
            icon: 'info',
            title: 'Notice',
            text: '{{ session('status') }}',
            confirmButtonColor: '#0d6efd'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
        }).then(() => {
            window.location.href = "{{ url('/dashboard') }}";
        });
    @endif

    @if($errors->any())
        document.getElementById('password').value = '';
        document.getElementById('password').focus();

        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>

</body>
</html>
