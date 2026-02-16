{{-- resources/views/notifications/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Send Notification')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Send Notification</h1>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Notifications
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Validation Errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Compose New Notification</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('notifications.send') }}" id="notificationForm">
                @csrf

                <!-- Recipient Selection -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold">Send To <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="recipient_all" name="recipient_type" value="all"
                                       class="custom-control-input" {{ old('recipient_type') == 'all' ? 'checked' : '' }}
                                       onclick="toggleRecipientFields()">
                                <label class="custom-control-label" for="recipient_all">All Members</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="recipient_role" name="recipient_type" value="role"
                                       class="custom-control-input" {{ old('recipient_type') == 'role' ? 'checked' : '' }}
                                       onclick="toggleRecipientFields()">
                                <label class="custom-control-label" for="recipient_role">Specific Role</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="recipient_specific" name="recipient_type" value="specific"
                                       class="custom-control-input" {{ old('recipient_type') == 'specific' ? 'checked' : '' }}
                                       onclick="toggleRecipientFields()">
                                <label class="custom-control-label" for="recipient_specific">Specific Members</label>
                            </div>
                        </div>
                    </div>
                    @error('recipient_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selection (hidden by default) -->
                <div class="form-group" id="role-field" style="display: none;">
                    <label for="role">Select Role</label>
                    <select class="form-control" id="role" name="role">
                        <option value="">Choose Role</option>
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Members</option>
                        <option value="treasurer" {{ old('role') == 'treasurer' ? 'selected' : '' }}>Treasurers</option>
                    </select>
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Specific Members Selection (hidden by default) -->
                <div class="form-group" id="members-field" style="display: none;">
                    <label for="recipients">Select Members</label>
                    <select class="form-control select2" id="recipients" name="recipients[]" multiple style="width: 100%;">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ in_array($member->id, old('recipients', [])) ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->membership_number ?? 'No #' }}) - {{ $member->email }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">You can select multiple members</small>
                    @error('recipients')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notification Type -->
                <div class="form-group">
                    <label for="type">Notification Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="admin_message" {{ old('type') == 'admin_message' ? 'selected' : '' }}>Admin Message</option>
                        <option value="loan_update" {{ old('type') == 'loan_update' ? 'selected' : '' }}>Loan Update</option>
                        <option value="savings_update" {{ old('type') == 'savings_update' ? 'selected' : '' }}>Savings Update</option>
                        <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Meeting Announcement</option>
                        <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System Notification</option>
                    </select>
                    @error('type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="{{ old('title') }}" placeholder="e.g., Important Update" required>
                    @error('title')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label for="message">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="message" name="message" rows="5"
                              placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Preview Section -->
                <div class="card bg-light mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Preview</h6>
                    </div>
                    <div class="card-body">
                        <div class="media">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-envelope" id="preview-icon"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-0 font-weight-bold" id="preview-title">Your Notification Title</h6>
                                <p id="preview-message">Your notification message will appear here...</p>
                                <small class="text-muted">From: {{ auth()->user()->name }} • Just now</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <hr>
                <div class="text-right">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Notifications Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Notifications Sent</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Recipients</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentSent = \App\Models\MemberNotification::with('user')
                                ->where('sender_id', auth()->id())
                                ->latest()
                                ->limit(5)
                                ->get()
                                ->groupBy(function($item) {
                                    return $item->title . '|' . $item->message . '|' . $item->type;
                                });
                        @endphp
                        @forelse($recentSent as $key => $group)
                            @php
                                $first = $group->first();
                                $recipientCount = $group->count();
                                $sampleRecipient = $group->first()->user->name ?? 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $first->created_at->format('d M Y H:i') }}</td>
                                <td>{{ Str::limit($first->title, 30) }}</td>
                                <td>
                                    <span class="badge badge-{{ $first->color }}">{{ str_replace('_', ' ', ucfirst($first->type)) }}</span>
                                </td>
                                <td>{{ $recipientCount }} recipients (e.g., {{ $sampleRecipient }})</td>
                                <td>
                                    <span class="badge badge-success">Sent</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">No notifications sent yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        min-height: 38px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 3px;
        padding: 2px 8px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#recipients').select2({
            placeholder: 'Select members...',
            allowClear: true
        });

        // Initial toggle
        toggleRecipientFields();

        // Preview updates
        $('#title, #message, #type').on('input change', function() {
            updatePreview();
        });

        // Set initial preview
        updatePreview();
    });

    function toggleRecipientFields() {
        const recipientType = document.querySelector('input[name="recipient_type"]:checked')?.value;

        document.getElementById('role-field').style.display = recipientType === 'role' ? 'block' : 'none';
        document.getElementById('members-field').style.display = recipientType === 'specific' ? 'block' : 'none';

        // Update required attributes
        document.getElementById('role').required = recipientType === 'role';

        const recipients = document.getElementById('recipients');
        if (recipients) {
            recipients.required = recipientType === 'specific';
        }
    }

    function updatePreview() {
        const title = document.getElementById('title').value || 'Your Notification Title';
        const message = document.getElementById('message').value || 'Your notification message will appear here...';
        const type = document.getElementById('type').value;

        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-message').textContent = message;

        // Update icon based on type
        const iconMap = {
            'admin_message': 'fa-envelope',
            'loan_update': 'fa-hand-holding-usd',
            'savings_update': 'fa-piggy-bank',
            'meeting': 'fa-calendar-alt',
            'system': 'fa-info-circle'
        };

        const icon = iconMap[type] || 'fa-bell';
        document.getElementById('preview-icon').className = `fas ${icon}`;
    }

    function resetForm() {
        if (confirm('Reset all fields?')) {
            document.getElementById('notificationForm').reset();
            $('#recipients').val(null).trigger('change');
            toggleRecipientFields();
            updatePreview();
        }
    }
</script>
@endpush
