@extends('layouts.passenger')

@section('content')

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container my-5">
    <h3>Report Driver: {{ $driver->fullname ?? 'Unknown Driver' }}</h3>

    <form id="reportForm" action="{{ route('passenger.report.submit') }}" method="POST">
        @csrf
        {{-- Pass driver ID safely via hidden input --}}
        <input type="hidden" name="driver_id" value="{{ $driver->id ?? '' }}">
        
        <div class="mb-3">
            <label for="description" class="form-label">Report Description:</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-danger">Submit Report</button>
    </form>
</div>

<script>
    // Show Laravel validation errors using SweetAlert
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#dc3545'
        });
    @endif

    // Show success alert
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Report Submitted',
            text: '{{ session('success') }}',
            confirmButtonColor: '#198754'
        });
    @endif

    // Confirmation before form submission
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent actual submission

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to submit a report.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Proceed if confirmed
            }
        });
    });
</script>

@endsection
