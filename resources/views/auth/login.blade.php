<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - DriverZone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons + Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #ffffff;
      overflow: hidden;
    }
<<<<<<< HEAD

    /* ✅ Title */
=======
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
    .sakay-title {
      position: absolute;
      top: 40px;
      font-size: 3rem;
      font-weight: 800;
      background: linear-gradient(45deg, #ff9800, #ff5722, #2196f3);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: slideDown 1s ease-out;
    }
<<<<<<< HEAD

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ✅ Login Card */
=======
    @keyframes slideDown { from {opacity:0;transform:translateY(-50px);} to {opacity:1;transform:translateY(0);} }
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
    .login-card {
      width: 100%;
      max-width: 420px;
      padding: 40px 35px;
      border-radius: 20px;
      background: #ffffff;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
      color: #333;
      animation: fadeUp 1.2s ease;
    }
    @keyframes fadeUp { from {opacity:0;transform:translateY(40px);} to {opacity:1;transform:translateY(0);} }
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
<<<<<<< HEAD
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

    /* ✅ Ride Logo Animation */
    .logo-container { position: relative; display: inline-block; }
    .ride-logo { width: 50px; height: 50px; object-fit: contain; animation: rideForward 2s ease-in-out infinite; }

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

=======
    .btn-login:hover { transform: scale(1.05); box-shadow: 0 6px 18px rgba(0,0,0,0.2); }
    .exit-button { position: absolute; top: 15px; right: 15px; font-size: 14px; }
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
    .toggle-password { position: absolute; right: 16px; top: 70%; transform: translateY(-50%); color: #6b7280; cursor: pointer; }

    /* ✅ Loading Screen */
    #loading-screen {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }

    #loading-screen img {
      width: 100px;
      height: 100px;
      animation: pulse 1.5s infinite ease-in-out;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.7; }
    }

    #loading-text {
      margin-top: 15px;
      color: #1e3a8a;
      font-weight: 700;
      letter-spacing: 1px;
    }
  </style>
</head>
<body>

<<<<<<< HEAD
  <!-- ✅ Loading Screen -->
  <div id="loading-screen" style="display: none;">
    <img src="{{ asset('images/log.png') }}" alt="Loading...">
    <div id="loading-text">Loading Dashboard...</div>
  </div>

  <!-- Title -->
=======
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
  <h1 class="sakay-title">Santafe Tareppa</h1>

  <div class="login-card">
    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm exit-button">
      <i class="bi bi-box-arrow-left me-1"></i> Exit
    </a>

    <h2 class="text-center fw-bold text-primary mb-4">Login</h2>

<<<<<<< HEAD
    <form method="POST" action="{{ route('login') }}" id="loginForm">
=======
    <form id="loginForm" method="POST" action="{{ route('login') }}">
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
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
        <input name="email" type="email" id="email" class="form-control" placeholder="Enter your Gmail" required>
      </div>

      <div class="mb-4 position-relative" id="passwordField">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" id="password" class="form-control" placeholder="Enter your password">
        <i class="bi bi-eye toggle-password" id="togglePassword"></i>
      </div>

      <button type="button" id="loginBtn" class="btn btn-login w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Proceed
      </button>
    </form>
  </div>

<<<<<<< HEAD
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
  if (roleSelect.value !== 'admin') {
    passwordField.style.display = 'none';
  }
  roleSelect.addEventListener('change', () => {
    passwordField.style.display = roleSelect.value === 'admin' ? 'block' : 'none';
  });

  // ✅ Disable inspect shortcuts
  document.addEventListener('contextmenu', e => e.preventDefault());
  document.addEventListener('keydown', e => {
    if (e.keyCode === 123 || 
        (e.ctrlKey && e.shiftKey && ['I', 'J'].includes(e.key.toUpperCase())) ||
        (e.ctrlKey && e.key.toLowerCase() === 'u')) e.preventDefault();
  });

  // ✅ Loading screen on successful login
  const loginForm = document.getElementById('loginForm');
  const loadingScreen = document.getElementById('loading-screen');

  loginForm.addEventListener('submit', function(e) {
    // Show loading only after form validation
    loadingScreen.style.display = 'flex';
  });

  // ✅ SweetAlert2 for messages
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

=======
  <script>
    const roleSelect = document.getElementById('role');
    const passwordField = document.getElementById('passwordField');
    const emailInput = document.getElementById('email');
    const loginBtn = document.getElementById('loginBtn');

    roleSelect.addEventListener('change', () => {
      const role = roleSelect.value;
      passwordField.style.display = (role === 'driver' || role === 'passenger') ? 'none' : 'block';
    });

    loginBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      const role = roleSelect.value;
      const email = emailInput.value.trim();

      if (!email.endsWith('@gmail.com')) {
        Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Only Gmail addresses are allowed.' });
        return;
      }

      if (role === 'driver' || role === 'passenger') {
        try {
          const response = await fetch("{{ route('send.otp') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email, role })
          });

          const data = await response.json();
          if (!data.success) {
            Swal.fire('Error', data.message || 'Failed to send OTP.', 'error');
            return;
          }

          const { value: otp } = await Swal.fire({
            title: 'Enter OTP',
            text: 'Check your Gmail for the OTP code.',
            input: 'text',
            inputPlaceholder: 'Enter your OTP',
            confirmButtonText: 'Verify',
            showCancelButton: true,
            inputValidator: (value) => {
              if (!value) return 'Please enter your OTP';
            }
          });

          if (otp) {
            const verifyResponse = await fetch("{{ route('verify.otp') }}", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
              },
              body: JSON.stringify({ otp })
            });
            const verifyData = await verifyResponse.json();

            if (verifyData.redirect) {
              Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Redirecting to your dashboard...',
                showConfirmButton: false,
                timer: 2000,
                didOpen: () => Swal.showLoading()
              });
              setTimeout(() => window.location.href = verifyData.redirect, 2000);
            } else {
              Swal.fire('Error', verifyData.message || 'Invalid OTP.', 'error');
            }
          }
        } catch (err) {
          Swal.fire('Error', 'Server error. Please try again.', 'error');
        }
      } else {
        document.getElementById('loginForm').submit();
      }
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      togglePassword.classList.toggle('bi-eye');
      togglePassword.classList.toggle('bi-eye-slash');
    });
  </script>
>>>>>>> 0bf178cf647042af6d1f2d4518d2190091b1b3fa
</body>
</html>
