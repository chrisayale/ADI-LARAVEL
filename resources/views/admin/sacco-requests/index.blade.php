@extends('layouts.app')

@section('title', 'Demandes de SACCO')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Demandes de SACCO</h1>
    </div>

    <!-- Status Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filtrer par statut</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group" aria-label="Status filter">
                        <a href="{{ route('admin.request-sacco.index', ['status' => 'all']) }}"
                           class="btn btn-outline-secondary {{ request()->input('status') == 'all' ? 'active' : '' }}">
                            Toutes
                        </a>
                        <a href="{{ route('admin.request-sacco.index', ['status' => 'pending']) }}"
                           class="btn btn-outline-warning {{ request()->input('status') == 'pending' ? 'active' : '' }}>
                            En attente
                        </a>
                        <a href="{{ route('admin.request-sacco.index', ['status' => 'approved']) }}"
                           class="btn btn-outline-success {{ request()->input('status') == 'approved' ? 'active' : '' }}">
                            Approuvées
                        </a>
                        <a href="{{ route('admin.request-sacco.index', ['status' => 'rejected']) }}"
                           class="btn btn-outline-danger {{ request()->input('status') == 'rejected' ? 'active' : '' }}">
                            Rejetées
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Liste des demandes de SACCO</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom du SACCO</th>
                                    <th>Demandeur</th>
                                    <th>Localisation</th>
                                    <th>Téléphone Mobile Money</th>
                                    <th>Statut</th>
                                    <th>Date de soumission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($requests->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">Aucune demande de SACCO trouvée.</td>
                                    </tr>
                                @else
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $request->name }}</td>
                                            <td>
                                                <div class="font-weight-bold">{{ $request->applicant_name }}</div>
                                                <div class="text-muted small">{{ $request->applicant_phone }}</div>
                                            </td>
                                            <td>
                                                <div class="text-sm">{{ $request->province }}, {{ $request->territory }}, {{ $request->city_village }}</div>
                                                @if($request->address)
                                                    <div class="text-muted small">{{ $request->address }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $request->phone_number }}</td>
                                            <td>
                                                @switch($request->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">En attente</span>
                                                    @break
                                                    @case('approved')
                                                        <span class="badge badge-success">Approuvée</span>
                                                    @break
                                                    @case('rejected')
                                                        <span class="badge badge-danger">Rejetée</span>
                                                    @break
                                                    @default
                                                        <span class="badge badge-info">{{ ucfirst($request->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.request-sacco.show', $request->id) }}"
                                                       class="btn btn-sm btn-info me-1">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                    @if($request->status == 'pending')
                                                        <a href="{{ route('admin.request-sacco.approve', $request->id) }}"
                                                           class="btn btn-sm btn-success me-1">
                                                            <i class="fas fa-check"></i> Approuver
                                                        </a>
                                                        <a href="{{ route('admin.request-sacco.reject', $request->id) }}"
                                                           class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times"></i> Rejeter
                                                        </a>
                                                    @elseif($request->status == 'approved')
                                                        @php
                                                            $sacco = $request->sacco;
                                                        @endphp
                                                        @if($sacco)
                                                            @php
                                                                $isActive = $sacco->status === 'active';
                                                                $statusText = $isActive ? 'Désactiver' : 'Activer';
                                                                $statusConfirmText = $isActive ? 'Désactiver' : 'Activer';
                                                            @endphp
                                                            <form action="{{ route('super-admin.sacos.toggle-status', $sacco->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm me-1 {{ $isActive ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                                        onclick="return confirm('Êtes-vous sûr de vouloir {{ strtolower($statusText) }} ce SACCO ?');">
                                                                    <i class="fas {{ $isActive ? 'fa-times' : 'fa-check' }}"></i>
                                                                    {{ $statusConfirmText }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
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
        </div>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-0">
                        Affichage {{ $requests->firstItem() }} à {{ $requests->lastItem() }} sur {{ $requests->total() }} entrées
                    </p>
                </div>
                <div>
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Make tables responsive and searchable if needed
    $(document).ready(function() {
        // You can add DataTables.js here if desired for advanced features
        // $('#dataTable').DataTable();
    });
</script>
@endpush