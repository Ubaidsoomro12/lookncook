@extends('admin.layouts.master')
@section('title', 'View | Waiter')

@section('content')
<div class="container py-4">

    <!-- Header Row -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.waiter.index') }}"
               class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" 
               style="width: 40px; height: 40px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h2 fw-bold text-dark mb-0">Waiter Details</h1>
                <p class="text-muted small mt-1">View complete information of {{ $waiter->name }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.waiter.edit', $waiter->id) }}"
               class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill shadow-sm" 
               style="background: linear-gradient(135deg, #ff2d7a, #ff4b91); border: none;">
                <i class="fa-solid fa-pen"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.waiter.index') }}"
               class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                <i class="fa-solid fa-list me-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
        <div class="card-body p-4 p-md-5">

            <div class="row g-4">
                <!-- Profile Section -->
                <div class="col-12">
                    <div class="d-flex align-items-center gap-4">
                        <div class="position-relative">
                            @if($waiter->image)
                                <img src="{{ asset($waiter->image) }}" alt="{{ $waiter->name }}" 
                                     class="rounded-circle object-fit-cover border" 
                                     style="width: 120px; height: 120px; border: 4px solid #ff2d7a !important;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" 
                                     style="width: 120px; height: 120px; border: 4px solid #ff2d7a !important; color: #ccc;">
                                    <i class="fa-solid fa-user fs-1"></i>
                                </div>
                            @endif
                            <span class="position-absolute bottom-0 end-0 mb-1 me-1">
                                <span class="badge {{ $waiter->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                    {{ ucfirst($waiter->status) }}
                                </span>
                            </span>
                        </div>
                        <div>
                            <h2 class="fw-bold text-dark mb-1">{{ $waiter->name }}</h2>
                            <p class="text-muted mb-0"><i class="fa-solid fa-envelope me-2"></i>{{ $waiter->email }}</p>
                            <p class="text-muted mb-0"><i class="fa-solid fa-phone me-2"></i>{{ $waiter->phone }}</p>
                            <p class="text-muted mb-0"><i class="fa-solid fa-id-card me-2"></i>CNIC: {{ $waiter->cnic ?? 'Not Provided' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 ">
                    <hr class="border-2 opacity-25" style="margin-bottom: 8px;">
                </div>

                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Basic Information</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-dark" style="width: 40%;">Full Name</td>
                                    <td class="text-muted">{{ $waiter->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Email Address</td>
                                    <td class="text-muted">{{ $waiter->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Phone Number</td>
                                    <td class="text-muted">{{ $waiter->phone }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">CNIC</td>
                                    <td class="text-muted">{{ $waiter->cnic ?? 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Status</td>
                                    <td>
                                        @if($waiter->status === 'active')
                                            <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Employment Details -->
                <div class="col-md-6">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Employment Details</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-dark" style="width: 40%;">Hire Date</td>
                                    <td class="text-muted">{{ $waiter->hire_date ? $waiter->hire_date->format('d M, Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Salary</td>
                                    <td class="text-muted fw-semibold text-dark">Rs. {{ number_format($waiter->salary, 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Address</td>
                                    <td class="text-muted">{{ $waiter->address ?? 'Not Provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Joined</td>
                                    <td class="text-muted">{{ $waiter->created_at ? $waiter->created_at->format('d M, Y h:i A') : '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Last Updated</td>
                                    <td class="text-muted">{{ $waiter->updated_at ? $waiter->updated_at->format('d M, Y h:i A') : '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Images & Documents Section -->
                <div class="col-12 mt-3">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">Images & Documents</h5>
                    <div class="row g-4">

                        <!-- Profile Image -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="fw-semibold text-dark mb-2">
                                        <i class="fa-solid fa-user-circle text-primary me-2"></i>Profile Image
                                    </h6>
                                    @if($waiter->image)
                                        <img src="{{ asset($waiter->image) }}" alt="Profile" 
                                             class="rounded-3 object-fit-cover border mb-2" 
                                             style="width: 100%; height: 200px;">
                                        <a href="{{ asset($waiter->image) }}" target="_blank" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fa-solid fa-eye me-1"></i> View
                                        </a>
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2" 
                                             style="height: 200px; color: #ccc;">
                                            <i class="fa-solid fa-user fs-1"></i>
                                        </div>
                                        <span class="text-muted small">No image uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- CNIC Front Image -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="fw-semibold text-dark mb-2">
                                        <i class="fa-solid fa-id-card text-primary me-2"></i>CNIC Front
                                    </h6>
                                    @if($waiter->cnic_front_image)
                                        <img src="{{ asset($waiter->cnic_front_image) }}" alt="CNIC Front" 
                                             class="rounded-3 object-fit-cover border mb-2" 
                                             style="width: 100%; height: 200px;">
                                        <a href="{{ asset($waiter->cnic_front_image) }}" target="_blank" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fa-solid fa-eye me-1"></i> View
                                        </a>
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2" 
                                             style="height: 200px; color: #ccc;">
                                            <i class="fa-solid fa-id-card fs-1"></i>
                                        </div>
                                        <span class="text-muted small">No image uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- CNIC Back Image -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="fw-semibold text-dark mb-2">
                                        <i class="fa-solid fa-id-card text-primary me-2"></i>CNIC Back
                                    </h6>
                                    @if($waiter->cnic_back_image)
                                        <img src="{{ asset($waiter->cnic_back_image) }}" alt="CNIC Back" 
                                             class="rounded-3 object-fit-cover border mb-2" 
                                             style="width: 100%; height: 200px;">
                                        <a href="{{ asset($waiter->cnic_back_image) }}" target="_blank" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fa-solid fa-eye me-1"></i> View
                                        </a>
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2" 
                                             style="height: 200px; color: #ccc;">
                                            <i class="fa-solid fa-id-card fs-1"></i>
                                        </div>
                                        <span class="text-muted small">No image uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- CV / Resume -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="fw-semibold text-dark mb-2">
                                        <i class="fa-solid fa-file-pdf text-danger me-2"></i>CV / Resume
                                    </h6>
                                    @if($waiter->cv_image)
                                        <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center mb-2" 
                                             style="height: 200px;">
                                            <i class="fa-solid fa-file-pdf text-danger" style="font-size: 60px;"></i>
                                            <span class="small text-muted mt-2">{{ basename($waiter->cv_image) }}</span>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ asset($waiter->cv_image) }}" target="_blank" 
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="fa-solid fa-eye me-1"></i> View
                                            </a>
                                            <a href="{{ asset($waiter->cv_image) }}" download 
                                               class="btn btn-sm btn-danger rounded-pill px-3">
                                                <i class="fa-solid fa-download me-1"></i> Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2" 
                                             style="height: 200px; color: #ccc;">
                                            <i class="fa-solid fa-file-pdf fs-1"></i>
                                        </div>
                                        <span class="text-muted small">No file uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Letter -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body text-center p-3">
                                    <h6 class="fw-semibold text-dark mb-2">
                                        <i class="fa-solid fa-file-contract text-success me-2"></i>Appointment Letter
                                    </h6>
                                    @if($waiter->appointment_letter_image)
                                        <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center mb-2" 
                                             style="height: 200px;">
                                            <i class="fa-solid fa-file-contract text-success" style="font-size: 60px;"></i>
                                            <span class="small text-muted mt-2">{{ basename($waiter->appointment_letter_image) }}</span>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ asset($waiter->appointment_letter_image) }}" target="_blank" 
                                               class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                <i class="fa-solid fa-eye me-1"></i> View
                                            </a>
                                            <a href="{{ asset($waiter->appointment_letter_image) }}" download 
                                               class="btn btn-sm btn-success rounded-pill px-3">
                                                <i class="fa-solid fa-download me-1"></i> Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2" 
                                             style="height: 200px; color: #ccc;">
                                            <i class="fa-solid fa-file-contract fs-1"></i>
                                        </div>
                                        <span class="text-muted small">No file uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 mt-4">
                    <hr class="border-2 opacity-25">
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('admin.waiter.edit', $waiter->id) }}"
                           class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" 
                           style="background: linear-gradient(135deg, #ff2d7a, #ff4b91); border: none;">
                            <i class="fa-solid fa-pen me-2"></i> Edit Waiter
                        </a>
                        <a href="{{ route('admin.waiter.index') }}"
                           class="btn btn-outline-secondary px-5 py-2 rounded-pill">
                            <i class="fa-solid fa-list me-2"></i> Back to List
                        </a>
                        <button type="button"
                                class="btn btn-outline-danger px-5 py-2 rounded-pill delete-waiter-btn"
                                data-id="{{ $waiter->id }}"
                                data-name="{{ $waiter->name }}"
                                data-url="{{ route('admin.waiter.destroy', $waiter->id) }}">
                            <i class="fa-solid fa-trash me-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ======================= DELETE CONFIRMATION MODAL ======================= -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-body p-5 text-center">
                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-trash-can text-danger fs-1"></i>
                </div>
                <h3 class="fw-bold text-dark">Delete Waiter?</h3>
                <p class="text-muted mt-2">
                    Are you sure you want to delete
                    <span id="deleteModalWaiterName" class="fw-semibold text-dark">this waiter</span>?
                    This action cannot be undone.
                </p>
                <div class="d-flex gap-3 mt-4 justify-content-center">
                    <button type="button" id="deleteModalCancelBtn" class="btn btn-outline-secondary px-4 py-2 rounded-pill" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" id="deleteModalConfirmBtn"
                            class="btn btn-danger px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                        <span id="deleteModalConfirmText">Yes, Delete</span>
                        <i id="deleteModalSpinner" class="fa-solid fa-circle-notch fa-spin d-none"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================= TOAST NOTIFICATION CONTAINER ======================= -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div class="toast-container"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

    // ==================================================================
    // TOAST NOTIFICATION SYSTEM
    // ==================================================================
    const toastContainer = document.getElementById('toastContainer');

    function showToast(message, type = 'success', duration = 5000) {
        const isSuccess = type === 'success';
        const toast = document.createElement('div');
        toast.className = `toast align-items-center border-0 ${isSuccess ? 'bg-success' : 'bg-danger'} text-white show`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid ${isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.querySelector('.toast-container').appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // ==================================================================
    // DELETE CONFIRMATION MODAL
    // ==================================================================
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteModalNameEl = document.getElementById('deleteModalWaiterName');
    const deleteModalCancelBtn = document.getElementById('deleteModalCancelBtn');
    const deleteModalConfirmBtn = document.getElementById('deleteModalConfirmBtn');
    const deleteModalConfirmText = document.getElementById('deleteModalConfirmText');
    const deleteModalSpinner = document.getElementById('deleteModalSpinner');

    let pendingDeleteUrl = null;
    let pendingDeleteRow = null;

    function openDeleteModal(url, row, name) {
        pendingDeleteUrl = url;
        pendingDeleteRow = row;
        deleteModalNameEl.textContent = name ? `"${name}"` : 'this waiter';
        deleteModal.show();
    }

    function closeDeleteModal() {
        deleteModal.hide();
        pendingDeleteUrl = null;
        pendingDeleteRow = null;
    }

    document.querySelector('.delete-waiter-btn')?.addEventListener('click', function (e) {
        openDeleteModal(this.dataset.url, null, this.dataset.name);
    });

    deleteModalCancelBtn.addEventListener('click', closeDeleteModal);

    deleteModalConfirmBtn.addEventListener('click', function () {
        if (!pendingDeleteUrl) return;

        deleteModalConfirmBtn.disabled = true;
        deleteModalConfirmText.textContent = 'Deleting...';
        deleteModalSpinner.classList.remove('d-none');

        fetch(pendingDeleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Request failed with status ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Waiter deleted successfully.', 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('admin.waiter.index') }}";
                }, 1000);
            } else {
                showToast('Failed to delete waiter. Please try again.', 'error');
            }
        })
        .catch(() => {
            showToast('Something went wrong while deleting. Please try again.', 'error');
        })
        .finally(() => {
            deleteModalConfirmBtn.disabled = false;
            deleteModalConfirmText.textContent = 'Yes, Delete';
            deleteModalSpinner.classList.add('d-none');
            closeDeleteModal();
        });
    });
});
</script>
@endsection