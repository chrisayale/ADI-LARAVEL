@extends('layouts.app')

@section('title', __('loan_applications_under_verification'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('loan_applications_under_verification') }}</h3>
                    <div class="card-tools">
                        <!-- Filter by Treasurer -->
                        <form method="GET" action="{{ route('admin.loans.under-verification') }}" class="d-inline-block">
                            <select name="treasurer_id" class="form-control form-control-sm d-inline-block" style="width: 200px;" onchange="this.form.submit()">
                                <option value="">{{ __('all_treasurers') }}</option>
                                @foreach($treasurers as $treasurer)
                                    <option value="{{ $treasurer->id }}" {{ request('treasurer_id') == $treasurer->id ? 'selected' : '' }}>
                                        {{ $treasurer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('application_number') }}</th>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('amount_applied') }}</th>
                                    <th>{{ __('treasurer') }}</th>
                                    <th>{{ __('assigned_date') }}</th>
                                    <th>{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                <tr>
                                    <td>{{ $loan->application_number }}</td>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ number_format($loan->amount_applied, 2) }}</td>
                                    <td>
                                        @if($loan->treasurer)
                                            <span class="badge bg-info">{{ $loan->treasurer->name }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('not_assigned') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $loan->assigned_at ? $loan->assigned_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.loans.show', $loan) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('view') }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('no_loans_under_verification_found') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $loans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection