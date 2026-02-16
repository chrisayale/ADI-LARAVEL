@extends('layouts.app')

@section('title', 'Loan Details - ' . $loan->application_number)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Details</h1>
        <div>
            <a href="{{ route('member.loans.applications') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Applications
            </a>
            @if(in_array($loan->status, ['approved', 'disbursed']))
                <a href="{{ route('member.loans.repayment-schedule', $loan) }}" class="btn btn-success">
                    <i class="fas fa-calendar"></i> Repayment Schedule
                </a>
            @endif
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $statusColors = [
                    'pending' => 'secondary',
                    'under_verification' => 'info',
                    'verified' => 'primary',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'disbursed' => 'primary',
                    'completed' => 'success',
                ];
            @endphp
            <div class="alert alert-{{ $statusColors[$loan->status] ?? 'secondary' }} mb-0">
                <strong>Status:</strong> {{ str_replace('_', ' ', ucfirst($loan->status)) }}
                @if($loan->status == 'approved' && $loan->approved_at)
                    - Approved on {{ $loan->approved_at->format('d M Y') }}
                @elseif($loan->status == 'disbursed' && $loan->disbursed_at)
                    - Disbursed on {{ $loan->disbursed_at->format('d M Y') }}
                @elseif($loan->status == 'rejected' && $loan->rejected_at)
                    - Rejected on {{ $loan->rejected_at->format('d M Y') }}
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Loan Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Loan Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="45%">Application Number:</th>
                                    <td><strong>{{ $loan->application_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Application Date:</th>
                                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Loan Purpose:</th>
                                    <td>{{ $loan->purpose ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount Applied:</th>
                                    <td><strong>UGX {{ number_format($loan->amount_applied, 2) }}</strong></td>
                                </tr>
                                @if($loan->amount_approved)
                                <tr>
                                    <th>Amount Approved:</th>
                                    <td><strong class="text-success">UGX {{ number_format($loan->amount_approved, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($loan->interest_rate)
                                <tr>
                                    <th width="45%">Interest Rate:</th>
                                    <td>{{ $loan->interest_rate }}% per annum</td>
                                </tr>
                                @endif
                                @if($loan->duration_months)
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $loan->duration_months }} months</td>
                                </tr>
                                @endif
                                @if($loan->monthly_repayment)
                                <tr>
                                    <th>Monthly Repayment:</th>
                                    <td><strong>UGX {{ number_format($loan->monthly_repayment, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($loan->total_repayable)
                                <tr>
                                    <th>Total Repayable:</th>
                                    <td><strong>UGX {{ number_format($loan->total_repayable, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($loan->first_payment_date)
                                <tr>
                                    <th>First Payment:</th>
                                    <td>{{ $loan->first_payment_date->format('d M Y') }}</td>
                                </tr>
                                @endif
                                @if($loan->maturity_date)
                                <tr>
                                    <th>Maturity Date:</th>
                                    <td>{{ $loan->maturity_date->format('d M Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($loan->approval_notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Approval Notes:</strong><br>
                                {{ $loan->approval_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($loan->verification_notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <strong>Verification Notes:</strong><br>
                                {{ $loan->verification_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($loan->rejection_reason)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <strong>Rejection Reason:</strong><br>
                                {{ $loan->rejection_reason }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guarantors -->
            @if($loan->guarantors && $loan->guarantors->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guarantors</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ID Number</th>
                                    <th>Phone</th>
                                    <th>Relationship</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->guarantors as $guarantor)
                                <tr>
                                    <td>{{ $guarantor->name }}</td>
                                    <td>{{ $guarantor->id_number ?? 'N/A' }}</td>
                                    <td>{{ $guarantor->phone ?? 'N/A' }}</td>
                                    <td>{{ $guarantor->relationship ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$guarantor->status] ?? 'secondary' }}">
                                            {{ ucfirst($guarantor->status ?? 'pending') }}
                                        </span>
                                        @if($guarantor->verified_at)
                                            <br><small class="text-muted">Verified: {{ $guarantor->verified_at->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Collateral Information -->
            @if($loan->has_collateral)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Collateral Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Type:</strong> {{ ucfirst($loan->collateral_type) }}
                        </div>
                        <div class="col-md-4">
                            <strong>Value:</strong> UGX {{ number_format($loan->collateral_value, 2) }}
                        </div>
                        <div class="col-md-12 mt-3">
                            <strong>Description:</strong>
                            <p class="mb-0">{{ $loan->collateral_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents -->
            @if($loan->documents && $loan->documents->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Uploaded Documents</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>Uploaded</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->documents as $document)
                                <tr>
                                    <td>
                                        <i class="fas {{ $document->file_icon }} mr-2"></i>
                                        {{ Str::limit($document->document_name, 30) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($document->category)) }}</span>
                                    </td>
                                    <td>{{ $document->formatted_size }}</td>
                                    <td>{{ $document->created_at->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $docStatusColors = [
                                                'pending' => 'warning',
                                                'verified' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $docStatusColors[$document->verification_status] ?? 'secondary' }}">
                                            {{ ucfirst($document->verification_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.loans.download-document', $document) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Repayments -->
            @if($loan->repayments && $loan->repayments->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Repayments</h6>
                    <a href="{{ route('member.loans.repayment-schedule', $loan) }}" class="btn btn-sm btn-primary">
                        View Full Schedule
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Due Date</th>
                                    <th>Amount Due</th>
                                    <th>Amount Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Paid Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->repayments->take(5) as $repayment)
                                <tr class="{{ $repayment->due_date < now() && $repayment->status != 'paid' ? 'table-danger' : '' }}">
                                    <td>{{ $repayment->installment_number }}</td>
                                    <td>{{ $repayment->due_date->format('d M Y') }}</td>
                                    <td class="text-right">UGX {{ number_format($repayment->amount_due, 2) }}</td>
                                    <td class="text-right">UGX {{ number_format($repayment->amount_paid, 2) }}</td>
                                    <td class="text-right">UGX {{ number_format($repayment->amount_due - $repayment->amount_paid, 2) }}</td>
                                    <td>
                                        @php
                                            $repaymentStatusColors = [
                                                'pending' => 'secondary',
                                                'partial' => 'info',
                                                'paid' => 'success',
                                                'overdue' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $repaymentStatusColors[$repayment->status] ?? 'secondary' }}">
                                            {{ ucfirst($repayment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $repayment->paid_date ? $repayment->paid_date->format('d M Y') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Loan Summary Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Loan Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-gray-500">Total Paid</div>
                        <div class="h5 mb-0 font-weight-bold text-success">
                            UGX {{ number_format($loan->repayments->sum('amount_paid'), 2) }}
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">Outstanding Balance</div>
                        <div class="h5 mb-0 font-weight-bold {{ $loan->balance > 0 ? 'text-danger' : 'text-success' }}">
                            UGX {{ number_format($loan->balance, 2) }}
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small text-gray-500">Progress</div>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $loan->progress_percentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ $loan->progress_percentage }}% paid</small>
                    </div>
                </div>
            </div>

            <!-- Next Payment Card -->
            @php
                $nextPayment = $loan->repayments()
                    ->whereIn('status', ['pending', 'partial'])
                    ->orderBy('due_date')
                    ->first();
            @endphp
            @if($nextPayment)
            <div class="card shadow mb-4 border-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Next Payment</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold">UGX {{ number_format($nextPayment->amount_due - $nextPayment->amount_paid, 2) }}</h3>
                        <p class="mb-0">Due: {{ $nextPayment->due_date->format('d F Y') }}</p>
                        @if($nextPayment->due_date < now())
                            <span class="badge badge-danger mt-2">Overdue</span>
                        @endif
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Installment #{{ $nextPayment->installment_number }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
