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
            background: #e5e7eb; /* gray-200 background */
            font-family: 'Poppins', sans-serif;
        }

        /* Logo drives in like a vehicle */
        @keyframes driveIn {
            0% {
                opacity: 0;
                transform: translateX(-150px) rotate(-10deg) scale(0.8);
            }
            60% {
                opacity: 1;
                transform: translateX(10px) rotate(5deg) scale(1.05);
            }
            100% {
                transform: translateX(0) rotate(0deg) scale(1);
            }
        }

        /* Smoke puff effect */
        @keyframes smoke {
            0% {
                opacity: 0.6;
                transform: scale(0.3) translateY(0);
            }
            50% {
                opacity: 0.4;
                transform: scale(0.6) translateY(-10px);
            }
            100% {
                opacity: 0;
                transform: scale(1) translateY(-20px);
            }
        }

        /* Floating effect */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .logo-wrapper {
            position: relative;
            display: inline-block;
        }

        .logo-animate {
            animation: driveIn 1.2s ease-out forwards, float 3s ease-in-out infinite 1.5s;
            position: relative;
            z-index: 10;
        }

        /* Smoke circles */
        .smoke {
            position: absolute;
            bottom: 5px;
            left: -15px;
            width: 15px;
            height: 15px;
            background: rgba(107, 114, 128, 0.7); /* gray-500 smoke */
            border-radius: 50%;
            animation: smoke 2s linear infinite;
            z-index: 1;
        }
        .smoke:nth-child(1) { animation-delay: 0s; }
        .smoke:nth-child(2) { animation-delay: 0.5s; left: -25px; width: 12px; height: 12px; }
        .smoke:nth-child(3) { animation-delay: 1s; left: -20px; width: 18px; height: 18px; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

    <!-- Registration Container -->
    <div class="w-full max-w-lg bg-gray-100 border border-gray-300 rounded-xl p-8 shadow-xl space-y-6 text-gray-900">
        
        <!-- Logo with Ride + Smoke Animation -->
        <div class="flex justify-center">
            <div class="logo-wrapper">
                <img src="{{ asset('images/log.png') }}" alt="Logo" 
                     class="w-20 h-20 object-contain logo-animate">
                <!-- Smoke puffs -->
                <div class="smoke"></div>
                <div class="smoke"></div>
                <div class="smoke"></div>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-gray-200 border border-gray-400 p-4 rounded-lg shadow-sm text-sm">
            <h3 class="text-xl font-semibold text-gray-800 mb-2"> Registration Requirements</h3>
            <ul class="list-disc pl-5 space-y-1 text-gray-700">
                <li>Provide your <strong>Full Name, Email, Phone Number</strong>.</li>
                <li>Enter your <strong>Age, Sex, and City</strong>.</li>
                <li>Set your <strong>Password</strong>.</li>
                <li>Upload your <strong>Profile Photo</strong>.</li>
                <li>Upload required documents: <strong>Business Permit, Barangay Clearance, Police Clearance</strong>.</li>
            </ul>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Role Selection (only driver available) -->
            <label class="block text-sm font-medium">Role</label>
            <select name="role" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
                <option value="driver" selected>Driver</option>
            </select>

            <!-- Full Name -->
            <input type="text" name="fullname" placeholder="Full Name" 
                   class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>

            <!-- Email -->
            <input type="email" name="email" placeholder="Email" 
                   class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>

            <!-- Phone (max 11 characters, numeric only) -->
            <input type="text" name="phone" placeholder="Phone Number (11 digits)" 
                   maxlength="11" inputmode="numeric" pattern="[0-9]*"
                   class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>

            <!-- Age -->
            <input type="number" name="age" placeholder="Age" 
                   class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>

            <!-- Sex -->
            <select name="sex" class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>
                <option value="">Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <!-- City -->
            <input type="text" name="city" placeholder="City" 
                   class="w-full p-2 border border-gray-400 rounded-lg bg-gray-50" required>

        
            <!-- File Uploads -->
            <label class="block text-sm font-medium">Profile Photo</label>
            <input type="file" name="photo" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>

            <label class="block text-sm font-medium">Business Permit</label>
            <input type="file" name="business_permit" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>

            <label class="block text-sm font-medium">Barangay Clearance</label>
            <input type="file" name="barangay_clearance" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>

            <label class="block text-sm font-medium">Police Clearance</label>
            <input type="file" name="police_clearance" class="w-full border border-gray-400 rounded-lg bg-gray-50" required>

            <!-- Submit -->
            <button type="submit"
                    class="w-full py-2 bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800 transition duration-200 rounded-lg text-white font-semibold shadow-md">
                Submit
            </button>
        </form>
    </div>  

    <!-- SweetAlert Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#16a34a'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc2626'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Validation Errors',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#f59e0b'
            });
        </script>
    @endif

</body>
</html>
