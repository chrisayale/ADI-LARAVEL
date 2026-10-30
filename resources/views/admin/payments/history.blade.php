{{-- resources/views/admin/payments/history.blade.php --}}
@extends('layouts.app')

@section('title', __('payment_history'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('payment_history') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.payments.collect') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> {{ __('new_payment') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalCollected, 2) }}</h3>
                                    <p>{{ __('todays_collections') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $transactions->total() }}</h3>
                                    <p>{{ __('total_transactions') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $transactions->count() }}</h3>
                                    <p>{{ __('showing') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('filter_payments') }}</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.payments.history') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('date_from') }}</label>
                                            <input type="date"
                                                   name="date_from"
                                                   class="form-control"
                                                   value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('date_to') }}</label>
                                            <input type="date"
                                                   name="date_to"
                                                   class="form-control"
                                                   value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('member') }}</label>
                                            <select name="user_id" class="form-control">
                                                <option value="">{{ __('all_members') }}</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                                        {{ $member->name }} ({{ $member->membership_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('payment_method') }}</label>
                                            <select name="payment_method" class="form-control">
                                                <option value="">{{ __('all_methods') }}</option>
                                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('cash') }}</option>
                                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('bank_transfer') }}</option>
                                                <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>{{ __('mobile_money') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter"></i> {{ __('apply_filters') }}
                                        </button>
                                        <a href="{{ route('admin.payments.history') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('clear') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>{{ __('receipt_number') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('member') }}</th>
                                    <th>{{ __('loan') }} #</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('payment_method') }}</th>
                                    <th>{{ __('reference_number') }}</th>
                                    <th>{{ __('collected_by') }}</th>
                                    <th>{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->receipt_number }}</strong>
                                    </td>
                                    <td>{{ $transaction->payment_date->format('d/m/Y H:i') }}</td>
                                    <td>
                                        {{ $transaction->user->name }}
                                        <br>
                                        <small class="text-muted">{{ $transaction->user->membership_number }}</small>
                                    </td>
                                    <td>
                                        @if($transaction->loanRepayment)
                                            {{ $transaction->loanRepayment->loanApplication->application_number ?? __('n_a') }}
                                            <br>
                                            <small>{{ __('installment') }} #{{ $transaction->loanRepayment->installment_number }}</small>
                                        @else
                                            {{ __('n_a') }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($transaction->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->payment_method == 'cash' ? 'success' : ($transaction->payment_method == 'bank_transfer' ? 'info' : 'warning') }}">
                                            {{ $transaction->payment_method == 'cash' ? __('cash') : ($transaction->payment_method == 'bank_transfer' ? __('bank_transfer') : __('mobile_money')) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->reference_number ?? __('n_a') }}</td>
                                    <td>{{ $transaction->recorder->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.payments.receipt', $transaction) }}"
                                           class="btn btn-sm btn-info"
                                           target="_blank"
                                           title="{{ __('print_receipt') }}">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('no_payment_transactions_found') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection