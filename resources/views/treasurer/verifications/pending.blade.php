{{-- resources/views/treasurer/verifications/pending.blade.php --}}
@extends('layouts.app')

@section('title', 'Pending Loan Verifications')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pending Loan Verifications</h1>
        <div>
            <a href="{{ route('treasurer.verifications.verified') }}" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Verified Loans
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                With Collateral</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['with_collateral'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                With Guarantors</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['with_guarantors'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Loans</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.verifications.pending') }}" class="row">
                <div class="col-md-8 mb-3">
                    <label for="search">Search by Member Name or Membership Number</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Enter name or membership number...">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-secondary btn-block ml-2">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loans Pending Verification</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Application #</th>
                            <th>Member</th>
                            <th>Amount Applied</th>
                            <th>Applied Date</th>
                            <th>Collateral</th>
                            <th>Guarantors</th>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                        <tr>
                            <td>{{ $loans->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $loan->application_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($loan->user->profile_photo)
                                        <img src="{{ asset('storage/' . $loan->user->profile_photo) }}"
                                             class="rounded-circle mr-2" width="30" height="30" alt="Profile">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2"
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $loan->user->name }}</strong>
                                        <br><small class="text-muted">{{ $loan->user->membership_number ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <strong>UGX {{ number_format($loan->amount_applied, 2) }}</strong>
                            </td>
                            <td>{{ $loan->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($loan->has_collateral)
                                    <span class="badge badge-info p-2">
                                        <i class="fas fa-check-circle mr-1"></i> Yes
                                    </span>
                                    <br><small class="text-muted">{{ ucfirst($loan->collateral_type) }}</small>
                                @else
                                    <span class="badge badge-secondary p-2">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $guarantorCount = $loan->guarantors->count();
                                    $verifiedCount = $loan->guarantors->where('status', 'approved')->count();
                                @endphp
                                @if($guarantorCount > 0)
                                    <span class="badge badge-info p-2">
                                        {{ $guarantorCount }} total
                                    </span>
                                    @if($verifiedCount > 0)
                                        <br><small class="text-success">{{ $verifiedCount }} verified</small>
                                    @endif
                                @else
                                    <span class="badge badge-secondary p-2">None</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $docCount = $loan->documents->count();
                                    $verifiedDocCount = $loan->documents->where('verification_status', 'verified')->count();
                                @endphp
                                @if($docCount > 0)
                                    <span class="badge badge-info p-2">
                                        {{ $docCount }} files
                                    </span>
                                    @if($verifiedDocCount > 0)
                                        <br><small class="text-success">{{ $verifiedDocCount }} verified</small>
                                    @endif
                                @else
                                    <span class="badge badge-secondary p-2">None</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('treasurer.verifications.show', $loan) }}"
                                   class="btn btn-info btn-sm" title="Verify Loan">
                                    <i class="fas fa-check-circle"></i> Verify
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No loans pending verification</p>
                                @if(request('search'))
                                    <p class="text-muted">Try adjusting your search criteria</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $loans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
