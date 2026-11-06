@extends('layouts.app')

@section('title', 'Créer une Demande de Prêt')

@section('styles')
<style>
    /* Sticky Footer Styles */
    .sticky-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0) 0%, #ffffff 20%);
        padding: 1rem 1.5rem;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        border-top: 1px solid #e3e6f0;
    }

    .sticky-footer .btn {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sticky-footer .btn-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
    }

    .sticky-footer .btn-primary:hover {
        background: linear-gradient(135deg, #2e59d9 0%, #1a3c9e 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(78, 115, 223, 0.4);
    }

    .sticky-footer .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(108, 117, 125, 0.4);
    }

    /* Ensure form has bottom padding to not be hidden behind sticky footer */
    #loan-form {
        padding-bottom: 120px;
    }

    @media (max-width: 767.98px) {
        .sticky-footer {
            display: none;
        }

        #loan-form {
            padding-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer une Demande de Prêt</h1>
        <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulaire de Demande de Prêt</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.loans.store') }}" method="POST" enctype="multipart/form-data" id="loan-form">
                @csrf

                <!-- Member Selection with QR Code / Manual Input -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Sélectionner un Membre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control @error('user_id') is-invalid @enderror"
                                        id="user_id" name="user_id" required>
                                    <option value="">Choisir un membre...</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->membership_number ?? 'Aucun #' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary" id="btn-qr-scanner"
                                            title="Scanner le QR Code du membre" data-toggle="tooltip">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-manual-input"
                                            title="Saisie manuelle du code membre" data-toggle="tooltip">
                                        <i class="fas fa-keyboard"></i>
                                    </button>
                                </div>
                            </div>
                            @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount_applied">Montant demandé (CDF) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount_applied') is-invalid @enderror"
                                   id="amount_applied" name="amount_applied" value="{{ old('amount_applied') }}"
                                   required min="1">
                            @error('amount_applied')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Loan Purpose -->
                <div class="form-group">
                    <label for="purpose">Objet du prêt <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('purpose') is-invalid @enderror"
                              id="purpose" name="purpose" rows="3" required>{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Loan Calculator with Better UI -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Calculateur de Prêt - Aperçu de l'Échéancier</h6>
                        <span class="badge badge-light">Aperçu seulement</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preview_interest_rate">Taux d'intérêt (%)</label>
                                    <input type="number" step="0.1" class="form-control"
                                           id="preview_interest_rate" value="12" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preview_duration">Durée</label>
                                    <input type="number" class="form-control"
                                           id="preview_duration" name="preview_duration" value="12" min="1" max="365">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preview_frequency">Fréquence de remboursement</label>
                                    <select class="form-control" id="preview_frequency" name="preview_frequency">
                                        <option value="daily" selected>Quotidien</option>
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="monthly">Mensuel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="preview_amortization">Méthode d'amortissement</label>
                                    <select class="form-control" id="preview_amortization" name="preview_amortization">
                                        <option value="pato_ndogo" selected>Pato Ndogo</option>
                                        <option value="french">Système Français / Dégressif</option>
                                        <option value="flat_rate">Taux Fixe / Plat</option>
                                        <option value="constant_capital">Capital Constant / Amortissable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="calculateLoan">
                                        <i class="fas fa-calculator"></i> Calculer l'échéancier
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields to sync preview calculator with actual loan fields -->
                        <input type="hidden" name="repayment_frequency" id="repayment_frequency" value="monthly">
                        <input type="hidden" name="duration_value" id="duration_value" value="12">
                        <input type="hidden" name="amortization_method" id="amortization_method" value="pato_ndogo">
                        <input type="hidden" name="interest_rate" id="interest_rate" value="12">

                        <!-- Loan Summary Cards -->
                        <div class="row mt-3" id="loanSummary" style="display: none;">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body py-2">
                                        <small id="paymentLabel">Paiement Mensuel</small>
                                        <h6 id="monthlyPayment">CDF 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body py-2">
                                        <small>Intérêt Total</small>
                                        <h6 id="totalInterest">CDF 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body py-2">
                                        <small>Paiement Total</small>
                                        <h6 id="totalPayment">CDF 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body py-2">
                                        <small>Premier Paiement</small>
                                        <h6 id="firstPaymentDate">{{ now()->addMonth()->format('M Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adjusted Amount Field (Pato Ndogo only) -->
                        <div class="row mt-3" id="adjustedAmountRow" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adjusted_daily_amount">Montant ajusté / Arrondi (CDF) <span class="text-info">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="adjusted_daily_amount"
                                           name="adjusted_daily_amount" placeholder="Entrez le montant journalier arrondi" min="1">
                                    <small class="text-muted">Modifiez le montant journalier théorique pour un arrondi pratique. L'écart sera suivi comme surplus/manquant.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="surplus_amount">Écart (Surplus / Manquant)</label>
                                    <input type="text" class="form-control" id="surplus_amount" readonly>
                                    <small class="text-muted" id="surplus_description"></small>
                                </div>
                            </div>
                            <!-- Hidden fields for form submission -->
                            <input type="hidden" id="theoretical_daily_amount" name="theoretical_daily_amount" value="">
                            <input type="hidden" id="total_surplus_amount" name="total_surplus_amount" value="">
                            <input type="hidden" id="surplus_type" name="surplus_type" value="">
                        </div>

                        <!-- Late Penalty Settings -->
                        <div class="row mt-3" id="latePenaltyRow" style="display: none;">
                            <div class="col-md-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark py-2">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Paramètres de Pénalité de Retard</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="apply_late_penalty" name="apply_late_penalty" value="1" {{ old('apply_late_penalty', true) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="apply_late_penalty">
                                                            <strong>Appliquer une pénalité de retard</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="late_penalty_amount">Montant de la pénalité (CDF)</label>
                                                    <input type="number" step="1" class="form-control" id="late_penalty_amount" name="late_penalty_amount" value="{{ old('late_penalty_amount', 2000) }}" min="0">
                                                    <small class="text-muted">Montant fixe appliqué par paiement en retard. Par défaut : 2 000 CDF (modifiable manuellement)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amortization Table -->
                        <div class="table-responsive mt-3" id="amortizationTable" style="display: none;">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date de paiement</th>
                                        <th id="paymentHeader">Paiement Mensuel</th>
                                        <th>Principal</th>
                                        <th>Intérêt</th>
                                        <th>Solde</th>
                                    </tr>
                                </thead>
                                <tbody id="amortizationBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                                <!-- Collateral Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Informations sur la Garantie</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="has_collateral" name="has_collateral" value="1" {{ old('has_collateral', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="has_collateral">
                                    <strong>A une Garantie</strong>
                                </label>
                            </div>
                        </div>

                        <div id="collateral-fields" style="{{ old('has_collateral', true) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_type">Type de Garantie <span class="text-danger">*</span></label>
                                        <select class="form-control @error('collateral_type') is-invalid @enderror"
                                                id="collateral_type" name="collateral_type" required>
                                            <option value="">Sélectionner le type</option>
                                            <option value="land" {{ old('collateral_type') == 'land' ? 'selected' : '' }}>Terrain/Propriété</option>
                                            <option value="vehicle" {{ old('collateral_type') == 'vehicle' ? 'selected' : '' }}>Véhicule</option>
                                            <option value="building" {{ old('collateral_type') == 'building' ? 'selected' : '' }}>Bâtiment</option>
                                            <option value="equipment" {{ old('collateral_type') == 'equipment' ? 'selected' : '' }}>Équipement</option>
                                            <option value="savings" {{ old('collateral_type') == 'savings' ? 'selected' : '' }}>Épargne</option>
                                            <option value="other" {{ old('collateral_type') == 'other' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                        @error('collateral_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_value">Valeur Estimée (CDF) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control @error('collateral_value') is-invalid @enderror"
                                               id="collateral_value" name="collateral_value" value="{{ old('collateral_value') }}" required min="1">
                                        @error('collateral_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="collateral_description">Description de la Garantie <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('collateral_description') is-invalid @enderror"
                                                  id="collateral_description" name="collateral_description" rows="2" placeholder="Fournir une description détaillée de la garantie" required>{{ old('collateral_description') }}</textarea>
                                        @error('collateral_description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!-- Guarantors Information (External People) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Informations sur les Garants</h6>
                        <small class="text-muted">Ajouter des garants externes qui ne sont pas membres du système</small>
                    </div>
                    <div class="card-body">
                        <div id="guarantors-container">
                            <!-- Guarantor Template - First Guarantor -->
                            <div class="guarantor-row border p-3 mb-3 rounded">
                                <div class="row">
                                    <div class="col-md-12 text-right mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-guarantor" style="display: none;">
                                            <i class="fas fa-trash"></i> Supprimer le Garant
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom Complet <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][name]"
                                                   placeholder="Entrez le nom complet du garant"
                                                   value="{{ old('guarantors.0.name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Numéro de Téléphone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][phone]"
                                                   placeholder="Entrez le numéro de téléphone"
                                                   value="{{ old('guarantors.0.phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email (Optionnel)</label>
                                            <input type="email" class="form-control"
                                                   name="guarantors[0][email]"
                                                   placeholder="Entrez l'adresse email"
                                                   value="{{ old('guarantors.0.email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Numéro d'Identité <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][id_number]"
                                                   placeholder="Entrez le numéro d'identité national"
                                                   value="{{ old('guarantors.0.id_number') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Profession</label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][occupation]"
                                                   placeholder="Entrez la profession"
                                                   value="{{ old('guarantors.0.occupation') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Adresse</label>
                                            <textarea class="form-control"
                                                      name="guarantors[0][address]"
                                                      rows="2" placeholder="Entrez l'adresse physique">{{ old('guarantors.0.address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Relation avec le Membre</label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][relationship]"
                                                   placeholder="ex: Frère, Ami, Partenaire d'affaires"
                                                   value="{{ old('guarantors.0.relationship') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary" id="add-guarantor">
                            <i class="fas fa-plus"></i> Ajouter un Autre Garant
                        </button>
                        <small class="text-muted d-block mt-2">Maximum 3 garants autorisés. Tous les garants doivent fournir une pièce d'identité et des coordonnées valides.</small>
                    </div>
                </div>

                <!-- Documents Section with 15MB Limit -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Documents Justificatifs</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Limite de taille de fichier :</strong> Maximum 15MB par fichier. Formats autorisés : PDF, JPG, JPEG, PNG
                        </div>

                        <div id="documents-container">
                            <!-- Document Template - First Document -->
                            <div class="document-row border p-3 mb-3 rounded">
                                <div class="row">
                                    <div class="col-md-12 text-right mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-document" style="display: none;">
                                            <i class="fas fa-trash"></i> Supprimer le Document
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Catégorie de Document <span class="text-danger">*</span></label>
                                            <select class="form-control" name="document_categories[0]" required>
                                                <option value="">Sélectionner la Catégorie</option>
                                                <option value="guarantor_id">Pièce d'identité du Garant</option>
                                                <option value="collateral_photo">Photo de la Garantie</option>
                                                <option value="collateral_document">Document de la Garantie</option>
                                                <option value="proof_of_income">Preuve de Revenu</option>
                                                <option value="bank_statement">Relevé Bancaire</option>
                                                <option value="application_form">Formulaire de Demande</option>
                                                <option value="other">Autre</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lier au Garant (Optionnel)</label>
                                            <select class="form-control" name="document_guarantor[0]">
                                                <option value="">Non lié à un garant</option>
                                                @for($i = 0; $i < 3; $i++)
                                                    <option value="guarantor_{{ $i }}">Garant {{ $i + 1 }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Télécharger le Fichier <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control-file"
                                                   name="documents[0]"
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   data-max-size="15728640" {{-- 15MB in bytes --}}
                                                   required>
                                            <small class="text-muted">Max 15MB</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description (Optionnel)</label>
                                            <input type="text" class="form-control"
                                                   name="document_descriptions[0]"
                                                   placeholder="Brève description du document"
                                                   value="{{ old('document_descriptions.0') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Actions & Hints -->
                        <div class="mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-sm btn-primary" id="add-document">
                                <i class="fas fa-plus"></i> Ajouter un Autre Document
                            </button>
                            <small class="text-muted d-block mt-2">Maximum 10 documents autorisés. Téléchargez des copies claires et lisibles.</small>

                            <!-- Document type hints -->
                            <div class="alert alert-light small p-2 mt-3 permanent-note">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong>Documents recommandés :</strong>
                                <ul class="mb-0 mt-1 pl-3">
                                    <li>Copies des pièces d'identité des garants (liées au garant spécifique)</li>
                                    <li>Photos/documents de la garantie</li>
                                    <li>Preuves de revenu (bulletins de paie, relevés bancaires)</li>
                                    <li>Relevés bancaires (3-6 derniers mois)</li>
                                    <li>Formulaire de demande complété</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="alert alert-info permanent-note">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note sur le Traitement du Prêt :</strong>
                    <ul class="mb-0 mt-2">
                        <li>Le taux d'intérêt, la durée et les paiements mensuels seront définis lors de l'approbation du prêt</li>
                        <li>Les garants seront contactés et vérifiés par le trésorier pendant le processus de vérification</li>
                        <li>Tous les documents téléchargés seront examinés lors de la vérification (15MB max par fichier)</li>
                        <li>La garantie sera inspectée et vérifiée par le trésorier assigné</li>
                        <li>Cette demande sera en attente jusqu'à ce qu'elle soit assignée à un trésorier pour vérification</li>
                    </ul>
                </div>
            </form>
        </div>

        <!-- Form Footer with Submit Buttons (Mobile only - hidden on desktop where sticky footer shows) -->
        <div class="card-footer bg-light border-top d-flex justify-content-end py-4 d-md-none">
            <div class="w-100 text-right">
                <button type="button" id="mobile-submit-btn" class="btn btn-primary">
                    <i class="fas fa-save"></i> Soumettre la Demande
                </button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary ml-2">Annuler</a>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Footer for Submit Button (Desktop only) -->
<div class="sticky-footer d-none d-md-block">
    <div class="container-fluid">
        <div class="row justify-content-end">
            <div class="col-auto">
                <button type="button" id="sticky-submit-btn" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save"></i> Soumettre la Demande
                </button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary btn-lg px-4 ml-2">
                    Annuler
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

    // ============================================
    // QR CODE / MANUAL MEMBER SELECTION HANDLERS
    // ============================================
    let html5QrCodeScanner = null;
    let isScanning = false;

    // QR Code Scanner Modal HTML
    const qrScannerModalHtml = `
        <div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="qrScannerModalLabel"><i class="fas fa-qrcode"></i> Scanner le QR Code du Membre</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopQrScanner(); $('#qrScannerModal').modal('hide');">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div id="qr-reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                        <div class="mt-3 text-center">
                            <div class="alert alert-info" id="qr-scanner-status" style="display: none;"></div>
                            <div class="alert alert-warning" id="https-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> <strong>HTTPS requis :</strong> La caméra nécessite une connexion sécurisée (HTTPS).
                                <br>Sur localhost, utilisez <a href="#" onclick="showManualInputModal(); return false;">la saisie manuelle</a> ou configurez HTTPS local.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="stopQrScanner(); $('#qrScannerModal').modal('hide');">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="showManualInputModal(); stopQrScanner();">
                            <i class="fas fa-keyboard"></i> Saisie Manuelle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Manual Input Modal HTML
    const manualInputModalHtml = `
        <div class="modal fade" id="manualInputModal" tabindex="-1" role="dialog" aria-labelledby="manualInputModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="manualInputModalLabel"><i class="fas fa-keyboard"></i> Saisie Manuelle du Code Membre</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" onclick="$('#manualInputModal').modal('hide');">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="form-group">
                            <label for="manual_qr_code">Numéro de Membre / Code QR</label>
                            <input type="text" class="form-control form-control-lg text-center" id="manual_qr_code"
                                   placeholder="Ex: MEM-2024-001" autocomplete="off" autofocus
                                   style="font-size: 1.25rem; letter-spacing: 0.1em;">
                            <small class="text-muted">Entrez le numéro de membre ou le code du QR code</small>
                        </div>
                        <div class="alert alert-info mt-3" id="manual-input-status" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#manualInputModal').modal('hide');">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="button" class="btn btn-primary" onclick="searchMemberByManualCode()">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Inject modals into DOM
    document.body.insertAdjacentHTML('beforeend', qrScannerModalHtml);
    document.body.insertAdjacentHTML('beforeend', manualInputModalHtml);

    // Check if we're on localhost (insecure context for camera)
    const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    const isSecureContext = window.isSecureContext || window.location.protocol === 'https:';

    // Open QR Scanner Modal
    document.getElementById('btn-qr-scanner')?.addEventListener('click', function() {
        $('#qrScannerModal').modal('show');
    });

    // Open Manual Input Modal
    document.getElementById('btn-manual-input')?.addEventListener('click', function() {
        showManualInputModal();
    });

    function showManualInputModal() {
        $('#manualInputModal').modal('show');
        setTimeout(() => {
            const input = document.getElementById('manual_qr_code');
            if (input) input.focus();
        }, 300);
    }

    // Check camera permission and HTTPS requirement - called when modal is fully shown
    function checkCameraPermission() {
        const statusDiv = document.getElementById('qr-scanner-status');
        const httpsWarning = document.getElementById('https-warning');
        const readerDiv = document.getElementById('qr-reader');

        // If elements don't exist, fail silently (modal not ready or not our modal)
        if (!statusDiv || !httpsWarning || !readerDiv) {
            console.debug('QR scanner modal elements not found');
            return;
        }

        if (!isSecureContext && isLocalhost) {
            // Show HTTPS warning
            httpsWarning.style.display = 'block';
            readerDiv.style.display = 'none';
            statusDiv.style.display = 'none';
            return;
        } else {
            httpsWarning.style.display = 'none';
            readerDiv.style.display = 'block';
        }

        // Try to start scanner
        startQrScanner();
    }

    // Initialize scanner when QR modal is fully shown
    $('#qrScannerModal').on('shown.bs.modal', function() {
        checkCameraPermission();
    });

    // Start QR Code Scanner using html5-qrcode library
    function startQrScanner() {
        if (isScanning) return;

        // Check if html5-qrcode is available
        if (typeof Html5QrcodeScanner === 'undefined') {
            // Load the library dynamically
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js';
            script.onload = () => initQrScanner();
            script.onerror = () => {
                const statusDiv = document.getElementById('qr-scanner-status');
                const readerDiv = document.getElementById('qr-reader');
                if (statusDiv) {
                    statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Erreur de chargement du scanner. Utilisez la saisie manuelle.';
                    statusDiv.style.display = 'block';
                }
                if (readerDiv) {
                    readerDiv.style.display = 'none';
                }
            };
            document.head.appendChild(script);
        } else {
            initQrScanner();
        }
    }

    function initQrScanner() {
        const readerDiv = document.getElementById('qr-reader');
        const statusDiv = document.getElementById('qr-scanner-status');

        if (!readerDiv) return;

        // Check camera permission before initializing
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    // Stop the test stream
                    stream.getTracks().forEach(track => track.stop());
                    initHtml5QrCodeScanner();
                })
                .catch(err => {
                    console.error('Camera access error:', err);
                    if (statusDiv) {
                        let msg = 'Impossible d\'accéder à la caméra. ';
                        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                            msg += 'Veuillez autoriser l\'accès à la caméra dans les paramètres du navigateur.';
                        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                            msg += 'Aucune caméra détectée sur cet appareil.';
                        } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                            msg += 'La caméra est déjà utilisée par une autre application.';
                        } else if (err.name === 'OverconstrainedError') {
                            msg += 'La caméra ne supporte pas la configuration requise.';
                        } else {
                            msg += 'Erreur : ' + err.message;
                        }
                        msg += ' <button type=\"button\" class=\"btn btn-sm btn-outline-primary ml-2\" onclick=\"showManualInputModal(); stopQrScanner();\"><i class=\"fas fa-keyboard\"></i> Saisie Manuelle</button>';
                        statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
                        statusDiv.className = 'alert alert-danger';
                        statusDiv.style.display = 'block';
                    }
                    if (readerDiv) {
                        readerDiv.style.display = 'none';
                    }
                });
        } else {
            // Fallback if getUserMedia not available
            initHtml5QrCodeScanner();
        }

        function initHtml5QrCodeScanner() {
            html5QrCodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true,
                    showZoomSliderIfSupported: true
                },
                false
            );

            html5QrCodeScanner.render(
                onQrScanSuccess,
                onQrScanFailure
            );

            isScanning = true;
            if (statusDiv) statusDiv.style.display = 'none';
            readerDiv.style.display = 'block';
        }
    }

    function onQrScanSuccess(decodedText, decodedResult) {
        // Stop scanner after successful scan
        stopQrScanner();

        // Search for member
        searchMemberByQrCode(decodedText);
    }

    function onQrScanFailure(error) {
        // Silently handle scan failures (continuous scanning)
        // Only show if it's a permission error
        if (error && error.name === 'NotAllowedError') {
            const statusDiv = document.getElementById('qr-scanner-status');
            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Accès caméra refusé. Veuillez autoriser l\'accès à la caméra ou utiliser la saisie manuelle.';
                statusDiv.className = 'alert alert-danger';
                statusDiv.style.display = 'block';
            }
            stopQrScanner();
        }
    }

    function stopQrScanner() {
        if (html5QrCodeScanner && isScanning) {
            html5QrCodeScanner.clear().catch(err => console.warn('QR Scanner clear error:', err));
            isScanning = false;
        }
        $('#qrScannerModal').modal('hide');
    }

    // Search member by QR code (AJAX)
    function searchMemberByQrCode(qrCode) {
        const statusDiv = document.getElementById('qr-scanner-status');
        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche du membre...';
            statusDiv.className = 'alert alert-info';
            statusDiv.style.display = 'block';
        }

        fetch('{{ route("admin.loans.find-member-qr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => {
            // Handle HTTP errors (404, 500, etc.) with user-friendly messages
            if (!response.ok) {
                let errorMsg = '';
                switch (response.status) {
                    case 404:
                        errorMsg = 'La route de recherche est introuvable ou le service est temporairement indisponible (404).';
                        break;
                    case 422:
                        errorMsg = 'Données invalides envoyées au serveur (422).';
                        break;
                    case 500:
                        errorMsg = 'Erreur interne du serveur (500). Veuillez réessayer plus tard.';
                        break;
                    case 419:
                        errorMsg = 'Session expirée. Veuillez rafraîchir la page.';
                        break;
                    default:
                        errorMsg = 'Erreur de communication avec le serveur (' + response.status + ').';
                }
                return response.json().catch(() => ({})).then(err => {
                    throw new Error(errorMsg + (err.message ? ' - ' + err.message : ''));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.member) {
                selectMember(data.member);
                $('#qrScannerModal').modal('hide');
                showToast('success', 'Membre trouvé : ' + data.member.name);
            } else {
                throw new Error(data.message || 'Membre non trouvé');
            }
        })
        .catch(error => {
            console.error('QR Search error:', error);
            let msg = error.message || 'Erreur lors de la recherche';

            // Don't show technical 404 message for "member not found" (that's expected)
            if (msg.includes('Aucun membre trouvé')) {
                msg = 'Aucun membre trouvé avec ce code. Vérifiez le numéro et réessayez.';
            }

            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
                statusDiv.className = 'alert alert-danger';
                statusDiv.style.display = 'block';
            }
            // Don't hide modal, let user retry or switch to manual
        });
    }

    // Search member by manual code input
    function searchMemberByManualCode() {
        const input = document.getElementById('manual_qr_code');
        const statusDiv = document.getElementById('manual-input-status');
        const qrCode = input?.value?.trim();

        if (!qrCode) {
            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Veuillez entrer un code membre';
                statusDiv.className = 'alert alert-warning';
                statusDiv.style.display = 'block';
            }
            input?.focus();
            return;
        }

        if (statusDiv) {
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche du membre...';
            statusDiv.className = 'alert alert-info';
            statusDiv.style.display = 'block';
        }

        fetch('{{ route("admin.loans.find-member-qr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => {
            // Handle HTTP errors (404, 500, etc.) with user-friendly messages
            if (!response.ok) {
                let errorMsg = '';
                switch (response.status) {
                    case 404:
                        errorMsg = 'La route de recherche est introuvable ou le service est temporairement indisponible (404).';
                        break;
                    case 422:
                        errorMsg = 'Données invalides envoyées au serveur (422).';
                        break;
                    case 500:
                        errorMsg = 'Erreur interne du serveur (500). Veuillez réessayer plus tard.';
                        break;
                    case 419:
                        errorMsg = 'Session expirée. Veuillez rafraîchir la page.';
                        break;
                    default:
                        errorMsg = 'Erreur de communication avec le serveur (' + response.status + ').';
                }
                return response.json().catch(() => ({})).then(err => {
                    throw new Error(errorMsg + (err.message ? ' - ' + err.message : ''));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.member) {
                selectMember(data.member);
                $('#manualInputModal').modal('hide');
                showToast('success', 'Membre trouvé : ' + data.member.name);
            } else {
                throw new Error(data.message || 'Membre non trouvé');
            }
        })
        .catch(error => {
            console.error('Manual Search error:', error);
            let msg = error.message || 'Erreur lors de la recherche';

            // Don't show technical 404 message for "member not found" (that's expected)
            if (msg.includes('Aucun membre trouvé')) {
                msg = 'Aucun membre trouvé avec ce code. Vérifiez le numéro et réessayez.';
            }

            if (statusDiv) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
                statusDiv.className = 'alert alert-danger';
                statusDiv.style.display = 'block';
            }
        });
    }

    // Select member in dropdown
    function selectMember(member) {
        const select = document.getElementById('user_id');
        if (!select) return;

        // Check if member already exists in options
        let option = select.querySelector(`option[value="${member.id}"]`);
        if (!option) {
            // Add new option
            option = document.createElement('option');
            option.value = member.id;
            option.textContent = `${member.name} (${member.membership_number || 'Aucun #'})`;
            select.appendChild(option);
        }
        select.value = member.id;
        select.dispatchEvent(new Event('change', { bubbles: true }));

        // Visual feedback
        select.classList.add('is-valid');
        select.classList.remove('is-invalid');
        setTimeout(() => select.classList.remove('is-valid'), 2000);
    }

    // Toast notification helper
    function showToast(type, message) {
        // Create toast if not exists
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        toast.style.cssText = 'min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        toast.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;
        toastContainer.appendChild(toast);

        // Auto remove after 4 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    // Clean up scanner when modals are hidden
    $('#qrScannerModal').on('hidden.bs.modal', function() {
        stopQrScanner();
    });

    $('#manualInputModal').on('hidden.bs.modal', function() {
        const input = document.getElementById('manual_qr_code');
        const statusDiv = document.getElementById('manual-input-status');
        if (input) input.value = '';
        if (statusDiv) {
            statusDiv.style.display = 'none';
            statusDiv.textContent = '';
        }
    });

    // Explicit cancel button handlers (fallback for data-bs-dismiss)
    document.querySelectorAll('#qrScannerModal .btn-secondary[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            stopQrScanner();
            $('#qrScannerModal').modal('hide');
        });
    });

    document.querySelectorAll('#manualInputModal .btn-secondary[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            $('#manualInputModal').modal('hide');
        });
    });

    // Allow Enter key in manual input
    document.getElementById('manual_qr_code')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchMemberByManualCode();
        }
    });

    // ============================================
    // TOGGLE COLLATERAL FIELDS (with null checks)
    // ============================================
    const hasCollateralEl = document.getElementById('has_collateral');
    const collateralFields = document.getElementById('collateral-fields');

    if (hasCollateralEl && collateralFields) {
        hasCollateralEl.addEventListener('change', function() {
            const isChecked = this.checked;
            collateralFields.style.display = isChecked ? 'block' : 'none';

            const collateralInputs = collateralFields.querySelectorAll('input, select, textarea');
            collateralInputs.forEach(function(input) {
                if (isChecked) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
        });

        // Initialize on load
        if (!hasCollateralEl.checked) {
            collateralFields.style.display = 'none';
        }
    }

    // ============================================
    // GUARANTOR MANAGEMENT (with null checks)
    // ============================================
    let guarantorCount = 1;
    const maxGuarantors = 3;
    const addGuarantorBtn = document.getElementById('add-guarantor');
    const guarantorsContainer = document.getElementById('guarantors-container');

    if (addGuarantorBtn && guarantorsContainer) {
        addGuarantorBtn.addEventListener('click', function() {
            if (guarantorCount >= maxGuarantors) {
                alert('Maximum de ' + maxGuarantors + ' garants autorisés');
                return;
            }

            const template = document.querySelector('.guarantor-row');
            if (!template) return;

            const newRow = template.cloneNode(true);

            // Update indices and clear values
            newRow.querySelectorAll('input, textarea, select').forEach(element => {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, `[${guarantorCount}]`));
                    if (element.tagName === 'SELECT') {
                        element.value = '';
                    } else {
                        element.value = '';
                    }
                }
            });

            // Show remove button
            const removeBtn = newRow.querySelector('.remove-guarantor');
            if (removeBtn) {
                removeBtn.style.display = 'inline-block';
                removeBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.guarantor-row').length > 1) {
                        newRow.remove();
                        guarantorCount--;
                    }
                });
            }

            guarantorsContainer.appendChild(newRow);
            guarantorCount++;
        });
    }

    // Initialize remove buttons for existing guarantor rows
    document.querySelectorAll('.remove-guarantor').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                this.closest('.guarantor-row').remove();
                guarantorCount--;
            }
        });
    });

    // ============================================
    // DOCUMENT MANAGEMENT (with null checks)
    // ============================================
    let documentCount = 1;
    const maxDocuments = 10;
    const addDocumentBtn = document.getElementById('add-document');
    const documentsContainer = document.getElementById('documents-container');

    if (addDocumentBtn && documentsContainer) {
        addDocumentBtn.addEventListener('click', function() {
            if (documentCount >= maxDocuments) {
                alert('Maximum de ' + maxDocuments + ' documents autorisés');
                return;
            }

            const template = document.querySelector('.document-row');
            if (!template) return;

            const newRow = template.cloneNode(true);

            // Update indices and clear values
            newRow.querySelectorAll('select, input').forEach(element => {
                const name = element.getAttribute('name');
                if (name) {
                    element.setAttribute('name', name.replace(/\[\d+\]/, `[${documentCount}]`));
                    if (element.tagName === 'INPUT' && element.type === 'file') {
                        element.value = '';
                    } else if (element.tagName === 'SELECT') {
                        element.value = '';
                    } else {
                        element.value = '';
                    }
                }
            });

            // Show remove button
            const removeBtn = newRow.querySelector('.remove-document');
            if (removeBtn) {
                removeBtn.style.display = 'inline-block';
                removeBtn.addEventListener('click', function() {
                    if (document.querySelectorAll('.document-row').length > 1) {
                        newRow.remove();
                        documentCount--;
                    }
                });
            }

            documentsContainer.appendChild(newRow);
            documentCount++;
        });
    }

    // Initialize remove buttons for existing document rows
    document.querySelectorAll('.remove-document').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.document-row').length > 1) {
                this.closest('.document-row').remove();
                documentCount--;
            }
        });
    });

    // ============================================
    // FILE SIZE VALIDATION (15MB limit)
    // ============================================
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.hasAttribute('data-max-size')) {
            const maxSize = parseInt(e.target.getAttribute('data-max-size'));
            if (e.target.files.length > 0) {
                const fileSize = e.target.files[0].size;
                if (fileSize > maxSize) {
                    alert('La taille du fichier dépasse la limite de 15MB. Veuillez choisir un fichier plus petit.');
                    e.target.value = '';
                    return;
                }

                // Show file name and size
                const fileRow = e.target.closest('.document-row');
                const sizeInMB = (fileSize / (1024 * 1024)).toFixed(2);
                const fileName = e.target.files[0].name;

                // Create or update file info display
                let infoDiv = fileRow?.querySelector('.file-info');
                if (!infoDiv) {
                    infoDiv = document.createElement('div');
                    infoDiv.className = 'file-info small text-muted mt-1';
                    const formGroup = e.target.closest('.form-group');
                    if (formGroup) formGroup.appendChild(infoDiv);
                }
                if (infoDiv) {
                    infoDiv.innerHTML = `<i class="fas fa-file"></i> ${fileName} (${sizeInMB} MB)`;
                }
            }
        }
    });

    // ============================================
    // LOAN CALCULATOR FUNCTION
    // ============================================
    let calculatedPeriodicPayment = 0; // Store for adjusted amount calculation

    const calculateLoanBtn = document.getElementById('calculateLoan');
    if (calculateLoanBtn) {
        calculateLoanBtn.addEventListener('click', function() {
            const amountEl = document.getElementById('amount_applied');
            const interestRateEl = document.getElementById('preview_interest_rate');
            const durationEl = document.getElementById('preview_duration');
            const frequencyEl = document.getElementById('preview_frequency');
            const amortizationMethodEl = document.getElementById('preview_amortization');

            // Null checks
            if (!amountEl || !interestRateEl || !durationEl || !frequencyEl || !amortizationMethodEl) {
                console.error('Missing calculator elements');
                return;
            }

            const amount = parseFloat(amountEl.value);
            const interestRate = parseFloat(interestRateEl.value);
            const duration = parseInt(durationEl.value);
            const frequency = frequencyEl.value;
            const amortizationMethod = amortizationMethodEl.value;

            if (!amount || amount <= 0) {
                alert('Veuillez d\'abord entrer un montant de prêt valide');
                amountEl.focus();
                return;
            }

            if (duration < 1) {
                alert('Veuillez entrer une durée valide');
                durationEl.focus();
                return;
            }

            // AUTO-CALCULATE INTEREST RATE for Daily Flat Rate
            let calculatedInterestRate = interestRate;
            let isAutoCalculatedRate = false;

            if (frequency === 'daily' && (amortizationMethod === 'flat_rate' || amortizationMethod === 'pato_ndogo')) {
                calculatedInterestRate = (duration / 30) * 10;
                interestRateEl.value = calculatedInterestRate.toFixed(2);
                isAutoCalculatedRate = true;
            }

            // Validate interest rate only if not auto-calculated
            if (!isAutoCalculatedRate && calculatedInterestRate <= 0) {
                alert('Veuillez entrer un taux d\'intérêt valide');
                interestRateEl.focus();
                return;
            }

            // Calculate based on frequency
            const annualRate = calculatedInterestRate / 100;
            let periodicRate, periods, firstPaymentDate;

            switch (frequency) {
                case 'daily':
                    periodicRate = annualRate / 365;
                    periods = duration;
                    firstPaymentDate = new Date();
                    firstPaymentDate.setDate(firstPaymentDate.getDate() + 1);
                    break;
                case 'weekly':
                    periodicRate = annualRate / 52;
                    periods = duration;
                    firstPaymentDate = new Date();
                    firstPaymentDate.setDate(firstPaymentDate.getDate() + 7);
                    break;
                case 'monthly':
                default:
                    periodicRate = annualRate / 12;
                    periods = duration;
                    firstPaymentDate = new Date();
                    firstPaymentDate.setMonth(firstPaymentDate.getMonth() + 1);
                    break;
            }

            let periodicPayment, totalInterest, totalPayment;
            let remainingBalance = amount;

            switch (amortizationMethod) {
                case 'flat_rate':
                case 'pato_ndogo':
                    if (frequency === 'daily') {
                        totalInterest = amount * annualRate;
                        totalPayment = amount + totalInterest;
                        periodicPayment = totalPayment / periods;
                    } else {
                        const periodicPrincipal = amount / periods;
                        const periodicInterest = amount * periodicRate;
                        periodicPayment = periodicPrincipal + periodicInterest;
                        totalInterest = periodicInterest * periods;
                        totalPayment = amount + totalInterest;
                    }
                    break;

                case 'constant_capital':
                    const fixedPrincipal = amount / periods;
                    periodicPayment = fixedPrincipal + (amount * periodicRate);
                    totalInterest = 0;
                    remainingBalance = amount;
                    for (let i = 1; i <= periods; i++) {
                        totalInterest += remainingBalance * periodicRate;
                        remainingBalance -= fixedPrincipal;
                    }
                    totalPayment = amount + totalInterest;
                    break;

                case 'french':
                default:
                    if (periodicRate === 0) {
                        periodicPayment = amount / periods;
                    } else {
                        periodicPayment = amount * periodicRate * Math.pow(1 + periodicRate, periods) / (Math.pow(1 + periodicRate, periods) - 1);
                    }
                    totalPayment = periodicPayment * periods;
                    totalInterest = totalPayment - amount;
                    break;
            }

            // Store calculated periodic payment for adjusted amount feature
            calculatedPeriodicPayment = periodicPayment;

            // Show/hide adjusted amount row for Pato Ndogo daily
            const adjustedRow = document.getElementById('adjustedAmountRow');
            if (adjustedRow) {
                if (amortizationMethod === 'pato_ndogo' && frequency === 'daily') {
                    adjustedRow.style.display = 'flex';
                    const adjustedAmountEl = document.getElementById('adjusted_daily_amount');
                    if (adjustedAmountEl) adjustedAmountEl.value = periodicPayment.toFixed(2);
                    calculateSurplus();
                } else {
                    adjustedRow.style.display = 'none';
                }
            }

            // Show/hide late penalty row for daily flat_rate/pato_ndogo
            const latePenaltyRow = document.getElementById('latePenaltyRow');
            if (latePenaltyRow) {
                if ((amortizationMethod === 'flat_rate' || amortizationMethod === 'pato_ndogo') && frequency === 'daily') {
                    latePenaltyRow.style.display = 'block';
                } else {
                    latePenaltyRow.style.display = 'none';
                }
            }

            // Update labels based on frequency
            const frequencyLabels = {
                'daily': { payment: 'Paiement Quotidien', period: 'jour' },
                'weekly': { payment: 'Paiement Hebdomadaire', period: 'semaine' },
                'monthly': { payment: 'Paiement Mensuel', period: 'mois' }
            };

            const labels = frequencyLabels[frequency] || frequencyLabels['monthly'];
            const paymentLabel = document.getElementById('paymentLabel');
            const paymentHeader = document.getElementById('paymentHeader');
            if (paymentLabel) paymentLabel.textContent = labels.payment;
            if (paymentHeader) paymentHeader.textContent = labels.payment;

            // Display summary with formatted numbers
            const monthlyPaymentEl = document.getElementById('monthlyPayment');
            const totalInterestEl = document.getElementById('totalInterest');
            const totalPaymentEl = document.getElementById('totalPayment');
            const loanSummaryEl = document.getElementById('loanSummary');

            if (monthlyPaymentEl) monthlyPaymentEl.textContent = 'CDF ' + Math.round(periodicPayment).toLocaleString();
            if (totalInterestEl) totalInterestEl.textContent = 'CDF ' + Math.round(totalInterest).toLocaleString();
            if (totalPaymentEl) totalPaymentEl.textContent = 'CDF ' + Math.round(totalPayment).toLocaleString();
            if (loanSummaryEl) loanSummaryEl.style.display = 'flex';

            // Sync preview calculator values to hidden loan fields
            const repaymentFrequencyEl = document.getElementById('repayment_frequency');
            const durationValueEl = document.getElementById('duration_value');
            const amortizationMethodHiddenEl = document.getElementById('amortization_method');
            const interestRateHiddenEl = document.getElementById('interest_rate');

            if (repaymentFrequencyEl) repaymentFrequencyEl.value = frequency;
            if (durationValueEl) durationValueEl.value = duration;
            if (amortizationMethodHiddenEl) amortizationMethodHiddenEl.value = amortizationMethod;
            if (interestRateHiddenEl) interestRateHiddenEl.value = calculatedInterestRate;

            // Generate amortization schedule
            remainingBalance = amount;
            let schedule = '';
            let paymentDate = new Date(firstPaymentDate);

            for (let i = 1; i <= periods; i++) {
                let principalPayment, interestPayment;

                switch (amortizationMethod) {
                    case 'flat_rate':
                case 'pato_ndogo':
                        if (frequency === 'daily') {
                            principalPayment = amount / periods;
                            interestPayment = totalInterest / periods;
                        } else {
                            principalPayment = amount / periods;
                            interestPayment = amount * periodicRate;
                        }
                        remainingBalance -= principalPayment;
                        break;

                    case 'constant_capital':
                        principalPayment = amount / periods;
                        interestPayment = remainingBalance * periodicRate;
                        remainingBalance -= principalPayment;
                        break;

                    case 'french':
                    default:
                        interestPayment = remainingBalance * periodicRate;
                        principalPayment = periodicPayment - interestPayment;
                        remainingBalance -= principalPayment;
                        break;
                }

                // Format date based on frequency
                let dateStr;
                const currentDate = new Date(paymentDate);
                if (frequency === 'daily') {
                    dateStr = currentDate.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
                } else if (frequency === 'weekly') {
                    dateStr = 'Sem ' + getWeekNumber(currentDate) + ' ' + currentDate.getFullYear();
                } else {
                    dateStr = currentDate.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
                }

                schedule += `<tr>
                    <td>${i}</td>
                    <td>${dateStr}</td>
                    <td>CDF ${Math.round(periodicPayment).toLocaleString()}</td>
                    <td>CDF ${Math.round(principalPayment).toLocaleString()}</td>
                    <td>CDF ${Math.round(interestPayment).toLocaleString()}</td>
                    <td>CDF ${Math.max(0, Math.round(remainingBalance)).toLocaleString()}</td>
                </tr>`;

                // Increment date for next period
                if (frequency === 'daily') {
                    paymentDate.setDate(paymentDate.getDate() + 1);
                } else if (frequency === 'weekly') {
                    paymentDate.setDate(paymentDate.getDate() + 7);
                } else {
                    paymentDate.setMonth(paymentDate.getMonth() + 1);
                }
            }

            const amortizationBody = document.getElementById('amortizationBody');
            const amortizationTable = document.getElementById('amortizationTable');
            if (amortizationBody) amortizationBody.innerHTML = schedule;
            if (amortizationTable) amortizationTable.style.display = 'block';
        });
    }

    // ============================================
    // SURPLUS/SHORTFALL CALCULATION
    // ============================================
    function calculateSurplus() {
        const theoreticalAmount = calculatedPeriodicPayment;
        const adjustedAmountEl = document.getElementById('adjusted_daily_amount');
        const adjustedAmount = adjustedAmountEl ? parseFloat(adjustedAmountEl.value) || 0 : 0;
        const surplusInput = document.getElementById('surplus_amount');
        const surplusDesc = document.getElementById('surplus_description');

        if (!surplusInput || !surplusDesc) return;

        if (adjustedAmount > 0) {
            const durationEl = document.getElementById('preview_duration');
            const duration = durationEl ? parseInt(durationEl.value) || 1 : 1;
            const difference = (adjustedAmount - theoreticalAmount) * duration;
            const absDiff = Math.abs(difference);

            if (difference > 0) {
                surplusInput.value = 'CDF ' + absDiff.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (Surplus total)';
                surplusInput.className = 'form-control text-success';
                surplusDesc.textContent = 'Surplus total à rembourser au membre ou transférer à l\'épargne à la clôture';
                surplusDesc.className = 'text-success';
                const surplusTypeEl = document.getElementById('surplus_type');
                if (surplusTypeEl) surplusTypeEl.value = 'surplus';
            } else if (difference < 0) {
                surplusInput.value = 'CDF ' + absDiff.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (Manquant total)';
                surplusInput.className = 'form-control text-danger';
                surplusDesc.textContent = 'Manquant total à collecter du membre à la clôture';
                surplusDesc.className = 'text-danger';
                const surplusTypeEl = document.getElementById('surplus_type');
                if (surplusTypeEl) surplusTypeEl.value = 'shortfall';
            } else {
                surplusInput.value = 'CDF 0 (Équilibré)';
                surplusInput.className = 'form-control text-info';
                surplusDesc.textContent = 'Montant ajusté équivaut au montant théorique';
                surplusDesc.className = 'text-info';
                const surplusTypeEl = document.getElementById('surplus_type');
                if (surplusTypeEl) surplusTypeEl.value = 'none';
            }

            // Populate hidden fields for form submission
            const theoreticalDailyEl = document.getElementById('theoretical_daily_amount');
            const totalSurplusEl = document.getElementById('total_surplus_amount');
            if (theoreticalDailyEl) theoreticalDailyEl.value = theoreticalAmount.toFixed(2);
            if (totalSurplusEl) totalSurplusEl.value = absDiff.toFixed(2);
        } else {
            surplusInput.value = '';
            surplusDesc.textContent = '';
            const surplusTypeEl = document.getElementById('surplus_type');
            const theoreticalDailyEl = document.getElementById('theoretical_daily_amount');
            const totalSurplusEl = document.getElementById('total_surplus_amount');
            if (surplusTypeEl) surplusTypeEl.value = '';
            if (theoreticalDailyEl) theoreticalDailyEl.value = '';
            if (totalSurplusEl) totalSurplusEl.value = '';
        }
    }

    // Event listener for adjusted amount input
    const adjustedDailyAmountEl = document.getElementById('adjusted_daily_amount');
    if (adjustedDailyAmountEl) {
        adjustedDailyAmountEl.addEventListener('input', calculateSurplus);
    }

    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    function getWeekNumber(date) {
        if (!date) return 1;
        const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
        const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    }

    // ============================================
    // FORM SUBMISSION HANDLERS
    // ============================================
    const stickySubmitBtn = document.getElementById('sticky-submit-btn');
    if (stickySubmitBtn) {
        stickySubmitBtn.addEventListener('click', function() {
            const form = document.getElementById('loan-form');
            if (form) {
                if (form.checkValidity()) {
                    form.submit();
                } else {
                    form.reportValidity();
                }
            }
        });
    }

    const mobileSubmitBtn = document.getElementById('mobile-submit-btn');
    if (mobileSubmitBtn) {
        mobileSubmitBtn.addEventListener('click', function() {
            const form = document.getElementById('loan-form');
            if (form) {
                if (form.checkValidity()) {
                    form.submit();
                } else {
                    form.reportValidity();
                }
            }
        });
    }

    // ============================================
    // CLEANUP ON PAGE UNLOAD
    // ============================================
    window.addEventListener('beforeunload', function() {
        stopQrScanner();
    });

</script>
@endpush