document.addEventListener("DOMContentLoaded", () => {
    const userModalEl = document.getElementById('userInfoModal');
    const userModal = new bootstrap.Modal(userModalEl);

    document.querySelectorAll(".open-user-modal").forEach(el => {
        el.addEventListener("click", function () {
            const user = JSON.parse(this.closest("tr").dataset.user);

            // Fill basic info
            document.getElementById("modal-id").innerText = user.id;
            document.getElementById("modal-fullname").innerText = user.fullname;
            document.getElementById("modal-email").innerText = user.email;
            document.getElementById("modal-phone").innerText = user.phone;
            document.getElementById("modal-age").innerText = user.age || "-";
            document.getElementById("modal-sex").innerText = user.sex || "-";
            document.getElementById("modal-city").innerText = user.city || "-";
            document.getElementById("modal-role").innerText = user.role;

            // Fill documents/photos
            document.getElementById("profilePhoto").src = user.profile_photo ? `/storage/${user.profile_photo}` : '/images/no-image.png';
            document.getElementById("policeClearance").src = user.police_clearance ? `/storage/${user.police_clearance}` : '/images/no-image.png';
            document.getElementById("brgyClearance").src = user.brgy_clearance ? `/storage/${user.brgy_clearance}` : '/images/no-image.png';
            document.getElementById("businessPermit").src = user.business_permit ? `/storage/${user.business_permit}` : '/images/no-image.png';

            // Store user ID in buttons
            document.getElementById("approveForm").dataset.id = user.id;
            document.getElementById("disapproveForm").dataset.id = user.id;
            document.getElementById("deleteForm").dataset.id = user.id;

            // Show modal
            userModal.show();
        });
    });

    // Approve driver
    document.getElementById("approveForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const userId = this.dataset.id;
        updateDriverStatus(userId, 'approved');
    });

    // Disapprove driver
    document.getElementById("disapproveForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const userId = this.dataset.id;
        updateDriverStatus(userId, 'disapproved');
    });

    // Delete driver
    document.getElementById("deleteForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const userId = this.dataset.id;
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete the driver!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/delete-driver/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Deleted!', data.message, 'success');
                    userModal.hide();
                    // Remove row from table
                    const row = document.querySelector(`tr[data-user*='"id":${userId}']`);
                    if(row) row.remove();
                });
            }
        });
    });

    // Optional: Print
    document.getElementById("printUser").addEventListener("click", () => {
        const printContent = document.querySelector("#userInfoModal .modal-body").innerHTML;
        const w = window.open();
        w.document.write(printContent);
        w.document.close();
        w.print();
    });
});

// Approve / Disapprove AJAX function
function updateDriverStatus(userId, status) {
    fetch(`/admin/update-driver-status/${userId}`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire('Success!', data.message, 'success');
        const row = document.querySelector(`tr[data-user*='"id":${userId}']`);
        if(row) {
            const statusCell = row.querySelector('td:last-child');
            statusCell.innerHTML = `<span class="status-${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
        }
        userModal.hide();
    });
}
