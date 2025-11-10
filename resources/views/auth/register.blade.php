<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: #f3f4f6; /* gray-100 background */
      font-family: 'Poppins', sans-serif;
    }

    /* Logo drive-in animation */
    @keyframes driveIn {
      0% { opacity: 0; transform: translateX(-150px) rotate(-10deg) scale(0.8); }
      60% { opacity: 1; transform: translateX(10px) rotate(5deg) scale(1.05); }
      100% { transform: translateX(0) rotate(0deg) scale(1); }
    }

    @keyframes smoke {
      0% { opacity: 0.6; transform: scale(0.3) translateY(0); }
      50% { opacity: 0.4; transform: scale(0.6) translateY(-10px); }
      100% { opacity: 0; transform: scale(1) translateY(-20px); }
    }

    @keyframes float {
      0%,100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    .logo-wrapper { position: relative; display: inline-block; }
    .logo-animate { animation: driveIn 1.2s ease-out forwards, float 3s ease-in-out infinite 1.5s; position: relative; z-index: 10; }

    .smoke {
      position: absolute; bottom: 5px; left: -15px;
      width: 15px; height: 15px;
      background: rgba(107,114,128,0.7);
      border-radius: 50%;
      animation: smoke 2s linear infinite;
      z-index: 1;
    }
    .smoke:nth-child(1){ animation-delay:0s; }
    .smoke:nth-child(2){ animation-delay:0.5s; left:-25px;width:12px;height:12px; }
    .smoke:nth-child(3){ animation-delay:1s; left:-20px;width:18px;height:18px; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">

  <div class="w-full max-w-2xl bg-white border border-gray-300 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6 text-gray-900">

    <!-- Logo -->
    <div class="flex justify-center">
      <div class="logo-wrapper">
        <img src="{{ asset('images/log.png') }}" alt="Logo" class="w-20 h-20 sm:w-24 sm:h-24 object-contain logo-animate">
        <div class="smoke"></div>
        <div class="smoke"></div>
        <div class="smoke"></div>
      </div>
    </div>

    <!-- Registration Requirements -->
    <div class="bg-gray-100 border border-gray-300 p-4 rounded-lg shadow-sm text-sm sm:text-base">
      <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Registration Requirements</h3>
      <ul class="list-disc pl-5 space-y-1 text-gray-700">
        <li>Provide your <strong>Full Name, Email, Phone Number</strong>.</li>
        <li>Enter your <strong>Age, Sex, and City</strong>.</li>
        <li>Upload your <strong>Profile Photo</strong>.</li>
        <li>Upload required documents: <strong>Business Permit, Barangay Clearance, Police Clearance</strong>.</li>
      </ul>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @csrf

      <!-- Role -->
      <div class="sm:col-span-2">
        <label class="block text-sm font-medium mb-1">Role</label>
        <select name="role" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
          <option value="driver" selected>Driver</option>
        </select>
      </div>

      <!-- Full Name -->
      <div>
        <label class="block text-sm font-medium mb-1">Full Name</label>
        <input type="text" name="fullname" placeholder="Full Name" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- Phone -->
      <div>
        <label class="block text-sm font-medium mb-1">Phone</label>
        <input type="text" name="phone" placeholder="Phone Number (11 digits)" maxlength="11" inputmode="numeric" pattern="[0-9]*" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- Age -->
      <div>
        <label class="block text-sm font-medium mb-1">Age</label>
        <input type="number" name="age" placeholder="Age" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- Sex -->
      <div>
        <label class="block text-sm font-medium mb-1">Sex</label>
        <select name="sex" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
          <option value="">Select Sex</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <!-- City -->
      <div>
        <label class="block text-sm font-medium mb-1">City</label>
        <input type="text" name="city" placeholder="City" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- File Uploads -->
      <div class="sm:col-span-2">
        <label class="block text-sm font-medium mb-1">Profile Photo</label>
        <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium mb-1">Business Permit</label>
        <input type="file" name="business_permit" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium mb-1">Barangay Clearance</label>
        <input type="file" name="barangay_clearance" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium mb-1">Police Clearance</label>
        <input type="file" name="police_clearance" accept=".jpg,.jpeg,.png,.pdf" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>
      </div>

      <!-- Terms -->
      <div class="sm:col-span-2 flex items-start space-x-2 mt-2">
        <input id="terms" type="checkbox" class="mt-1" required>
        <label for="terms" class="text-sm text-gray-700">
          I agree to the 
          <a href="#" id="showTerms" class="text-blue-600 hover:underline font-medium">Terms and Conditions</a>.
        </label>
      </div>

      <!-- Submit -->
      <div class="sm:col-span-2">
        <button type="submit" class="w-full py-2 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 transition duration-200 rounded-lg text-white font-semibold shadow-md">
          Submit
        </button>
      </div>

    </form>
  </div>

  <!-- SweetAlert Scripts -->
  <script>
    // Terms popup
    document.getElementById('showTerms').addEventListener('click', function(e){
      e.preventDefault();
      Swal.fire({
        title: 'Terms and Conditions',
        html: `
          <div class="text-left text-gray-700 space-y-2 text-sm">
            <p>By registering as a driver, you agree to the following terms:</p>
            <ul class="list-disc pl-5 space-y-1">
              <li>All information provided is accurate and truthful.</li>
              <li>You must comply with all local transportation and safety laws.</li>
              <li>Your account may be reviewed and verified by the admin.</li>
              <li>You are responsible for maintaining the confidentiality of your account.</li>
              <li>Misuse of the platform may result in suspension or permanent removal.</li>
            </ul>
            <p class="mt-2">If you do not agree, please do not proceed with registration.</p>
          </div>
        `,
        confirmButtonText: 'I Understand',
        confirmButtonColor: '#2563eb'
      });
    });

    // Session notifications
    @if(session('success'))
      Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", confirmButtonColor: '#16a34a' });
    @endif

    @if(session('error'))
      Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}", confirmButtonColor: '#dc2626' });
    @endif

    // Validation errors
    @if ($errors->any())
      Swal.fire({
        icon: 'warning',
        title: 'Validation Errors',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#f59e0b'
      });
    @endif
  </script>
</body>
</html>
