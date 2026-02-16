{{-- resources/views/treasurer/verifications/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Verify Loan - ' . $loan->application_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Verify Loan Application</h1>
        <div>
            <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Pending
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Loan Application #{{ $loan->application_number }}</strong> -
                Please verify all guarantors, collateral, and documents before proceeding.
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Member Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        @if($loan->user->membership_number)
                                            <br><small class="text-muted">#{{ $loan->user->membership_number }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $loan->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $loan->user->phone_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Address:</th>
                                    <td>{{ $loan->user->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>National ID:</th>
                                    <td>{{ $loan->user->national_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Member Since:</th>
                                    <td>{{ $loan->user->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Loan Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Amount Applied:</th>
                                    <td><strong>UGX {{ number_format($loan->amount_applied, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Purpose:</th>
                                    <td>{{ $loan->purpose ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Application Date:</th>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Has Collateral:</th>
                                    <td>
                                        @if($loan->has_collateral)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($loan->has_collateral)
                                <tr>
                                    <th>Collateral Type:</th>
                                    <td>{{ ucfirst($loan->collateral_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Collateral Value:</th>
                                    <td>UGX {{ number_format($loan->collateral_value, 2) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($loan->has_collateral && $loan->collateral_description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Collateral Description:</strong>
                            <p class="mb-0">{{ $loan->collateral_description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guarantors Verification -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guarantors Verification</h6>
                </div>
                <div class="card-body">
                    @if($loan->guarantors->count() > 0)
                        @foreach($loan->guarantors as $guarantor)
                        <div class="border rounded p-3 mb-3" id="guarantor-{{ $guarantor->id }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="font-weight-bold">{{ $guarantor->name }}</h6>
                                    <p class="mb-1"><i class="fas fa-phone mr-2"></i> {{ $guarantor->phone }}</p>
                                    <p class="mb-1"><i class="fas fa-id-card mr-2"></i> ID: {{ $guarantor->id_number }}</p>
                                    @if($guarantor->email)
                                        <p class="mb-1"><i class="fas fa-envelope mr-2"></i> {{ $guarantor->email }}</p>
                                    @endif
                                    @if($guarantor->relationship)
                                        <p class="mb-0"><i class="fas fa-users mr-2"></i> Relationship: {{ $guarantor->relationship }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $guarantor->status == 'approved' ? 'success' : ($guarantor->status == 'rejected' ? 'danger' : 'warning') }} p-2"
                                              id="guarantor-status-{{ $guarantor->id }}">
                                            {{ ucfirst($guarantor->status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success"
                                                onclick="verifyGuarantor({{ $guarantor->id }}, 'approved')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="verifyGuarantor({{ $guarantor->id }}, 'rejected')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if($guarantor->address)
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $guarantor->address }}
                            </small>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">No guarantors for this loan</p>
                    @endif
                </div>
            </div>

            <!-- Documents Verification -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Documents Verification</h6>
                </div>
                <div class="card-body">
                    @if($loan->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Category</th>
                                        <th>Size</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loan->documents as $document)
                                    <tr id="document-{{ $document->id }}">
                                        <td>
                                            <i class="fas {{ $document->file_icon }} mr-2"></i>
                                            <a href="{{ route('admin.loans.download-document', $document) }}" target="_blank">
                                                {{ Str::limit($document->document_name, 40) }}
                                            </a>
                                            @if($document->description)
                                                <br><small class="text-muted">{{ $document->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($document->category)) }}</span>
                                        </td>
                                        <td>{{ $document->formatted_size }}</td>
                                        <td>
                                            <span class="badge badge-{{ $document->verification_status == 'verified' ? 'success' : ($document->verification_status == 'rejected' ? 'danger' : 'warning') }}"
                                                  id="document-status-{{ $document->id }}">
                                                {{ ucfirst($document->verification_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-success"
                                                        onclick="verifyDocument({{ $document->id }}, 'verified')">
                                                    <i class="fas fa-check"></i> Verify
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="verifyDocument({{ $document->id }}, 'rejected')">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No documents uploaded for this loan</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Verification Progress -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verification Progress</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Guarantors</span>
                            <span>
                                {{ $loan->guarantors->where('status', 'approved')->count() }}/{{ $loan->guarantors->count() }}
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $loan->guarantors->count() > 0 ? ($loan->guarantors->where('status', 'approved')->count() / $loan->guarantors->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Documents</span>
                            <span>
                                {{ $loan->documents->where('verification_status', 'verified')->count() }}/{{ $loan->documents->count() }}
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: {{ $loan->documents->count() > 0 ? ($loan->documents->where('verification_status', 'verified')->count() / $loan->documents->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Final Verification Form -->
                    <form action="{{ route('treasurer.verifications.verify', $loan) }}" method="POST" id="verifyForm">
                        @csrf
                        <div class="form-group">
                            <label for="verification_notes">Verification Notes</label>
                            <textarea class="form-control" id="verification_notes" name="verification_notes"
                                      rows="3" required placeholder="Enter verification notes..."></textarea>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="guarantors_verified" name="guarantors_verified" value="1" required>
                            <label class="form-check-label" for="guarantors_verified">
                                I confirm that all guarantors have been verified
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="collateral_verified" name="collateral_verified" value="1"
                                   {{ $loan->has_collateral ? '' : 'disabled' }} required>
                            <label class="form-check-label" for="collateral_verified">
                                I confirm that collateral has been verified
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="documents_verified" name="documents_verified" value="1" required>
                            <label class="form-check-label" for="documents_verified">
                                I confirm that all documents have been verified
                            </label>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check-circle"></i> Verify Loan
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('treasurer.verifications.reject', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Loan Application</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Rejecting this loan will return it to admin for review.
                    </div>
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                  rows="4" required placeholder="Please explain why this loan is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Loan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function verifyGuarantor(guarantorId, status) {
        if (!confirm('Are you sure you want to ' + status + ' this guarantor?')) {
            return;
        }

        fetch('/treasurer/verifications/guarantor/' + guarantorId + '/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                verification_notes: prompt('Enter verification notes (optional):') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusBadge = document.getElementById('guarantor-status-' + guarantorId);
                statusBadge.className = 'badge badge-' + (status === 'approved' ? 'success' : 'danger') + ' p-2';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                // Update progress bar
                updateProgress();

                alert(data.message);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to verify guarantor. Please try again.');
        });
    }

    function verifyDocument(documentId, status) {
        if (!confirm('Are you sure you want to ' + status + ' this document?')) {
            return;
        }

        fetch('/treasurer/verifications/document/' + documentId + '/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                verification_status: status,
                verification_notes: prompt('Enter verification notes (optional):') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusBadge = document.getElementById('document-status-' + documentId);
                statusBadge.className = 'badge badge-' + (status === 'verified' ? 'success' : 'danger');
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                // Update progress
                updateProgress();

                alert(data.message);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to verify document. Please try again.');
        });
    }

    function updateProgress() {
        // Reload the page to update progress bars (simplest solution)
        location.reload();
    }

    // Enable/disable checkboxes based on verification status
    document.addEventListener('DOMContentLoaded', function() {
        const guarantorsCheck = document.getElementById('guarantors_verified');
        const documentsCheck = document.getElementById('documents_verified');
        const collateralCheck = document.getElementById('collateral_verified');

        // You can add logic here to auto-check if everything is verified
        @if($loan->guarantors->where('status', 'approved')->count() == $loan->guarantors->count() && $loan->guarantors->count() > 0)
            // Optionally auto-check
        @endif
    });
</script>
@endpush
