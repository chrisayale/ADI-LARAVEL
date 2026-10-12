@extends('layouts.app')

@section('title', 'Gestion du compte officiel - {{ $sacco->name }}')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion du compte officiel</h1>
        <a href="{{ route('admin.sacos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste des SACCOs
        </a>
        <a href="{{ route('admin.sacos.show', $sacco->id) }}" class="btn btn-primary">
            <i class="fas fa-eye"></i> Voir le SACCO
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Compte officiel du SACCO: {{ $sacco->name }}</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php
                        // Find the official user account for this SACCO
                        // First search by sacco_id
                        $officialUser = \App\Models\User::where('sacco_id', $sacco->id)->first();

                        // If not found, search by email
                        if (!$officialUser) {
                            $officialUser = \App\Models\User::where('email', $sacco->official_email)->first();
                        }
                    ?>

                    @if(!$officialUser)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucun compte officiel trouvé pour ce SACCO. Un compte sera créé lors de la première mise à jour.
                        </div>
                    @endif

                    <form action="{{ route('admin.sacos.official-account.update', $sacco->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Account Information Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="accountName" class="form-label">Nom du compte officiel</label>
                                    <input type="text" class="form-control" id="accountName" value="Compte Officiel - {{ $sacco->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="accountEmail" class="form-label">E-mail officiel (@adi.com)</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="accountEmail" name="email" value="{{ old('email', $officialUser ? $officialUser->email : $sacco->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        L'adresse e-mail doit se terminer par @adi.com et être unique parmi tous les comptes utilisateurs.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Statut du compte</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" id="accountStatus" name="status" value="active" {{ (old('status', $officialUser ? $officialUser->status : 'active')) === 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="accountStatus">
                                            <span class="text-active">{{ $officialUser && $officialUser->status === 'active' ? 'Actif' : 'Inactif' }}</span>
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Basculer pour activer ou désactiver le compte officiel</small>
                                </div>
                            </div>

                            <!-- Password Management Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Réinitialisation du mot de passe</label>
                                    <div>
                                        <button type="button" class="btn btn-outline-warning me-2" id="resetDefaultPasswordBtn">
                                            Réinitialiser au mot de passe par défaut (avec1234)
                                        </button>
                                        <small class="text-muted">Mot de passe par défaut: avec1234</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour conserver le mot de passe actuel)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Account Metadata -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Métadonnées du compte</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Date de création:</strong>
                                        <span>{{ $officialUser ? $officialUser->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Dernière connexion:</strong>
                                        <span>{{ $officialUser && $officialUser->last_login_at ? $officialUser->last_login_at->format('d/m/Y H:i') : 'Jamais connecté' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>ID du compte:</strong>
                                        <span>{{ $officialUser ? $officialUser->id : 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>SACCO associé:</strong>
                                        <span>{{ $sacco->name }} (ID: {{ $sacco->id }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('admin.sacos.index') }}" class="btn btn-secondary ms-2">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetDefaultPasswordBtn = document.getElementById('resetDefaultPasswordBtn');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');

        if (resetDefaultPasswordBtn) {
            resetDefaultPasswordBtn.addEventListener('click', function() {
                passwordInput.value = 'avec1234';
                passwordConfirmationInput.value = 'avec1234';

                // Show visual feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check me-1"></i> Mot de passe défini!';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            });
        }
    });
</script>
@endpush