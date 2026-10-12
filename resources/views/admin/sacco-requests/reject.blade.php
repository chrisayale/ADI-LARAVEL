@extends('layouts.app')

@section('title', 'Rejeter la demande de SACCO')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rejeter la demande de SACCO</h1>
        <a href="{{ route('admin.sacco-requests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Rejection Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rejeter la demande de SACCO</h6>
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

                    <form method="POST" action="{{ route('admin.request-sacco.storeRejection', $requestSacco->id) }}">
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
                            <h6 class="text-muted">Motif du rejet</h6>

                            <div class="mb-3">
                                <label class="form-label">Raison du rejet *</label>
                                <textarea name="reason" class="form-control" rows="4" required
                                          placeholder="Expliquez pourquoi cette demande est rejetée...">{{ old('reason') }}</textarea>
                                <div class="invalid-feedback">
                                    Veuillez fournir une raison pour le rejet.
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-times me-2"></i> Confirmer le rejet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection