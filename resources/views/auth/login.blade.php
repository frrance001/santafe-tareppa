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

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #ffffff;
      overflow: hidden;
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
      background: #ffffff;
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
      box-shadow: 0 0 10px rgba(33, 150, 243, 0.4);
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

    .exit-button {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 14px;
    }

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

    /* ✅ Ride Logo Container */
    .logo-container { position: relative; display: inline-block; }
    .ride-logo { width: 50px; height: 50px; object-fit: contain; animation: rideForward 2s ease-in-out infinite; }

    /* ✅ Smoke Effect */
    .smoke { position: absolute; bottom: 5px; left: -12px; width: 12px; height: 12px; background: rgba(180, 180, 180, 0.6); border-radius: 50%; filter: blur(2px); opacity: 0; }
    .smoke1 { animation: smokeTrail 2s infinite ease-out; }
    .smoke2 { animation: smokeTrail 2s infinite ease-out 0.5s; }
    .smoke3 { animation: smokeTrail 2s infinite ease-out 1s; }

    @keyframes rideForward {
      0%, 100% { transform: translateX(0) translateY(0) rotate(0deg); }
      25% { transform: translateX(2px) translateY(-2px) rotate(-2deg); }
      50% { transform: translateX(4px) translateY(0) rotate(2deg); }
      75% { transform: translateX(2px) translateY(2px) rotate(-1deg); }
    }

    @keyframes smokeTrail {
      0% { opacity: 0; transform: scale(0.4) translateX(0) translateY(0); }
      30% { opacity: 0.8; transform: scale(0.7) translateX(-10px) translateY(-8px); }
      60% { opacity: 0.4; transform: scale(1) translateX(-20px) translateY(-16px); }
      100% { opacity: 0; transform: scale(1.3) translateX(-30px) translateY(-24px); }
    }

    .toggle-password { position: absolute; right: 16px; top: 70%; transform: translateY(-50%); color: #6b7280; cursor: pointer; }
  </style>
</head>
<body>

  <!-- Title -->
  <h1 class="sakay-title">Santafe Tareppa</h1>

  <!-- Login Card -->
  <div class="login-card">
    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm exit-button">
      <i class="bi bi-box-arrow-left me-1"></i> Exit
    </a>

    <h2>
      <div class="logo-container">
        <img src="{{ asset('images/log.png') }}" alt="Logo" class="ride-logo">
        <span class="smoke smoke1"></span>
        <span class="smoke smoke2"></span>
        <span class="smoke smoke3"></span>
      </div>
      Login
    </h2>

    <!-- Error messages handled via SweetAlert2 -->

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
  <input 
    name="email" 
    type="email" 
    id="email" 
    class="form-control" 
    placeholder="Enter your Gmail"
    required 
    autofocus
    pattern="[a-zA-Z._%+-]+@gmail\.com"
    title="Please enter a valid Gmail address (e.g. example@gmail.com)"
  >
</div>


<!-- Hidden password for passenger & driver -->
<div class="mb-4 position-relative" id="passwordField">
  <label for="password" class="form-label">Password</label>
  <input name="password" type="password" id="password" class="form-control" placeholder="Enter your password">
  <i class="bi bi-eye toggle-password" id="togglePassword"></i>
</div>


      <button type="submit" id="loginBtn" class="btn btn-login w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </button>
    </form>
  </div>
<script>
  // ✅ Toggle password visibility
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  togglePassword.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePassword.classList.toggle('bi-eye');
    togglePassword.classList.toggle('bi-eye-slash');
  });

  // ✅ Hide password field for Passenger & Driver
  const roleSelect = document.getElementById('role');
  const passwordField = document.getElementById('passwordField');

  // Hide by default if role != admin
  if (roleSelect.value !== 'admin') {
    passwordField.style.display = 'none';
  }

  roleSelect.addEventListener('change', () => {
    if (roleSelect.value === 'admin') {
      passwordField.style.display = 'block';
    } else {
      passwordField.style.display = 'none';
    }
  });

  // ✅ Block right-click & Inspect shortcuts
  document.addEventListener('contextmenu', e => e.preventDefault());
  document.addEventListener('keydown', function(e) {
    if (e.keyCode === 123) e.preventDefault(); // F12
    if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) e.preventDefault(); // Ctrl+Shift+I/J
    if (e.ctrlKey && e.key.toLowerCase() === 'u') e.preventDefault(); // Ctrl+U
  });

  // ✅ Limit login attempts (max 3)
  let loginAttempts = 0;
  const maxAttempts = 3;
  const loginForm = document.getElementById('loginForm');
  const loginBtn = document.getElementById('loginBtn');

  loginForm.addEventListener('submit', function(e) {
    loginAttempts++;
    if (loginAttempts > maxAttempts) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Too Many Attempts',
        text: 'You have exceeded 3 login attempts. Please try again later.',
      });
      loginBtn.disabled = true;
      loginBtn.innerText = "Locked";
      return false;
    }
  });

  // ✅ SweetAlert2 for server-side feedback
  @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}' });
  @endif

  @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}' });
  @endif

  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: `<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`
    });
  @endif
</script>

</body>
</html>
