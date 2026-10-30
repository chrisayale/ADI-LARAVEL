{{-- resources/views/treasurer/verifications/verified.blade.php --}}
@extends('layouts.app')

@section('title', 'Verified Loans')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Verified Loans</h1>
        <div>
            <a href="{{ route('treasurer.verifications.pending') }}" class="btn btn-warning">
                <i class="fas fa-clock"></i> Pending Verifications
            </a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Verified Loans</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('treasurer.verifications.verified') }}" class="row">
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
                        <a href="{{ route('treasurer.verifications.verified') }}" class="btn btn-secondary btn-block ml-2">
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
            <h6 class="m-0 font-weight-bold text-primary">Verified Loan Applications</h6>
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
                            <th>Amount Approved</th>
                            <th>Verified Date</th>
                            <th>Verified By</th>
                            <th>Status</th>
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
                            <td class="text-right">CDF {{ number_format($loan->amount_applied, 2) }}</td>
                            <td class="text-right">
                                @if($loan->amount_approved)
                                    CDF {{ number_format($loan->amount_approved, 2) }}
                                @else
                                    <span class="text-muted">Pending</span>
                                @endif
                            </td>
                            <td>{{ $loan->verified_at ? $loan->verified_at->format('d M Y H:i') : 'N/A' }}</td>
                            <td>{{ $loan->verifier->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'verified' => 'primary',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'disbursed' => 'info',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$loan->status] ?? 'secondary' }} p-2">
                                    {{ translate_status($loan->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No verified loans found</p>
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
