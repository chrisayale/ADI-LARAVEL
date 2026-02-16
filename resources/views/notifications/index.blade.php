{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
        <div>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Send Notification
                </a>
            @endif
            <button type="button" class="btn btn-success" onclick="markAllRead()">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
            <button type="button" class="btn btn-danger" onclick="deleteAllRead()">
                <i class="fas fa-trash"></i> Clear Read
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('notifications.index') }}" class="row align-items-center">
                <div class="col-md-3">
                    <select class="form-control" name="type" onchange="this.form.submit()">
                        <option value="all">All Types</option>
                        <option value="admin_message" {{ request('type') == 'admin_message' ? 'selected' : '' }}>Admin Messages</option>
                        <option value="loan_update" {{ request('type') == 'loan_update' ? 'selected' : '' }}>Loan Updates</option>
                        <option value="savings_update" {{ request('type') == 'savings_update' ? 'selected' : '' }}>Savings Updates</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meetings</option>
                        <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="filter" onchange="this.form.submit()">
                        <option value="all">All Notifications</option>
                        <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>Unread ({{ $unreadCount }})</option>
                        <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-6 text-right">
                    <span class="badge badge-primary p-2">Total: {{ $notifications->total() }}</span>
                    <span class="badge badge-success p-2">Unread: {{ $unreadCount }}</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item p-3 {{ $notification->isRead() ? 'bg-light' : '' }}">
                        <div class="row">
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-{{ $notification->color }} text-white d-inline-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="fas {{ $notification->icon }}"></i>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 {{ !$notification->isRead() ? 'font-weight-bold' : '' }}">
                                        {{ $notification->title }}
                                        @if(!$notification->isRead())
                                            <span class="badge badge-success ml-2">New</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($notification->message, 150) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($notification->sender)
                                            From: {{ $notification->sender->name }}
                                        @else
                                            From: System
                                        @endif
                                        • Type: {{ str_replace('_', ' ', ucfirst($notification->type)) }}
                                    </small>
                                    <div>
                                        <a href="{{ route('notifications.show', $notification->id) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(!$notification->isRead())
                                            <button onclick="markAsRead({{ $notification->id }})"
                                                    class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Mark Read
                                            </button>
                                        @endif
                                        <button onclick="deleteNotification({{ $notification->id }})"
                                                class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <p class="text-muted">No notifications found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $notifications->withQueryString()->links() }}
    </div>
</div>

<!-- Delete Notification Form (Hidden) -->
<form id="delete-notification-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Mark Read Form (Hidden) -->
<form id="mark-read-form" method="POST" style="display: none;">
    @csrf
</form>

<!-- Mark All Read Form (Hidden) -->
<form id="mark-all-read-form" action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Delete All Read Form (Hidden) -->
<form id="delete-all-read-form" action="{{ route('notifications.delete-all-read') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function markAsRead(id) {
        if (confirm('Mark this notification as read?')) {
            const form = document.getElementById('mark-read-form');
            form.action = `/notifications/${id}/read`;
            form.submit();
        }
    }

    function markAllRead() {
        if (confirm('Mark all notifications as read?')) {
            document.getElementById('mark-all-read-form').submit();
        }
    }

    function deleteNotification(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            const form = document.getElementById('delete-notification-form');
            form.action = `/notifications/${id}`;
            form.submit();
        }
    }

    function deleteAllRead() {
        if (confirm('Delete all read notifications?')) {
            document.getElementById('delete-all-read-form').submit();
        }
    }
</script>
@endpush
