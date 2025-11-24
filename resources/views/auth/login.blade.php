<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - DriverZone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Google reCAPTCHA v3 -->
  <script src="https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fff;
      position: relative;
    }

    .sakay-title {
      position: absolute;
      top: 40px;
      text-align: center;
      font-size: 3rem;
      font-weight: 800;
      background: linear-gradient(45deg, #ff9800, #ff5722, #2196f3);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: slideDown 1s ease-out;
      z-index: 2;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-50px); } 
      to { opacity: 1; transform: translateY(0); }
    }

    .login-card {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 420px;
      padding: 40px 35px;
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
      color: #333;
      animation: fadeUp 1.2s ease;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); } 
      to { opacity: 1; transform: translateY(0); }
    }

    .form-label { font-weight: 600; color: #1e3a8a; }
    .form-control { 
      border-radius: 12px;
      padding: 12px 18px;
      font-size: 15px;
      background-color: #f9fafb;
      border: 1px solid #d1d5db;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      background-color: #fff;
      border-color: #2196f3;
      box-shadow: 0 0 10px rgba(33,150,243,0.4);
    }

    .btn-login {
      border-radius: 12px;
      padding: 12px;
      background: linear-gradient(135deg, #56ccf2, #2f80ed);
      color: #fff;
      font-weight: 700;
      font-size: 16px;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      border: none;
    }
    .btn-login:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    }

    .exit-button { position: absolute; top: 15px; right: 15px; font-size: 14px; }

    .login-card h2 { 
      margin-bottom: 25px;
      font-weight: 700;
      font-size: 24px;
      text-align: center;
      color: #1e3a8a;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
    }

    .toggle-password {
      position: absolute;
      right: 16px;
      top: 70%;
      transform: translateY(-50%);
      color: #6b7280;
      cursor: pointer;
    }

    /* reCAPTCHA v3 badge in bottom-right corner */
    .grecaptcha-badge {
      position: fixed !important;
      bottom: 15px !important;
      right: 15px !important;
      z-index: 9999 !important;
      visibility: visible !important;
      opacity: 1 !important;
      transform: scale(0.85);
      transform-origin: bottom right;
    }
  </style>
</head>
<body>

<h1 class="sakay-title">Santafe Tareppa</h1>

<div class="login-card">
  <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm exit-button">
    <i class="bi bi-box-arrow-left me-1"></i> Exit
  </a>

  <h2>Login</h2>

  <form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    <div class="mb-3">
      <label for="role" class="form-label">Select Role</label>
      <select name="role" id="role" class="form-control" required>
        <option value="passenger">Passenger</option>
        <option value="driver">Driver</option>
        <option value="admin">Admin</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input name="email" type="email" id="email" class="form-control" placeholder="Enter your Gmail" required autofocus pattern="[a-zA-Z._%+-]+@gmail\.com" title="Please enter a valid Gmail address">
    </div>

    <div class="mb-4 position-relative" id="passwordField">
      <label for="password" class="form-label">Password</label>
      <input name="password" type="password" id="password" class="form-control" placeholder="Enter your password">
      <i class="bi bi-eye toggle-password" id="togglePassword"></i>
    </div>

    <!-- Hidden reCAPTCHA token -->
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">

    <button type="submit" class="btn btn-login w-100">
      <i class="bi bi-box-arrow-in-right me-1"></i> Login
    </button>
  </form>
</div>

<script>
  // Toggle password visibility
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.classList.toggle('bi-eye');
    togglePassword.classList.toggle('bi-eye-slash');
  });

  // Hide password field for non-admin
  const roleSelect = document.getElementById('role');
  const passwordField = document.getElementById('passwordField');
  if(roleSelect.value !== 'admin'){ passwordField.style.display='none'; }
  roleSelect.addEventListener('change', () => {
    passwordField.style.display = roleSelect.value==='admin'?'block':'none';
  });

  // SweetAlert2 feedback
  @if(session('error'))
    Swal.fire({ icon:'error', title:'Oops...', text:'{{ session('error') }}' });
  @endif
  @if(session('success'))
    Swal.fire({ icon:'success', title:'Success', text:'{{ session('success') }}' });
  @endif
  @if($errors->any())
    Swal.fire({ icon:'error', title:'Error', html:`<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>` });
  @endif

  // reCAPTCHA v3 page-level execution
  grecaptcha.ready(function() {
    grecaptcha.execute('6Ld7dBYsAAAAABaiWl3AlIuM6jpKdNvJSZLobRk-', {action: 'pageview'}).then(function(token) {
      console.log('Page-level reCAPTCHA token:', token);
      // token can be used for server-side verification if needed
    });
  });

  // reCAPTCHA v3 on form submit
  const loginForm = document.getElementById('loginForm');
  loginForm.addEventListener('submit', function(e){
    e.preventDefault();
    grecaptcha.ready(function() {
      grecaptcha.execute('6Ld7dBYsAAAAABaiWl3AlIuM6jpKdNvJSZLobRk-', {action: 'login'}).then(function(token) {
        document.getElementById('recaptcha_token').value = token;
        loginForm.submit();
      });
    });
  });
</script>

</body>
</html>
