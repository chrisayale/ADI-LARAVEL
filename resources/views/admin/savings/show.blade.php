@extends('layouts.app')

@section('title', __('transaction_details') . ' - ' . $saving->transaction_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('transaction_details') }}: {{ $saving->transaction_number }}</h1>
        <div>
            <a href="{{ route('admin.savings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
            </a>
            @if($saving->status == 'pending')
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                    <i class="fas fa-check"></i> {{ __('approve') }}
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                    <i class="fas fa-times"></i> {{ __('reject') }}
                </button>
            @endif
            @if($saving->status == 'approved' && $saving->receipt_number)
                <a href="{{ route('admin.savings.print-receipt', $saving) }}" target="_blank" class="btn btn-info">
                    <i class="fas fa-print"></i> {{ __('print_receipt') }}
                </a>
            @endif
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $statusColors = [
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger'
                ];
                $color = $statusColors[$saving->status] ?? 'secondary';
            @endphp
            <div class="alert alert-{{ $color }} mb-0">
                <strong>{{ __('status') }}:</strong> {{ translate_status($saving->status) }}
                @if($saving->status == 'approved' && $saving->created_at != $saving->updated_at)
                    - {{ __('approved_on') }} {{ $saving->updated_at->format('d M Y H:i') }}
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transaction Details -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('transaction_information') }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">{{ __('transaction_number') }}:</th>
                            <td><strong>{{ $saving->transaction_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>{{ __('receipt_number') }}:</th>
                            <td>
                                @if($saving->receipt_number)
                                    <strong>{{ $saving->receipt_number }}</strong>
                                @else
                                    <span class="text-muted">{{ __('not_generated_yet') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('transaction_type') }}:</th>
                            <td>
                                <span class="badge badge-{{ $saving->type_color }} p-2">
                                    <i class="fas fa-{{ $saving->type_icon }} mr-1"></i>
                                    {{ $saving->transaction_type == 'deposit' ? __('deposit') : __('withdrawal') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('amount') }}:</th>
                            <td><h4 class="text-{{ $saving->transaction_type == 'deposit' ? 'success' : 'info' }}">UGX {{ number_format($saving->amount, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <th>{{ __('transaction_date') }}:</th>
                            <td>{{ $saving->transaction_date->format('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('recorded_on') }}:</th>
                            <td>{{ $saving->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('recorded_by') }}:</th>
                            <td>
                                <strong>{{ $saving->recorder->name }}</strong>
                                <br><small class="text-muted">{{ $saving->recorder->email }}</small>
                            </td>
                        </tr>
                        @if($saving->notes)
                        <tr>
                            <th>{{ __('notes') }}:</th>
                            <td>{{ $saving->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Member & Balance Details -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('member_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($saving->user->profile_photo)
                            <img src="{{ asset('storage/' . $saving->user->profile_photo) }}"
                                 class="rounded-circle mr-3" width="60" height="60" alt="Profile">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                 style="width: 60px; height: 60px; font-size: 1.5rem;">
                                {{ strtoupper(substr($saving->user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-0">{{ $saving->user->name }}</h5>
                            @if($saving->user->membership_number)
                                <p class="text-muted mb-0">#{{ $saving->user->membership_number }}</p>
                            @endif
                            <p class="mb-0"><small>{{ $saving->user->email }}</small></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center py-3">
                                    <small class="text-muted">{{ __('balance_before') }}</small>
                                    <h5 class="mb-0">UGX {{ number_format($balanceBefore, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-{{ $saving->transaction_type == 'deposit' ? 'success' : 'info' }} text-white mb-3">
                                <div class="card-body text-center py-3">
                                    <small>{{ __('balance_after') }}</small>
                                    <h5 class="mb-0">UGX {{ number_format($saving->balance_after, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.show', $saving->user) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user"></i> {{ __('view_member_profile') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions for this Member -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('recent_transactions_for') }} {{ $saving->user->name }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('transaction_number') }}</th>
                                    <th>{{ __('type') }}</th>
                                    <th>{{ __('amount') }}</th>
                                    <th>{{ __('balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentTransactions = $saving->user->savingsTransactions()
                                        ->where('status', 'approved')
                                        ->where('id', '!=', $saving->id)
                                        ->latest()
                                        ->limit(5)
                                        ->get();
                                @endphp
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.savings.show', $transaction) }}">
                                            {{ $transaction->transaction_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->type_color }}">
                                            {{ $transaction->transaction_type == 'deposit' ? __('deposit') : __('withdrawal') }}
                                        </span>
                                    </td>
                                    <td class="text-right">UGX {{ number_format($transaction->amount, 2) }}</td>
                                    <td class="text-right">UGX {{ number_format($transaction->balance_after, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">
                                        {{ __('no_other_transactions_found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if($saving->status == 'pending')
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.savings.approve', $saving) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('approve_transaction') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('sure_approve_transaction') }}</p>
                    <div class="alert alert-info">
                        <p class="mb-1"><strong>{{ __('member') }}:</strong> {{ $saving->user->name }}</p>
                        <p class="mb-1"><strong>{{ __('transaction_type') }}:</strong> {{ $saving->transaction_type == 'deposit' ? __('deposit') : __('withdrawal') }}</p>
                        <p class="mb-1"><strong>{{ __('amount') }}:</strong> UGX {{ number_format($saving->amount, 2) }}</p>
                        <p class="mb-0"><strong>{{ __('balance_after') }}:</strong> UGX {{ number_format($saving->balance_after, 2) }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('approve_transaction') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.savings.reject', $saving) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('reject_transaction') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">{{ __('rejection_reason') }}</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                  rows="3" required placeholder="{{ __('rejection_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('reject_transaction') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection