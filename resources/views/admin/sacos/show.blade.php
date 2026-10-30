@extends('layouts.app')

@section('title', __('view_sacco'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('view_sacco', ['name' => $sacco->name]) }}</h1>
        <a href="{{ route('admin.sacos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
        </a>
    </div>

    <!-- SACCO Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('sacco_information') }}</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">{{ __('name') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->name }}</dd>

                        <dt class="col-sm-3">{{ __('code') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->code }}</dd>

                        <dt class="col-sm-3">{{ __('registration_number') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->registration_number ?? __('n_a') }}</dd>

                        <dt class="col-sm-3">{{ __('physical_address') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->physical_address ?? __('n_a') }}</dd>

                        <dt class="col-sm-3">{{ __('official_mobile_money') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->mobile_money ?? __('n_a') }}</dd>

                        <dt class="col-sm-3">{{ __('official_email') }}:</dt>
                        <dd class="col-sm-9">
                            {{ $sacco->official_email ?? __('none') }}
                            @if ($sacco->official_email)
                                <br>
                                <small class="text-muted">
                                    ({{ __('official_email_auto_generated_approval') }})
                                </small>
                            @endif
                        </dd>

                        <dt class="col-sm-3">{{ __('primary_currency') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->primary_currency }}</dd>

                        <dt class="col-sm-3">{{ __('secondary_currency') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->secondary_currency }}</dd>

                        <dt class="col-sm-3">{{ __('status') }}:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-{{ $sacco->status === 'active' ? 'success' : 'secondary' }}">
                                {{ translate_status($sacco->status) }}
                            </span>

                        <dt class="col-sm-3">{{ __('creation_date') }}:</dt>
                        <dd class="col-sm-9">{{ $sacco->created_at ?? __('n_a') }}</dd>

                        <!-- Official Account Status -->
                        @php
                            $officialUser = \App\Models\User::where('sacco_id', $sacco->id)
                                ->where('name', 'Compte Officiel - ' . $sacco->name)
                                ->first();
                        @endphp
                        <dt class="col-sm-3">{{ __('official_account') }}:</dt>
                        <dd class="col-sm-9">
                            @if ($officialUser)
                                <span class="badge badge-{{ $officialUser->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $officialUser->status === 'active' ? __('active') : __('inactive') }}
                                </span>
                                @if ($officialUser->status === 'active')
                                    <form action="{{ route('admin.sacos.toggle-official-account', ['sacco' => $sacco->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2">
                                            {{ __('deactivate') }}
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.sacos.toggle-official-account', ['sacco' => $sacco->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success ms-2">
                                            {{ __('activate') }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">{{ __('no_official_account_found') }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection