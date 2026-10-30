@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="title">Détails du SACCO</h2>

                    @if (auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('super-admin.sacos.edit', $sacco->id) }}" class="btn btn-warning btn-sm float-end">
                            Modifier
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <dl class="row">
                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9">{{ $sacco->id }}</dd>

                        <dt class="col-sm-3">Nom</dt>
                        <dd class="col-sm-9">{{ $sacco->name }}</dd>

                        <dt class="col-sm-3">Code</dt>
                        <dd class="col-sm-9">{{ $sacco->code }}</dd>

                        <dt class="col-sm-3">Numéro d'enregistrement</dt>
                        <dd class="col-sm-9">{{ $sacco->registration_number ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Téléphone</dt>
                        <dd class="col-sm-9">{{ $sacco->phone ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $sacco->email ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Adresse</dt>
                        <dd class="col-sm-9">{{ $sacco->address ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Devise primaire</dt>
                        <dd class="col-sm-9">
                            @php
                                $currencies = [
                                    'USD' => 'Dollar américain (USD)',
                                    'EUR' => 'Euro (EUR)',
                                    'CDF' => 'Franc congolais (CDF)',
                                    'KES' => 'Shilling kényan (KES)',
                                    'CDF' => 'Shilling ougandais (CDF)',
                                    'TZS' => 'Shilling tanzanien (TZS)',
                                ];
                                echo $currencies[$sacco->primary_currency] ?? $sacco->primary_currency;
                            @endphp
                        </dd>

                        <dt class="col-sm-3">Devise secondaire</dt>
                        <dd class="col-sm-9">
                            @php
                                $currencies = [
                                    'USD' => 'Dollar américain (USD)',
                                    'EUR' => 'Euro (EUR)',
                                    'CDF' => 'Franc congolais (CDF)',
                                    'KES' => 'Shilling kényan (KES)',
                                    'CDF' => 'Shilling ougandais (CDF)',
                                    'TZS' => 'Shilling tanzanien (TZS)',
                                ];
                                echo $currencies[$sacco->secondary_currency] ?? $sacco->secondary_currency;
                            @endphp
                        </dd>

                        <dt class="col-sm-3">Statut</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $sacco->status === 'active' ? 'success' : ($sacco->status === 'inactive' ? 'secondary' : 'warning') }}">
                                {{ translate_status($sacco->status) }}
                            </span>
                        </dd>

                        @if (!empty($sacco->settings))
                            <dt class="col-sm-3">Paramètres</dt>
                            <dd class="col-sm-9">
                                <pre class="bg-light p-3 rounded">{{ json_encode($sacco->settings, JSON_PRETTY_PRINT) }}</pre>
                            </dd>
                        @endif

                        <dt class="col-sm-3">Date de création</dt>
                        <dd class="col-sm-9">{{ $sacco->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-3">Dernière mise à jour</dt>
                        <dd class="col-sm-9">{{ $sacco->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="card-footer">
                    <a href="{{ route('super-admin.sacos.index') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection