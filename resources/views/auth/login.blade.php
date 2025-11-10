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

    /* Title */
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
    @keyframes slideDown { from {opacity:0;transform:translateY(-50px);} to {opacity:1;transform:translateY(0);} }

    /* Login Card */
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
    .btn-login:hover { transform: scale(1.05); box-shadow: 0 6px 18px rgba(0,0,0,0.2); }

    .exit-button {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 14px;
    }

    .toggle-password { position: absolute; right: 16px; top: 70%; transform: translateY(-50%); color: #6b7280; cursor: pointer; }

    /* Loading Screen */
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
    #loading-screen img { width: 100px; height: 100px; animation: pulse 1.5s infinite ease-in-out; }
    @keyframes pulse { 0%,100%{transform:scale(1);opacity:1;}50%{transform:scale(1.2);opacity:0.7;} }
    #loading-text { margin-top: 15px; color: #1e3a8a; font-weight: 700; letter-spacing: 1px; }
  </style>
</head>
<body>

  <!-- Loading Screen -->
  <div id="loading-screen" style="display: none;">
    <img src="{{ asset('images/log.png') }}" alt="Loading...">
    <div id="loading-text">Loading Dashboard...</div>
  </div>

  <h1 class="sakay-title">Santafe Tareppa</h1>

  <div class="login-card">
    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm exit-button">
      <i class="bi bi-box-arrow-left me-1"></i> Exit
    </a>

    <h2 class="text-center fw-bold text-primary mb-4">Login</h2>

    <form id="loginForm" method="POST" action="{{ route('login') }}">
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

    // Hide password field for Passenger & Driver
    const roleSelect = document.getElementById('role');
    const passwordField = document.getElementById('passwordField');
    passwordField.style.display = roleSelect.value === 'admin' ? 'block' : 'none';
    roleSelect.addEventListener('change', () => {
      passwordField.style.display = roleSelect.value === 'admin' ? 'block' : 'none';
    });

    // Disable inspect shortcuts
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
      if (e.keyCode === 123 ||
          (e.ctrlKey && e.shiftKey && ['I','J'].includes(e.key.toUpperCase())) ||
          (e.ctrlKey && e.key.toLowerCase() === 'u')) e.preventDefault();
    });

    // SweetAlert2 messages
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

    // Login button click
    const loginBtn = document.getElementById('loginBtn');
    const emailInput = document.getElementById('email');
    const loginForm = document.getElementById('loginForm');
    const loadingScreen = document.getElementById('loading-screen');

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
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ email, role })
          });
          const data = await response.json();
          if (!data.success) { Swal.fire('Error', data.message || 'Failed to send OTP.', 'error'); return; }

          const { value: otp } = await Swal.fire({
            title: 'Enter OTP',
            text: 'Check your Gmail for the OTP code.',
            input: 'text',
            inputPlaceholder: 'Enter your OTP',
            confirmButtonText: 'Verify',
            showCancelButton: true,
            inputValidator: v => !v ? 'Please enter your OTP' : undefined
          });

          if (otp) {
            const verifyResponse = await fetch("{{ route('verify.otp') }}", {
              method: "POST",
              headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
              body: JSON.stringify({ otp })
            });
            const verifyData = await verifyResponse.json();
            if (verifyData.redirect) {
              Swal.fire({ icon:'success', title:'Login Successful!', text:'Redirecting...', showConfirmButton:false, timer:2000, didOpen:()=>Swal.showLoading() });
              setTimeout(() => window.location.href = verifyData.redirect, 2000);
            } else {
              Swal.fire('Error', verifyData.message || 'Invalid OTP.', 'error');
            }
          }
        } catch (err) {
          Swal.fire('Error', 'Server error. Please try again.', 'error');
        }
      } else {
        loadingScreen.style.display = 'flex';
        loginForm.submit();
      }
    });
  </script>

</body>
</html>
