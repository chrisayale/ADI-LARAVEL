@extends('layouts.app')

@section('title', 'Completed Loans')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Completed Loans</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Stats -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalCompleted }}</h3>
                                    <p>Total Completed Loans</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ number_format($totalRepaid, 2) }}</h3>
                                    <p>Total Amount Repaid</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Application #</th>
                                    <th>Member</th>
                                    <th>Amount Approved</th>
                                    <th>Total Repaid</th>
                                    <th>Completion Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedLoans as $loan)
                                <tr>
                                    <td>{{ $loan->application_number }}</td>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ number_format($loan->amount_approved, 2) }}</td>
                                    <td>{{ number_format($loan->total_repayable, 2) }}</td>
                                    <td>{{ $loan->updated_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.loans.show', $loan) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No completed loans found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $completedLoans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
