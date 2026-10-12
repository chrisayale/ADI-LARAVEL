@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="title">Modifier le SACCO</h2>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('super-admin.sacos.update', $sacco->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du SACCO *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sacco->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Code *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $sacco->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="registration_number" class="form-label">Numéro d'enregistrement</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" id="registration_number" name="registration_number" value="{{ old('registration_number', $sacco->registration_number) }}">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $sacco->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $sacco->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $sacco->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="primary_currency" class="form-label">Devise primaire *</label>
                            <select class="form-select @error('primary_currency') is-invalid @enderror" id="primary_currency" name="primary_currency" required>
                                <option value="">Sélectionnez une devise</option>
                                <option value="USD" {{ (old('primary_currency', $sacco->primary_currency)) === 'USD' ? 'selected' : '' }}>USD (Dollar américain)</option>
                                <option value="EUR" {{ (old('primary_currency', $sacco->primary_currency)) === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="CDF" {{ (old('primary_currency', $sacco->primary_currency)) === 'CDF' ? 'selected' : '' }}>CDF (Franc congolais)</option>
                                <option value="KES" {{ (old('primary_currency', $sacco->primary_currency)) === 'KES' ? 'selected' : '' }}>KES (Shilling kényan)</option>
                                <option value="UGX" {{ (old('primary_currency', $sacco->primary_currency)) === 'UGX' ? 'selected' : '' }}>UGX (Shilling ougandais)</option>
                                <option value="TZS" {{ (old('primary_currency', $sacco->primary_currency)) === 'TZS' ? 'selected' : '' }}>TZS (Shilling tanzanien)</option>
                            </select>
                            @error('primary_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="secondary_currency" class="form-label">Devise secondaire *</label>
                            <select class="form-select @error('secondary_currency') is-invalid @enderror" id="secondary_currency" name="secondary_currency" required>
                                <option value="">Sélectionnez une devise</option>
                                <option value="USD" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'USD' ? 'selected' : '' }}>USD (Dollar américain)</option>
                                <option value="EUR" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="CDF" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'CDF' ? 'selected' : '' }}>CDF (Franc congolais)</option>
                                <option value="KES" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'KES' ? 'selected' : '' }}>KES (Shilling kényan)</option>
                                <option value="UGX" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'UGX' ? 'selected' : '' }}>UGX (Shilling ougandais)</option>
                                <option value="TZS" {{ (old('secondary_currency', $sacco->secondary_currency)) === 'TZS' ? 'selected' : '' }}>TZS (Shilling tanzanien)</option>
                            </select>
                            @error('secondary_currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Statut *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Sélectionnez un statut</option>
                                <option value="active" {{ (old('status', $sacco->status)) === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ (old('status', $sacco->status)) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspended" {{ (old('status', $sacco->status)) === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback>{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="settings" class="form-label">Paramètres (JSON)</label>
                            <textarea class="form-control @error('settings') is-invalid @enderror" id="settings" name="settings" rows="4">{{ old('settings', $sacco->settings) }}</textarea>
                            <small class="form-text text-muted">Entrez les paramètres au format JSON, par exemple: {"interest_rate": 0.05, "loan_term_months": 12, "min_share_amount": 10000}</small>
                            @error('settings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('super-admin.sacos.index') }}" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour le SACCO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection