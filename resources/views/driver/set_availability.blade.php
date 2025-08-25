@extends('layouts.driver')

@section('content')

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md border">
    <h2 class="text-2xl font-bold mb-4">Set Your Availability</h2>

    <form id="availabilityForm" method="POST" action="{{ route('driver.setAvailability') }}">
        @csrf

        <div class="mb-4">
            <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Current Availability</label>
            <select name="availability" id="availability" class="form-select w-full border-gray-300 rounded-md shadow-sm">
                <option value="available" {{ $driver->availability == 'available' ? 'selected' : '' }}>Available</option>
                <option value="not_available" {{ $driver->availability == 'not_available' ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Availability</button>
    </form>
</div>

<!-- SweetAlert scripts -->
<script>
    document.getElementById('availabilityForm').addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Update Availability?',
            text: "Your status will be updated.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>

@endsection
