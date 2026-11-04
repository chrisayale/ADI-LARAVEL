{{-- resources/views/treasurer/verifications/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Vérifier le prêt - ' . $loan->application_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vérifier la demande de prêt</h1>
        <div>
            <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux en attente
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Demande de prêt N°{{ $loan->application_number }}</strong> -
                Veuillez vérifier tous les garants, garanties et documents avant de continuer.
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Member Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du membre</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom :</th>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        @if($loan->user->membership_number)
                                            <br><small class="text-muted">#{{ $loan->user->membership_number }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>{{ $loan->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td>{{ $loan->user->phone_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Adresse :</th>
                                    <td>{{ $loan->user->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ID National :</th>
                                    <td>{{ $loan->user->national_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Membre depuis :</th>
                                    <td>{{ $loan->user->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du prêt</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Montant demandé :</th>
                                    <td><strong>CDF {{ number_format($loan->amount_applied, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Objet :</th>
                                    <td>{{ $loan->purpose ?? 'Non spécifié' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de demande :</th>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                </tr>
                                @if($loan->amount_approved)
                                <tr>
                                    <th>Montant approuvé :</th>
                                    <td><strong class="text-success">CDF {{ number_format($loan->amount_approved, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Fréquence de remboursement :</th>
                                    <td>
                                        <span class="badge badge-info">
                                            @if($loan->repayment_frequency == 'daily')
                                                Quotidien
                                            @elseif($loan->repayment_frequency == 'weekly')
                                                Hebdomadaire
                                            @else
                                                Mensuel
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Durée :</th>
                                    <td>
                                        @if($loan->duration_value)
                                            {{ $loan->duration_value }}
                                            @if($loan->repayment_frequency == 'daily')
                                                jour(s)
                                            @elseif($loan->repayment_frequency == 'weekly')
                                                semaine(s)
                                            @else
                                                mois
                                            @endif
                                        @elseif($loan->duration_months)
                                            {{ $loan->duration_months }} mois
                                        @else
                                            Non spécifié
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Méthode d'amortissement :</th>
                                    <td>
                                        <span class="badge badge-info">
                                            @if($loan->amortization_method == 'pato_ndogo')
                                                Pato Ndogo
                                            @elseif($loan->amortization_method == 'french')
                                                Française (Standard)
                                            @elseif($loan->amortization_method == 'flat_rate')
                                                Taux fixe
                                            @elseif($loan->amortization_method == 'constant_capital')
                                                Capital constant
                                            @else
                                                {{ ucfirst($loan->amortization_method) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($loan->interest_rate)
                                <tr>
                                    <th>Taux d'intérêt :</th>
                                    <td>
                                        @if($loan->interest_rate_label)
                                            {{ $loan->interest_rate_label }}
                                        @else
                                            {{ number_format($loan->interest_rate, 2) }}% par an
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if($loan->monthly_repayment)
                                <tr>
                                    <th>Paiement {{ $loan->repayment_frequency == 'daily' ? 'journalier' : ($loan->repayment_frequency == 'weekly' ? 'hebdomadaire' : 'mensuel') }} :</th>
                                    <td><strong>CDF {{ number_format($loan->monthly_repayment, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($loan->total_repayable)
                                <tr>
                                    <th>Total à rembourser :</th>
                                    <td><strong>CDF {{ number_format($loan->total_repayable, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Garantie :</th>
                                    <td>
                                        @if($loan->has_collateral)
                                            <span class="badge badge-success">Avec garantie</span>
                                        @else
                                            <span class="badge badge-secondary">Sans garantie</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($loan->has_collateral)
                                <tr>
                                    <th>Type de garantie :</th>
                                    <td>{{ ucfirst($loan->collateral_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Valeur de la garantie :</th>
                                    <td>CDF {{ number_format($loan->collateral_value, 2) }}</td>
                                </tr>
                                @endif
                                @if($loan->apply_late_penalty !== null)
                                <tr>
                                    <th>Pénalité de retard :</th>
                                    <td>
                                        <span class="badge badge-{{ $loan->apply_late_penalty ? 'success' : 'secondary' }}">
                                            {{ $loan->apply_late_penalty ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if($loan->late_penalty_amount)
                                <tr>
                                    <th>Montant pénalité :</th>
                                    <td><strong>CDF {{ number_format($loan->late_penalty_amount, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($loan->has_collateral && $loan->collateral_description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Description de la garantie :</strong>
                            <p class="mb-0">{{ $loan->collateral_description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guarantors Verification -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vérification des garants</h6>
                </div>
                <div class="card-body">
                    @if($loan->guarantors->count() > 0)
                        @foreach($loan->guarantors as $guarantor)
                        <div class="border rounded p-3 mb-3" id="guarantor-{{ $guarantor->id }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="font-weight-bold">{{ $guarantor->name }}</h6>
                                    <p class="mb-1"><i class="fas fa-phone mr-2"></i> {{ $guarantor->phone }}</p>
                                    <p class="mb-1"><i class="fas fa-id-card mr-2"></i> ID : {{ $guarantor->id_number }}</p>
                                    @if($guarantor->email)
                                        <p class="mb-1"><i class="fas fa-envelope mr-2"></i> {{ $guarantor->email }}</p>
                                    @endif
                                    @if($guarantor->relationship)
                                        <p class="mb-0"><i class="fas fa-users mr-2"></i> Relation : {{ $guarantor->relationship }}</p>
                                    @endif
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $guarantor->status == 'approved' ? 'success' : ($guarantor->status == 'rejected' ? 'danger' : 'warning') }} p-2"
                                              id="guarantor-status-{{ $guarantor->id }}">
                                            {{ translate_status($guarantor->status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success"
                                                onclick="verifyGuarantor({{ $guarantor->id }}, 'approved')">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="verifyGuarantor({{ $guarantor->id }}, 'rejected')">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if($guarantor->address)
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $guarantor->address }}
                            </small>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3">Aucun garant pour ce prêt</p>
                    @endif
                </div>
            </div>

            <!-- Documents Verification -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vérification des documents</h6>
                </div>
                <div class="card-body">
                    @if($loan->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Catégorie</th>
                                        <th>Taille</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loan->documents as $document)
                                    <tr id="document-{{ $document->id }}">
                                        <td>
                                            <i class="fas {{ $document->file_icon }} mr-2"></i>
                                            <a href="{{ route('admin.loans.download-document', $document) }}" target="_blank">
                                                {{ Str::limit($document->document_name, 40) }}
                                            </a>
                                            @if($document->description)
                                                <br><small class="text-muted">{{ $document->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($document->category)) }}</span>
                                        </td>
                                        <td>{{ $document->formatted_size }}</td>
                                        <td>
                                            <span class="badge badge-{{ $document->verification_status == 'verified' ? 'success' : ($document->verification_status == 'rejected' ? 'danger' : 'warning') }}"
                                                  id="document-status-{{ $document->id }}">
                                                {{ translate_status($document->verification_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-success"
                                                        onclick="verifyDocument({{ $document->id }}, 'verified')">
                                                    <i class="fas fa-check"></i> Vérifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="verifyDocument({{ $document->id }}, 'rejected')">
                                                    <i class="fas fa-times"></i> Rejeter
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Aucun document téléchargé pour ce prêt</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Verification Progress -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Progression de la vérification</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Garants</span>
                            <span>
                                {{ $loan->guarantors->where('status', 'approved')->count() }}/{{ $loan->guarantors->count() }}
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $loan->guarantors->count() > 0 ? ($loan->guarantors->where('status', 'approved')->count() / $loan->guarantors->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Documents</span>
                            <span>
                                {{ $loan->documents->where('verification_status', 'verified')->count() }}/{{ $loan->documents->count() }}
                            </span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: {{ $loan->documents->count() > 0 ? ($loan->documents->where('verification_status', 'verified')->count() / $loan->documents->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Final Verification Form -->
                    <form action="{{ route('treasurer.verifications.verify', $loan) }}" method="POST" id="verifyForm">
                        @csrf
                        <div class="form-group">
                            <label for="verification_notes">Notes de vérification</label>
                            <textarea class="form-control" id="verification_notes" name="verification_notes"
                                      rows="3" required placeholder="Entrez les notes de vérification..."></textarea>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="guarantors_verified" name="guarantors_verified" value="1" required>
                            <label class="form-check-label" for="guarantors_verified">
                                Je confirme que tous les garants ont été vérifiés
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="collateral_verified" name="collateral_verified" value="1"
                                   {{ $loan->has_collateral ? '' : 'disabled' }} required>
                            <label class="form-check-label" for="collateral_verified">
                                Je confirme que la garantie a été vérifiée
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="documents_verified" name="documents_verified" value="1" required>
                            <label class="form-check-label" for="documents_verified">
                                Je confirme que tous les documents ont été vérifiés
                            </label>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check-circle"></i> Vérifier le prêt
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times-circle"></i> Rejeter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('treasurer.verifications.reject', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rejeter la demande de prêt</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Le rejet de ce prêt le renverra à l'admin pour révision.
                    </div>
                    <div class="form-group">
                        <label for="rejection_reason">Motif du rejet</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                  rows="4" required placeholder="Veuillez expliquer pourquoi ce prêt est rejeté..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter le prêt</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function verifyGuarantor(guarantorId, status) {
        if (!confirm('Êtes-vous sûr de vouloir ' + status + ' ce garant ?')) {
            return;
        }

        fetch('/treasurer/verifications/guarantor/' + guarantorId + '/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                verification_notes: prompt('Entrez les notes de vérification (optionnel) :') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusBadge = document.getElementById('guarantor-status-' + guarantorId);
                statusBadge.className = 'badge badge-' + (status === 'approved' ? 'success' : 'danger') + ' p-2';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                // Update progress bar
                updateProgress();

                alert(data.message);
            } else {
                alert('Erreur : ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Échec de la vérification du garant. Veuillez réessayer.');
        });
    }

    function verifyDocument(documentId, status) {
        if (!confirm('Êtes-vous sûr de vouloir ' + status + ' ce document ?')) {
            return;
        }

        fetch('/treasurer/verifications/document/' + documentId + '/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                verification_status: status,
                verification_notes: prompt('Entrez les notes de vérification (optionnel) :') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusBadge = document.getElementById('document-status-' + documentId);
                statusBadge.className = 'badge badge-' + (status === 'verified' ? 'success' : 'danger');
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                // Update progress
                updateProgress();

                alert(data.message);
            } else {
                alert('Erreur : ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Échec de la vérification du document. Veuillez réessayer.');
        });
    }

    function updateProgress() {
        // Reload the page to update progress bars (simplest solution)
        location.reload();
    }

    // Enable/disable checkboxes based on verification status
    document.addEventListener('DOMContentLoaded', function() {
        const guarantorsCheck = document.getElementById('guarantors_verified');
        const documentsCheck = document.getElementById('documents_verified');
        const collateralCheck = document.getElementById('collateral_verified');

        // You can add logic here to auto-check if everything is verified
        @if($loan->guarantors->where('status', 'approved')->count() == $loan->guarantors->count() && $loan->guarantors->count() > 0)
            // Optionally auto-check
        @endif
    });
</script>
@endpush