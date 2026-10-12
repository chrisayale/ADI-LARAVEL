@extends('layouts.app')

@section('title', 'Détails de la demande de SACCO')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails de la demande de SACCO</h1>
        <a href="{{ route('admin.request-sacco.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Request Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de la demande</h6>
                </div>
                <div class="card-body">
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
                            <div class="mb-3">
                                <strong>Email officiel :</strong>
                                <p class="mb-1">{{ $requestSacco->email or 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Localisation :</strong>
                                <p class="mb-1">{{ $requestSacco->province }}, {{ $requestSacco->territory }}, {{ $requestSacco->city_village }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Adresse détaillée :</strong>
                                <p class="mb-1">{{ $requestSacco->address or 'Non renseignée' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nom du demandeur :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Fonction / Rôle :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_role }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Email du demandeur :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_email or 'Non renseigné' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Téléphone du demandeur :</strong>
                                <p class="mb-1">{{ $requestSacco->applicant_phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Statut :</strong>
                                <p class="mb-1">
                                    @switch($requestSacco->status)
                                        @case('pending')
                                            <span class="badge badge-warning">{{ ucfirst($requestSacco->status) }}</span>
                                        @break
                                        @case('approved')
                                            <span class="badge badge-success">{{ ucfirst($requestSacco->status) }}</span>
                                        @break
                                        @case('rejected')
                                            <span class="badge badge-danger">{{ ucfirst($requestSacco->status) }}</span>
                                        @break
                                        @default
                                            <span class="badge badge-info">{{ ucfirst($requestSacco->status) }}</span>
                                    @endswitch
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Date de soumission :</strong>
                                <p class="mb-1">{{ $requestSacco->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($requestSacco->reviewed_at)
                                <div class="mb-3">
                                    <strong>Date de révision :</strong>
                                    <p class="mb-1">{{ $requestSacco->reviewed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($requestSacco->description)
                        <hr class="my-4">
                        <div class="mb-3">
                            <strong>Motif / Description :</strong>
                            <p class="mb-1">{{ nl2br(e($requestSacco->description)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection