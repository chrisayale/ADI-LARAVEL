@extends('layouts.app')

@section('title', 'Liste des SACCOs')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des SACCOs</h1>
        <a href="{{ route('admin.sacos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw me-1"></i> Nouveau SACCO
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rechercher et filtrer</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sacos.index') }}">
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
                        <button type="button" onclick="window.location.href = '{{ route('admin.sacos.index') }}'"
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
                                            {{ ucfirst($sacco->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="{{ route('admin.sacos.show', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>

                                            <a href="{{ route('admin.sacos.edit', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-info me-1">
                                                <i class="fas fa-edit"></i> Éditer
                                            </a>

                                            <form action="{{ route('admin.sacos.destroy', $sacco->id) }}" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce SACCO ? Cette action est irréversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger me-1">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>

                                            <!-- Gérer le compte officiel button -->
                                            <a href="{{ route('admin.sacos.official-account', $sacco->id) }}"
                                               class="btn btn-sm btn-outline-success me-1">
                                                <i class="fas fa-cog"></i> Gérer le compte officiel
                                            </a>

                                            <!-- Activer/Désactiver SACCO button -->
                                            @php
                                                $isActive = $sacco->status === 'active';
                                                $statusText = $isActive ? 'Désactiver' : 'Activer';
                                                $statusConfirmText = $isActive ? 'Désactiver' : 'Activer';
                                            @endphp
                                            <form action="{{ route('admin.sacos.toggle-status', $sacco->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm me-1 {{ $isActive ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir {{ strtolower($statusText) }} ce SACCO ?');">
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