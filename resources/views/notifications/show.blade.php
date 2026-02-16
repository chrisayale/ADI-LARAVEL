{{-- resources/views/notifications/show.blade.php --}}
@extends('layouts.app')

@section('title', $notification->title)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notification Details</h1>
        <div>
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Notifications
            </a>
            <button onclick="deleteNotification({{ $notification->id }})" class="btn btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Notification Content -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-{{ $notification->color }} text-white d-flex align-items-center justify-content-center mr-3"
                         style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fas {{ $notification->icon }}"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold">{{ $notification->title }}</h5>
                        <small class="text-muted">
                            {{ $notification->created_at->format('F d, Y \a\t h:i A') }}
                            @if($notification->isRead())
                                • Read {{ $notification->read_at->diffForHumans() }}
                            @else
                                • <span class="text-success">New</span>
                            @endif
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($notification->message)) !!}
                    </div>

                    @if($notification->data)
                        <hr>
                        <h6 class="font-weight-bold">Additional Data:</h6>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</code></pre>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Sender Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sender Information</h6>
                </div>
                <div class="card-body">
                    @if($notification->sender)
                        <div class="text-center mb-3">
                            @if($notification->sender->profile_photo)
                                <img src="{{ asset('storage/' . $notification->sender->profile_photo) }}"
                                     class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ strtoupper(substr($notification->sender->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <h5 class="text-center">{{ $notification->sender->name }}</h5>
                        <p class="text-center text-muted mb-0">{{ $notification->sender->email }}</p>
                        @if($notification->sender->hasRole('admin'))
                            <p class="text-center"><span class="badge badge-primary">Administrator</span></p>
                        @elseif($notification->sender->hasRole('treasurer'))
                            <p class="text-center"><span class="badge badge-info">Treasurer</span></p>
                        @endif
                    @else
                        <div class="text-center">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 100px; height: 100px; font-size: 2.5rem;">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h5>System Notification</h5>
                            <p class="text-muted">Automated system message</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notification Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Type:</th>
                            <td>
                                <span class="badge badge-{{ $notification->color }} p-2">
                                    {{ str_replace('_', ' ', ucfirst($notification->type)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($notification->isRead())
                                    <span class="badge badge-success p-2">Read</span>
                                    <br><small class="text-muted">{{ $notification->read_at->format('d M Y H:i') }}</small>
                                @else
                                    <span class="badge badge-warning p-2">Unread</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Received:</th>
                            <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Notification ID:</th>
                            <td><small class="text-muted">{{ $notification->id }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if(!$notification->isRead())
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="d-inline-block w-100 mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Mark as Read
                            </button>
                        </form>
                    @endif

                    <button onclick="deleteNotification({{ $notification->id }})" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Delete Notification
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="delete-notification-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteNotification(id) {
        if (confirm('Are you sure you want to delete this notification?')) {
            const form = document.getElementById('delete-notification-form');
            form.action = `/notifications/${id}`;
            form.submit();
        }
    }
</script>
@endpush
