@extends('layouts.app')

@section('title', 'Approuver la demande de SACCO')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Approuver la demande de SACCO</h1>
        <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Approval Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Approuver la demande de SACCO</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.request-sacco.storeApproval', $requestSacco->id) }}">
                        @csrf

                        <div class="mb-4">
                            <h6 class="text-muted">Informations de la demande</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Nom du SACCO :</strong>
                                        <p class="mb-1">{{ $requestSacco->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Numéro Mobile Money :</strong>
                                        <p class="mb-1">{{ $requestSacco->phone_number }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Nom du demandeur :</strong>
                                        <p class="mb-1">{{ $requestSacco->applicant_name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Téléphone du demandeur :</strong>
                                        <p class="mb-1">{{ $requestSacco->applicant_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h6 class="text-muted">Détails de l'approbation</h6>

                            <div class="mb-3">
                                <label class="form-label">Code SACCO unique *</label>
                                <input type="text" name="code" class="form-control" required
                                       value="{{ old('code', $requestSacco->code ?: '') }}"
                                       placeholder="Laissez vide pour générer automatiquement">
                                <small class="text-muted">Si laissé vide, un code unique sera généré automatiquement</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Coordonnées GPS (optionnel)</label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Latitude</label>
                                        <input type="number" name="latitude" class="form-control" step="0.000001"
                                               value="{{ old('latitude') }}" placeholder="-4.3250">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Longitude</label>
                                        <input type="number" name="longitude" class="form-control" step="0.000001"
                                               value="{{ old('longitude') }}" placeholder="15.3222">
                                    </div>
                                </div>
                                <small class="text-muted">Les coordonnées GPS seront utilisées pour localiser le SACCO sur une carte</small>
                            </div>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Lors de l'approbation, le système créera automatiquement :
                            <ul class="mb-0 mt-2">
                                <li>Un e-mail officiel sous le domaine @adi.com (basé sur le nom du SACCO)</li>
                                <li>Un compte utilisateur avec le mot de par défaut : <code>avec1234</code></li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i> Approuver et créer le SACCO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection