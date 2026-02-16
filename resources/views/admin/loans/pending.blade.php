@extends('layouts.app')

@section('title', 'Pending Loan Applications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Loan Applications</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.loans.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> New Loan Application
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Stats -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $totalPending }}</h3>
                                    <p>Total Pending Applications</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalAmount, 2) }}</h3>
                                    <p>Total Amount Applied</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.loans.pending') }}" class="mb-4">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search by member name or membership number..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.loans.pending') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Loans Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Application #</th>
                                    <th>Member</th>
                                    <th>Membership #</th>
                                    <th>Amount Applied</th>
                                    <th>Purpose</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                <tr>
                                    <td>{{ $loan->application_number }}</td>
                                    <td>{{ $loan->user->name }}</td>
                                    <td>{{ $loan->user->membership_number }}</td>
                                    <td>{{ number_format($loan->amount_applied, 2) }}</td>
                                    <td>{{ Str::limit($loan->purpose, 30) }}</td>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.loans.show', $loan) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No pending loan applications found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $loans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
