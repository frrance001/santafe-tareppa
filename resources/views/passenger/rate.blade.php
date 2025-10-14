{{-- resources/views/passenger/rate.blade.php --}}
@extends('layouts.passenger')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #1e3c72, #2a5298); /* 🔵 Blue gradient */
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 30px;
        width: 100%;
        max-width: 600px;
        color: #fff;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    h2 {
        font-weight: 700;
        font-size: 1.8rem;
        color: #fff;
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: inline-block;
        color: #fefefe;
    }

    select, textarea {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 10px;
        padding: 10px;
        color: #fff;
        width: 100%;
        outline: none;
        transition: all 0.3s ease;
    }

    select:focus, textarea:focus {
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.4);
    }

    option {
        color: #333;
    }

    button {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #36d1dc, #5b86e5); /* button gradient blue */
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="glass-card">
    <h2>⭐ Rate Your Ride</h2>

    <form action="{{ route('passenger.rate.submit', ['ride' => $ride->id]) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="rating">Rating (1 to 5)</label>
            <select name="rating" id="rating" required>
                <option value="">Select rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label for="feedback">Feedback</label>
            <textarea name="feedback" id="feedback" rows="4" placeholder="Write your feedback here..."></textarea>
        </div>

        <button type="submit">✅ Submit Rating</button>
    </form>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#2a5298', // 🔵 match blue theme
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: "{{ session('error') }}",
        confirmButtonColor: '#e53e3e',
    });
</script>
@endif
@endsection
