@extends('layouts.app')

@section('title', 'Liste des SACCOs (Super Admin)')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des SACCOs (Super Admin)</h1>
        <a href="{{ route('super-admin.sacos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw me-1"></i> Nouveau SACCO
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rechercher et filtrer</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('super-admin.sacos.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Rechercher par nom, code ou numéro d'enregistrement"
                                   value="{{ request()->get('search') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="status" class="form-control form-control-sm">
                                <option value="all">Tous les statuts</option>
                                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>
                                    Actif
                                </option>
                                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactif
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-search fa-sm"></i> Rechercher
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" onclick="window.location.href = '{{ route('super-admin.sacos.index') }}'"
                                class="btn btn-secondary btn-sm btn-block">
                            <i class="fas fa-sync-alt fa-sm"></i> Réinitialiser
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
                    <h6 class="m-0 font-weight-bold text-primary">Liste des SACCOs</h6>
                </div>
                <div class="col-md-6 text-right">
                    @if ($sacos->total() > 0)
                        <span class="text-muted">
                            Affichage {{ $sacos->firstItem() }} à {{ $sacos->lastItem() }} sur {{ $sacos->total() }} entrée{{ $sacos->total() > 1 ? 's' : '' }}
                        </span>
                    @else
                        <span class="text-muted">Aucun SACCO trouvé</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Code</th>
                            <th>Email officiel</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sacos->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Aucun SACCO trouvé.</td>
                            </tr>
                        @else
                            @foreach ($sacos as $sacco)
                                <tr>
                                    <td>{{ $sacco->name }}</td>
                                    <td>{{ $sacco->code }}</td>
                                    <td>
                                        {{ $sacco->email ?? 'Aucun' }}
                                        @if ($sacco->email)
                                            <br>
                                            <small class="text-muted">
                                                (Email officiel généré automatiquement)
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sacco->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ translate_status($sacco->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Status toggle button (kept outside dropdown) -->
                                        @php
                                            $isActive = $sacco->status === 'active';
                                            $statusText = $isActive ? 'Désactiver' : 'Activer';
                                            $statusConfirmText = $isActive ? 'Désactiver' : 'Activer';
                                        @endphp
                                        <form action="{{ route('super-admin.sacos.toggle-status', $sacco->id) }}" method="POST" class="d-inline mb-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-block w-100 {{ $isActive ? 'btn-danger' : 'btn-success' }}"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir {{ strtolower($statusText) }} ce SACCO ?');">
                                                <i class="fas {{ $isActive ? 'fa-times' : 'fa-check' }} me-1"></i>
                                                {{ $statusText }}
                                            </button>
                                        </form>

                                        <!-- Actions dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton{{ $sacco->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $sacco->id }}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('super-admin.sacos.show', $sacco->id) }}">
                                                        <i class="fas fa-eye me-1"></i> Voir les détails
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('super-admin.sacos.edit', $sacco->id) }}">
                                                        <i class="fas fa-edit me-1"></i> Éditer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('super-admin.sacos.official-account', $sacco->id) }}">
                                                        <i class="fas fa-cog me-1"></i> Gérer le compte officiel
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('super-admin.sacos.destroy', $sacco->id) }}" method="POST" class="dropdown-item p-0 m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger p-0 m-0 w-100"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce SACCO ? Cette action est irréversible.');">
                                                            <i class="fas fa-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-center p-3">
                                    <div class="bg-info text-white rounded p-2">
                                        <strong>DEBUG:</strong> View loaded successfully.
                                        Sac count: {{ $sacos->count() }}.
                                        @php
                                            $user = auth()->user();
                                            $userRoleInfo = $user && $user->roles ? $user->roles->pluck('name')->implode(', ') : ($user ? 'no roles' : 'not authenticated');
                                        @endphp>
                                        User role: {{ $userRoleInfo }}.
                                        Is super admin: {{ $user && $user->hasRole('super_admin') ? 'yes' : 'no' }}.
                                    </div>
                                </td>
                            </tr>
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