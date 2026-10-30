@extends('layouts.app')

@section('title', __('manage_members'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('manage_members') }}</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('add_new_user') }}
        </a>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('filter_members') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">{{ __('search') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="{{ __('search_placeholder') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="status">{{ __('status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all">{{ __('all_status') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('inactive') }}</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('suspended') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="role">{{ __('role') }}</label>
                    <select class="form-control" id="role" name="role">
                        <option value="all">{{ __('all_roles') }}</option>
                        <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>{{ __('member') }}</option>
                        <option value="treasurer" {{ request('role') == 'treasurer' ? 'selected' : '' }}>{{ __('treasurer') }}</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('admin') }}</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> {{ __('filter') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('members_list') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('member') }}</th>
                            <th>{{ __('membership_no') }}</th>
                            <th>{{ __('contact') }}</th>
                            <th>{{ __('joined_date') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('role') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->membership_number ?? __('n_a') }}</td>
                            <td>
                                {{ $user->phone_number ?? __('n_a') }}<br>
                                <small class="text-muted">{{ $user->address ?? __('n_a') }}</small>
                            </td>
                            <td>{{ $user->membership_date ? $user->membership_date->format('d M Y') : $user->created_at->format('d M Y') }}</td>
                           <td>
    <span class="badge bg-{{
        $user->status === 'active' ? 'success' :
        ($user->status === 'inactive' ? 'warning' : 'danger')
    }} px-3 py-2">
        {{ translate_status($user->status) }}
    </span>
</td>
<td>
    @foreach($user->roles as $role)
        <span class="badge bg-info">{{ translate_status($role->name) }}</span>
    @endforeach
</td>

                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm" title="{{ __('view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm" title="{{ __('edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'warning' : 'success' }} btn-sm"
                                            title="{{ $user->status === 'active' ? __('deactivate') : __('activate') }}">
                                        <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                @if(!$user->hasRole('admin'))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="{{ __('delete') }}"
                                            onclick="return confirm('{{ __('confirm_delete') }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">{{ __('no_members_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
