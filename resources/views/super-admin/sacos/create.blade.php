@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="title">Nouveau SACCO</h2>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('super-admin.sacos.store') }}" method="POST">
                        @csrf

                        <!-- Informations sur le SACCO -->
                        <div class="mb-4">
                            <h6 class="text-muted">Informations sur le SACCO</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom du SACCO *</label>
                                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Numéro Mobile Money officiel du SACCO *
                                        <span class="text-muted fw-normal">(Numéro où l'argent des membres sera déposé)</span>
                                    </label>
                                    <input type="tel" name="phone_number" class="form-control" required value="{{ old('phone_number') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email officiel du SACCO (optionnel)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Localisation du SACCO -->
                        <div class="mb-4">
                            <h6 class="text-muted">Localisation du SACCO *</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Province *</label>
                                    <select name="province" class="form-select" required>
                                        <option value="">-- Sélectionnez une province --</option>
                                        <option value="Kinshasa" {{ old('province') == 'Kinshasa' ? 'selected' : '' }}>Kinshasa</option>
                                        <option value="Kongo Central" {{ old('province') == 'Kongo Central' ? 'selected' : '' }}>Kongo Central</option>
                                        <option value="Kwango" {{ old('province') == 'Kwango' ? 'selected' : '' }}>Kwango</option>
                                        <option value="Kwilu" {{ old('province') == 'Kwilu' ? 'selected' : '' }}>Kwilu</option>
                                        <option value="Kasai" {{ old('province') == 'Kasai' ? 'selected' : '' }}>Kasai</option>
                                        <option value="Kasai Central" {{ old('province') == 'Kasai Central' ? 'selected' : '' }}>Kasai Central</option>
                                        <option value="Kasai Oriental" {{ old('province') == 'Kasai Oriental' ? 'selected' : '' }}>Kasai Oriental</option>
                                        <option value="Lomami" {{ old('province') == 'Lomami' ? 'selected' : '' }}>Lomami</option>
                                        <option value="Sankuru" {{ old('province') == 'Sankuru' ? 'selected' : '' }}>Sankuru</option>
                                        <option value="Maniema" {{ old('province') == 'Maniema' ? 'selected' : '' }}>Maniema</option>
                                        <option value="Sud-Kivu" {{ old('province') == 'Sud-Kivu' ? 'selected' : '' }}>Sud-Kivu</option>
                                        <option value="Nord-Kivu" {{ old('province') == 'Nord-Kivu' ? 'selected' : '' }}>Nord-Kivu</option>
                                        <option value="Ituri" {{ old('province') == 'Ituri' ? 'selected' : '' }}>Ituri</option>
                                        <option value="Haut-Uele" {{ old('province') == 'Haut-Uele' ? 'selected' : '' }}>Haut-Uele</option>
                                        <option value="Bas-Uele" {{ old('province') == 'Bas-Uele' ? 'selected' : '' }}>Bas-Uele</option>
                                        <option value="Nord-Ubangi" {{ old('province') == 'Nord-Ubangi' ? 'selected' : '' }}>Nord-Ubangi</option>
                                        <option value="Sud-Ubangi" {{ old('province') == 'Sud-Ubangi' ? 'selected' : '' }}>Sud-Ubangi</option>
                                        <option value="Équateur" {{ old('province') == 'Équateur' ? 'selected' : '' }}>Équateur</option>
                                        <option value="Mongala" {{ old('province') == 'Mongala' ? 'selected' : '' }}>Mongala</option>
                                        <option value="Tshuapa" {{ old('province') == 'Tshuapa' ? 'selected' : '' }}>Tshuapa</option>
                                        <option value="Tshopo" {{ old('province') == 'Tshopo' ? 'selected' : '' }}>Tshopo</option>
                                        <option value="Haut-Lomami" {{ old('province') == 'Haut-Lomami' ? 'selected' : '' }}>Haut-Lomami</option>
                                        <option value="Lualaba" {{ old('province') == 'Lualaba' ? 'selected' : '' }}>Lualaba</option>
                                        <option value="Haut-Katanga" {{ old('province') == 'Haut-Katanga' ? 'selected' : '' }}>Haut-Katanga</option>
                                        <option value="Tanganyika" {{ old('province') == 'Tanganyika' ? 'selected' : '' }}>Tanganyika</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Territoire / Commune *</label>
                                    <input type="text" name="territory" class="form-control" required value="{{ old('territory') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ville / Village / Quartier *</label>
                                    <input type="text" name="city_village" class="form-control" required value="{{ old('city_village') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse physique détaillée (facultatif)</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Informations du demandeur -->
                        <div class="mb-4">
                            <h6 class="text-muted">Informations du demandeur *</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom complet du demandeur *</label>
                                    <input type="text" name="applicant_name" class="form-control" required value="{{ old('applicant_name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fonction / Rôle dans le SACCO *</label>
                                    <input type="text" name="applicant_role" class="form-control" required value="{{ old('applicant_role') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email de contact du demandeur (optionnel)</label>
                                    <input type="email" name="applicant_email" class="form-control" value="{{ old('applicant_email') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone de contact du demandeur *</label>
                                    <input type="tel" name="applicant_phone" class="form-control" required value="{{ old('applicant_phone') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Motif / Description -->
                        <div class="mb-4">
                            <h6 class="text-muted">Motif / Description</h6>
                            <textarea name="description" class="form-control" rows="4" placeholder="Décrivez brièvement votre projet de SACCO...">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Créer le SACCO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection