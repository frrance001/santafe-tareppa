<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card shadow p-4" style="max-width:400px;">
    <h4 class="text-center mb-3">Enter OTP</h4>

    <form method="POST" action="{{ route('otp.verify.submit') }}">
      @csrf
      <div class="mb-3">
        <label for="otp" class="form-label">OTP Code</label>
        <input type="text" name="otp" id="otp" class="form-control" required placeholder="Enter your 6-digit OTP">
      </div>
      <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>

    @if(session('error'))
      <p class="text-danger text-center mt-2">{{ session('error') }}</p>
    @endif
  </div>
</body>
</html>
