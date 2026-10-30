@extends('layouts.app')

@section('title', __('sacos_list'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('sacos_list') }}</h1>
        <a href="{{ route('admin.sacos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw me-1"></i> {{ __('new_sacco') }}
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('search_and_filter') }}</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sacos.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="{{ __('search_by_name_code_registration') }}"
                                   value="{{ request()->get('search') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="status" class="form-control form-control-sm">
                                <option value="all">{{ __('all_statuses') }}</option>
                                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>
                                    {{ __('active') }}
                                </option>
                                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>
                                    {{ __('inactive') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-search fa-sm"></i> {{ __('search') }}
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" onclick="window.location.href = '{{ route('admin.sacos.index') }}'"
                                class="btn btn-secondary btn-sm btn-block">
                            <i class="fas fa-sync-alt fa-sm"></i> {{ __('reset') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SACCOs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('sacos_list') }}</h6>
                </div>
                <div class="col-md-6 text-right">
                    @if ($sacos->total() > 0)
                        <span class="text-muted">
                            {{ __('showing_entries', ['first' => $sacos->firstItem(), 'last' => $sacos->lastItem(), 'total' => $sacos->total()]) }}
                        </span>
                    @else
                        <span class="text-muted">{{ __('no_sacos_found') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('name') }}</th>
                            <th>{{ __('code') }}</th>
                            <th>{{ __('official_email') }}</th>
                            <th>{{ __('status') }}</th>
                            <th>{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sacos->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">{{ __('no_sacos_found') }}</td>
                            </tr>
                        @else
                            @foreach ($sacos as $sacco)
                                <tr>
                                    <td>{{ $sacco->name }}</td>
                                    <td>{{ $sacco->code }}</td>
                                    <td>
                                        {{ $sacco->email ?? __('none') }}
                                        @if ($sacco->email)
                                            <br>
                                            <small class="text-muted">
                                                ({{ __('official_email_auto_generated') }})
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sacco->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ translate_status($sacco->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('admin.sacos.show', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-eye"></i> {{ __('view') }}
                                            </a>

                                            <a href="{{ route('admin.sacos.edit', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-info me-1">
                                                <i class="fas fa-edit"></i> {{ __('edit') }}
                                            </a>

                                            <form action="{{ route('admin.sacos.destroy', $sacco->id) }}" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('{{ __('confirm_delete_sacco') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger me-1">
                                                    <i class="fas fa-trash"></i> {{ __('delete') }}
                                                </button>
                                            </form>

                                            <!-- Gérer le compte officiel button -->
                                            <a href="{{ route('admin.sacos.official-account', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-success me-1">
                                                <i class="fas fa-cog"></i> {{ __('manage_official_account') }}
                                            </a>

                                            <!-- Activer/Désactiver SACCO button -->
                                            @php
                                                $isActive = $sacco->status === 'active';
                                                $statusText = $isActive ? __('deactivate') : __('activate');
                                                $statusConfirmText = $isActive ? __('deactivate') : __('activate');
                                            @endphp
                                            <form action="{{ route('admin.sacos.toggle-status', $sacco->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm me-1 {{ $isActive ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        onclick="return confirm('{{ __('confirm_toggle_status', ['action' => strtolower($statusText)] ) }}');">
                                                    <i class="fas {{ $isActive ? 'fa-times' : 'fa-check' }}"></i>
                                                    {{ $statusConfirmText }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between">
        <div>
            {{ $sacos->links() }}
        </div>
    </div>
</div>
@endsection