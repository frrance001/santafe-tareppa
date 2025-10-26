<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP - Santafe Tareppa</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Google Font --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #eaebf3, #8f94fb);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .otp-card {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
      padding: 40px 30px;
      width: 90%;
      max-width: 400px;
      color: #fff;
      text-align: center;
      animation: fadeInUp 0.8s ease forwards;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .otp-card h4 {
      font-weight: 700;
      margin-bottom: 10px;
      color: #fff;
    }

    .otp-card p {
      font-size: 0.95rem;
      color: #e0e0e0;
      margin-bottom: 25px;
    }

    .otp-inputs {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 20px;
    }

    .otp-inputs input {
      width: 48px;
      height: 55px;
      border-radius: 10px;
      text-align: center;
      font-size: 1.5rem;
      border: none;
      outline: none;
      color: #000; /* changed to black */
      background-color: rgba(255, 255, 255, 0.9);
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .otp-inputs input:focus {
      background-color: #fff;
      transform: scale(1.1);
      box-shadow: 0 0 10px rgba(68, 250, 250, 0.958);
    }

    .btn-verify {
      background: linear-gradient(135deg, #43cea2, #15d2d299);
      border: none;
      padding: 12px;
      border-radius: 10px;
      color: #fff;
      font-weight: 600;
      font-size: 1.1rem;
      width: 100%;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-verify:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(24, 90, 157, 0.4);
    }

    .back-link {
      display: block;
      margin-top: 15px;
      color: #fff;
      text-decoration: none;
      opacity: 0.9;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
      opacity: 1;
    }

    @media (max-width: 576px) {
      .otp-inputs input {
        width: 42px;
        height: 50px;
        font-size: 1.3rem;
      }
    }
  </style>
</head>

<body>
  <div class="otp-card">
    <h4>Verify OTP</h4>
    <p>Enter the 6-digit code sent to your email.</p>

    <form action="{{ route('otp.verify.post') }}" method="POST" id="otpForm">
      @csrf
      <div class="otp-inputs">
        <input type="text" maxlength="1" inputmode="numeric" required>
        <input type="text" maxlength="1" inputmode="numeric" required>
        <input type="text" maxlength="1" inputmode="numeric" required>
        <input type="text" maxlength="1" inputmode="numeric" required>
        <input type="text" maxlength="1" inputmode="numeric" required>
        <input type="text" maxlength="1" inputmode="numeric" required>
      </div>

      <input type="hidden" name="otp" id="otpValue">

      <button type="submit" class="btn-verify">Verify</button>

      <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
    </form>
  </div>

  <script>
    // Combine OTP digits into one input before submitting
    const inputs = document.querySelectorAll('.otp-inputs input');
    const otpValue = document.getElementById('otpValue');

    inputs.forEach((input, index) => {
      input.addEventListener('input', () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }
        otpValue.value = Array.from(inputs).map(i => i.value).join('');
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === "Backspace" && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    // SweetAlert for session messages
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33'
      });
    @endif

    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#28a745'
      });
    @endif
  </script>
</body>
</html>
