{{-- resources/views/treasurer/savings/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Transaction Details - ' . $saving->receipt_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaction Details</h1>
        <div>
            <a href="{{ route('treasurer.savings.transactions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('treasurer.savings.print-receipt', $saving) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-print"></i> Print Receipt
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Transaction Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Receipt Number:</th>
                                    <td><strong>{{ $saving->receipt_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Transaction Number:</th>
                                    <td>{{ $saving->transaction_number }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction Type:</th>
                                    <td>
                                        <span class="badge badge-{{ $saving->type_color }} p-2">
                                            <i class="fas fa-{{ $saving->type_icon }} mr-1"></i>
                                            {{ ucfirst($saving->transaction_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td><h4 class="text-{{ $saving->transaction_type == 'deposit' ? 'success' : 'info' }}">CDF {{ number_format($saving->amount, 2) }}</h4></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Transaction Date:</th>
                                    <td>{{ $saving->transaction_date->format('l, d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Recorded On:</th>
                                    <td>{{ $saving->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Recorded By:</th>
                                    <td>{{ $saving->recorder->name }}</td>
                                </tr>
                                @if($saving->notes)
                                <tr>
                                    <th>Notes:</th>
                                    <td>{{ $saving->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
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
                            <p class="mb-0"><small>{{ $saving->user->phone_number }}</small></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center py-3">
                                    <small class="text-muted">Balance After Transaction</small>
                                    <h5 class="mb-0 text-primary">CDF {{ number_format($saving->balance_after, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-{{ $saving->transaction_type == 'deposit' ? 'success' : 'info' }} text-white mb-3">
                                <div class="card-body text-center py-3">
                                    <small>Transaction Status</small>
                                    <h5 class="mb-0">
                                        <i class="fas fa-check-circle mr-1"></i> Completed
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('treasurer.savings.print-receipt', $saving) }}"
                           target="_blank"
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-print mr-2 text-info"></i> Print Receipt
                        </a>
                        <a href="{{ route('treasurer.savings.create') }}?user_id={{ $saving->user_id }}"
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-plus mr-2 text-success"></i> New Transaction for {{ explode(' ', $saving->user->name)[0] }}
                        </a>
                        <a href="{{ route('treasurer.savings.transactions') }}?search={{ $saving->user->membership_number }}"
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-history mr-2 text-primary"></i> View Member's Transactions
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction Stats Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaction Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-gray-500">Receipt Generated</div>
                        <div class="h6 mb-0">{{ $saving->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">Recorded By</div>
                        <div class="h6 mb-0">{{ $saving->recorder->name }}</div>
                        <small class="text-muted">{{ $saving->recorder->email }}</small>
                    </div>
                    <hr>
                    <div>
                        <div class="small text-gray-500">Transaction ID</div>
                        <div class="h6 mb-0"><small>{{ $saving->transaction_number }}</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
