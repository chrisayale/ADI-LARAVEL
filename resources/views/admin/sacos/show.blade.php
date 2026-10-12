@extends('layouts.app')

@section('title', 'Voir le SACCO')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Voir le SACCO: {{ $sacco->name }}</h1>
        <a href="{{ route('admin.sacos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- SACCO Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du SACCO</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nom:</dt>
                        <dd class="col-sm-9">{{ $sacco->name }}</dd>

                        <dt class="col-sm-3">Code:</dt>
                        <dd class="col-sm-9">{{ $sacco->code }}</dd>

                        <dt class="col-sm-3">Numéro d'enregistrement:</dt>
                        <dd class="col-sm-9">{{ $sacco->registration_number ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Adresse:</dt>
                        <dd class="col-sm-9">{{ $sacco->address ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Téléphone:</dt>
                        <dd class="col-sm-9">{{ $sacco->phone ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Email officiel:</dt>
                        <dd class="col-sm-9">
                            {{ $sacco->email ?? 'Aucun' }}
                            @if ($sacco->email)
                                <br>
                                <small class="text-muted">
                                    (Email officiel généré automatiquement lors de l'approbation)
                                </small>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Devise primaire:</dt>
                        <dd class="col-sm-9">{{ $sacco->primary_currency }}</dd>

                        <dt class="col-sm-3">Devise secondaire:</dt>
                        <dd class="col-sm-9">{{ $sacco->secondary_currency }}</dd>

                        <dt class="col-sm-3">Statut:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-{{ $sacco->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($sacco->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Date de création:</dt>
                        <dd class="col-sm-9">{{ $sacco->created_at ?? 'N/A' }}</dd>

                        <!-- Official Account Status -->
                        @php
                            $officialUser = \App\Models\User::where('sacco_id', $sacco->id)
                                ->where('name', 'Compte Officiel - ' . $sacco->name)
                                ->first();
                        @endphp
                        <dt class="col-sm-3">Compte officiel:</dt>
                        <dd class="col-sm-9">
                            @if ($officialUser)
                                <span class="badge badge-{{ $officialUser->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $officialUser->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                                @if ($officialUser->status === 'active')
                                    <form action="{{ route('admin.sacos.toggle-official-account', ['sacco' => $sacco->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">
                                            Désactiver
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.sacos.toggle-official-account', ['sacco' => $sacco->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success ms-2">
                                            Activer
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">Aucun compte officiel trouvé</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
