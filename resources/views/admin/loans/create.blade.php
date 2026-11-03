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

                <!-- Member Selection -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Sélectionner un Membre <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id" required>
                                <option value="">Choisir un membre...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ $member->membership_number ?? 'Aucun #' }})
                                    </option>
                                @endforeach
                            </select>
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
                                        <option value="monthly" selected>Mensuel</option>
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="daily">Quotidien</option>
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
                                <input type="checkbox" class="custom-control-input" id="has_collateral" name="has_collateral" value="1" {{ old('has_collateral') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="has_collateral">
                                    <strong>A une Garantie</strong>
                                </label>
                            </div>
                        </div>

                        <div id="collateral-fields" style="{{ old('has_collateral') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_type">Type de Garantie</label>
                                        <select class="form-control @error('collateral_type') is-invalid @enderror"
                                                id="collateral_type" name="collateral_type">
                                            <option value="">Sélectionner le type</option>
                                            <option value="land" {{ old('collateral_type') == 'land' ? 'selected' : '' }}>Terrain/Propriété</option>
                                            <option value="vehicle" {{ old('collateral_type') == 'vehicle' ? 'selected' : '' }}>Véhicule</option>
                                            <option value="building" {{ old('collateral_type') == 'building' ? 'selected' : '' }}>Bâtiment</option>
                                            <option value="equipment" {{ old('collateral_type') == 'equipment' ? 'selected' : '' }}>Équipement</option>
                                            <option value="savings" {{ old('collateral_type') == 'savings' ? 'selected' : '' }}>Épargne</option>
                                            <option value="other" {{ old('collateral_type') == 'other' ? 'selected' : '' }}>Autre</option>
                                        </select>
                                </div>
                            </div>
                                        @error('collateral_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_value">Valeur Estimée (CDF)</label>
                                        <input type="number" step="0.01" class="form-control @error('collateral_value') is-invalid @enderror"
                                               id="collateral_value" name="collateral_value" value="{{ old('collateral_value') }}">
                                        @error('collateral_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="collateral_description">Description de la Garantie</label>
                                        <textarea class="form-control @error('collateral_description') is-invalid @enderror"
                                                  id="collateral_description" name="collateral_description" rows="2" placeholder="Fournir une description détaillée de la garantie">{{ old('collateral_description') }}</textarea>
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
                            <div class="alert alert-light small p-2 mt-3">
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
                <div class="alert alert-info">
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

        <!-- Form Footer with Submit Buttons -->
        <div class="card-footer bg-light border-top d-flex justify-content-end py-4">
            <div class="d-md-none w-100 text-right mb-3">
                <button type="submit" form="loan-form" class="btn btn-primary">
                    <i class="fas fa-save"></i> Soumettre la Demande
                </button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary ml-2">Annuler</a>
            </div>
            <div class="d-none d-md-flex">
                <button type="submit" form="loan-form" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save"></i> Soumettre la Demande
                </button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary btn-lg px-4 ml-2">
                    Annuler
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Footer for Submit Button (Desktop only) -->
<div class="sticky-footer d-none d-md-block">
    <div class="container-fluid">
        <div class="row justify-content-end">
            <div class="col-auto">
                <button type="submit" form="loan-form" class="btn btn-primary btn-lg px-5">
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

    // Toggle collateral fields
    document.getElementById('has_collateral').addEventListener('change', function() {
        document.getElementById('collateral-fields').style.display = this.checked ? 'block' : 'none';
    });

    // Guarantor management
    let guarantorCount = 1;
    const maxGuarantors = 3;

    document.getElementById('add-guarantor').addEventListener('click', function() {
        if (guarantorCount >= maxGuarantors) {
            alert('Maximum de ' + maxGuarantors + ' garants autorisés');
            return;
        }

        const container = document.getElementById('guarantors-container');
        const template = document.querySelector('.guarantor-row').cloneNode(true);

        // Update indices and clear values
        template.querySelectorAll('input, textarea, select').forEach(element => {
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
        const removeBtn = template.querySelector('.remove-guarantor');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                template.remove();
                guarantorCount--;
            }
        });

        container.appendChild(template);
        guarantorCount++;
    });

    // Initialize remove buttons for existing rows
    document.querySelectorAll('.remove-guarantor').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                this.closest('.guarantor-row').remove();
                guarantorCount--;
            }
        });
    });

    // Document management
    let documentCount = 1;
    const maxDocuments = 10;

    document.getElementById('add-document').addEventListener('click', function() {
        if (documentCount >= maxDocuments) {
            alert('Maximum de ' + maxDocuments + ' documents autorisés');
            return;
        }

        const container = document.getElementById('documents-container');
        const template = document.querySelector('.document-row').cloneNode(true);

        // Update indices and clear values
        template.querySelectorAll('select, input').forEach(element => {
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
        const removeBtn = template.querySelector('.remove-document');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.document-row').length > 1) {
                template.remove();
                documentCount--;
            }
        });

        container.appendChild(template);
        documentCount++;
    });

    // Initialize remove buttons for existing documents
    document.querySelectorAll('.remove-document').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.document-row').length > 1) {
                this.closest('.document-row').remove();
                documentCount--;
            }
        });
    });

    // File size validation (15MB limit)
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.hasAttribute('data-max-size')) {
            const maxSize = parseInt(e.target.getAttribute('data-max-size'));
            if (e.target.files.length > 0) {
                const fileSize = e.target.files[0].size;
                if (fileSize > maxSize) {
                    alert('La taille du fichier dépasse la limite de 15MB. Veuillez choisir un fichier plus petit.');
                    e.target.value = '';
                }

                // Show file name and size
                const fileRow = e.target.closest('.document-row');
                const sizeInMB = (fileSize / (1024 * 1024)).toFixed(2);
                const fileName = e.target.files[0].name;

                // Create or update file info display
                let infoDiv = fileRow.querySelector('.file-info');
                if (!infoDiv) {
                    infoDiv = document.createElement('div');
                    infoDiv.className = 'file-info small text-muted mt-1';
                    e.target.closest('.form-group').appendChild(infoDiv);
                }
                infoDiv.innerHTML = `<i class="fas fa-file"></i> ${fileName} (${sizeInMB} MB)`;
            }
        }
    });

    // Loan Calculator Function with real-time validation
    document.getElementById('calculateLoan').addEventListener('click', function() {
        const amount = parseFloat(document.getElementById('amount_applied').value);
        const interestRate = parseFloat(document.getElementById('preview_interest_rate').value);
        const duration = parseInt(document.getElementById('preview_duration').value);
        const frequency = document.getElementById('preview_frequency').value;
        const amortizationMethod = document.getElementById('preview_amortization').value;

        if (!amount || amount <= 0) {
            alert('Veuillez d\'abord entrer un montant de prêt valide');
            document.getElementById('amount_applied').focus();
            return;
        }

        if (duration < 1) {
            alert('Veuillez entrer une durée valide');
            return;
        }

        // AUTO-CALCULATE INTEREST RATE for Daily Flat Rate
        // When frequency is daily AND amortization is flat_rate: Taux (%) = (Durée en jours / 30) * 10%
        let calculatedInterestRate = interestRate;
        let isAutoCalculatedRate = false;

        if (frequency === 'daily' && (amortizationMethod === 'flat_rate' || amortizationMethod === 'pato_ndogo')) {
            calculatedInterestRate = (duration / 30) * 10;
            // Update the UI field
            document.getElementById('preview_interest_rate').value = calculatedInterestRate.toFixed(2);
            isAutoCalculatedRate = true;
        }

        // Validate interest rate only if not auto-calculated
        if (!isAutoCalculatedRate && calculatedInterestRate <= 0) {
            alert('Veuillez entrer un taux d\'intérêt valide');
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
                    // DAILY FLAT RATE: Special calculation per requirements
                    // Intérêt Total = Capital × Taux (%)
                    // Montant Total = Capital + Intérêt Total
                    // Paiement Journalier = Montant Total / Nombre de Jours
                    // Part Capital journalier = Capital / Nombre de jours
                    // Part Intérêt journalier = Intérêt Total / Nombre de jours
                    totalInterest = amount * annualRate; // annualRate here is the full rate (e.g., 0.10 for 10%)
                    totalPayment = amount + totalInterest;
                    periodicPayment = totalPayment / periods;
                } else {
                    // Regular flat rate (weekly/monthly)
                    const periodicPrincipal = amount / periods;
                    const periodicInterest = amount * periodicRate;
                    periodicPayment = periodicPrincipal + periodicInterest;
                    totalInterest = periodicInterest * periods;
                    totalPayment = amount + totalInterest;
                }
                break;

            case 'constant_capital':
                // Constant Capital: Fixed principal, declining interest
                const fixedPrincipal = amount / periods;
                periodicPayment = fixedPrincipal + (amount * periodicRate); // First payment
                // Calculate total interest
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
                // French (Standard amortization): Constant payment
                if (periodicRate === 0) {
                    periodicPayment = amount / periods;
                } else {
                    periodicPayment = amount * periodicRate * Math.pow(1 + periodicRate, periods) / (Math.pow(1 + periodicRate, periods) - 1);
                }
                totalPayment = periodicPayment * periods;
                totalInterest = totalPayment - amount;
                break;
        }

        // Update labels based on frequency
        const frequencyLabels = {
            'daily': { payment: 'Paiement Quotidien', period: 'jour' },
            'weekly': { payment: 'Paiement Hebdomadaire', period: 'semaine' },
            'monthly': { payment: 'Paiement Mensuel', period: 'mois' }
        };

        const labels = frequencyLabels[frequency] || frequencyLabels['monthly'];
        document.getElementById('paymentLabel').textContent = labels.payment;
        document.getElementById('paymentHeader').textContent = labels.payment;

        // Display summary with formatted numbers
        document.getElementById('monthlyPayment').textContent = 'CDF ' + Math.round(periodicPayment).toLocaleString();
        document.getElementById('totalInterest').textContent = 'CDF ' + Math.round(totalInterest).toLocaleString();
        document.getElementById('totalPayment').textContent = 'CDF ' + Math.round(totalPayment).toLocaleString();

        document.getElementById('loanSummary').style.display = 'flex';

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
                        // DAILY FLAT RATE: Equal daily payments with equal principal and interest portions
                        principalPayment = amount / periods;
                        interestPayment = totalInterest / periods;
                    } else {
                        principalPayment = amount / periods;
                        interestPayment = amount * periodicRate;
                    }
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

        document.getElementById('amortizationBody').innerHTML = schedule;
        document.getElementById('amortizationTable').style.display = 'block';
    });

    // Helper function to get week number
    function getWeekNumber(date) {
        const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
        const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    }

    // Optional: Auto-calculate when amount changes (if needed)
    document.getElementById('amount_applied').addEventListener('change', function() {
        if (this.value > 0) {
            // You could auto-trigger calculation here if desired
        }
    });
</script>
@endpush