@extends('layouts.app')

@section('title', __('loan_application_details'))

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('loan_application_details') }}</h1>
        <div>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('back_to_list') }}
            </a>

            @if($loan->status == 'pending')
                @if(auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-primary" onclick="openAssignTreasurerModal({{ $loan->id }})">
                        <i class="fas fa-user-tie"></i> {{ __('assign_treasurer') }}
                    </button>
                @endif
            @endif

            @if($loan->status == 'under_verification' && auth()->user()->hasRole('treasurer') && auth()->id() == $loan->treasurer_id)
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#verifyLoanModal">
                    <i class="fas fa-check-circle"></i> {{ __('verify_loan') }}
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectLoanModal">
                    <i class="fas fa-times"></i> {{ __('reject_loan') }}
                </button>
            @endif

            @if($loan->status == 'verified' && auth()->user()->hasRole('admin'))
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveLoanModal">
                    <i class="fas fa-check"></i> {{ __('approve_loan') }}
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectLoanModal">
                    <i class="fas fa-times"></i> {{ __('reject_loan') }}
                </button>
            @endif

            @if($loan->status == 'approved' && auth()->user()->hasRole('admin'))
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#disburseLoanModal">
                    <i class="fas fa-money-bill-wave"></i> {{ __('disburse_loan') }}
                </button>
            @endif

            @if($loan->status == 'disbursed' && auth()->user()->hasRole('admin'))
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#markCompletedModal">
                    <i class="fas fa-check-double"></i> {{ __('mark_completed') }}
                </button>
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
                    'defaulted' => 'danger'
                ];
                $color = $statusColors[$loan->status] ?? 'secondary';
            @endphp
            <div class="alert alert-{{ $color }} mb-0">
                <strong>{{ __('status') }}:</strong> {{ translate_status($loan->status) }}
                @if($loan->status == 'approved' && $loan->approved_at)
                    - {{ __('approved_on') }} {{ $loan->approved_at->format('d M Y H:i') }}
                    @if($loan->approver)
                        {{ __('by') }} {{ $loan->approver->name }}
                    @endif
                @elseif($loan->status == 'rejected' && $loan->rejected_at)
                    - {{ __('rejected_on') }} {{ $loan->rejected_at->format('d M Y H:i') }}
                    @if($loan->rejector)
                        {{ __('by') }} {{ $loan->rejector->name }}
                    @endif
                @elseif($loan->status == 'verified' && $loan->verified_at)
                    - {{ __('verified_on') }} {{ $loan->verified_at->format('d M Y H:i') }}
                    @if($loan->verifier)
                        {{ __('by') }} {{ $loan->verifier->name }}
                    @endif
                @elseif($loan->status == 'disbursed' && $loan->disbursed_at)
                    - {{ __('disbursed_on') }} {{ $loan->disbursed_at->format('d M Y H:i') }}
                    @if($loan->disburser)
                        {{ __('by') }} {{ $loan->disburser->name }}
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Details - Full Width -->
        <div class="col-12">
            <!-- Member Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('member_information') }}</h6>
                </div>
                <div class="card-body">
                    @if($loan->user)
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('name') }}:</th>
                                    <td>
                                        <strong>{{ $loan->user->name }}</strong>
                                        @if($loan->user->membership_number)
                                            <br><small class="text-muted">#{{ $loan->user->membership_number }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('email') }}:</th>
                                    <td>{{ $loan->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('phone') }}:</th>
                                    <td>{{ $loan->user->phone_number ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('address') }}:</th>
                                    <td>{{ $loan->user->address ?? __('n_a') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('member_since') }}:</th>
                                    <td>{{ $loan->user->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('status') }}:</th>
                                    <td>
                                        <span class="badge badge-{{ $loan->user->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($loan->user->status ?? 'active') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('roles') }}:</th>
                                    <td>
                                        @if($loan->user->roles && $loan->user->roles->count() > 0)
                                            @foreach($loan->user->roles as $role)
                                                <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-secondary">{{ __('member') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">{{ __('member_information_not_available') }}</p>
                    @endif
                </div>
            </div>

            <!-- Loan Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('loan_details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="45%">{{ __('application_number') }}:</th>
                                    <td>{{ $loan->application_number ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('applied_date') }}:</th>
                                    <td>{{ $loan->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('purpose') }}:</th>
                                    <td>{{ $loan->purpose ?? __('not_specified') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('amount_applied') }}:</th>
                                    <td><strong>CDF {{ number_format($loan->amount_applied, 2) }}</strong></td>
                                </tr>
                                @if($loan->amount_approved)
                                <tr>
                                    <th>{{ __('amount_approved') }}:</th>
                                    <td><strong class="text-success">CDF {{ number_format($loan->amount_approved, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($loan->interest_rate)
                                <tr>
                                    <th width="45%">{{ __('interest_rate') }}:</th>
                                    <td>{{ $loan->interest_rate }}% {{ __('per_annum') }}</td>
                                </tr>
                                @endif
                                @if($loan->duration_months)
                                <tr>
                                    <th>{{ __('duration') }}:</th>
                                    <td>{{ $loan->duration_months }} {{ __('months') }}</td>
                                </tr>
                                @endif
                                @if($loan->monthly_repayment)
                                <tr>
                                    <th>{{ __('monthly_payment') }}:</th>
                                    <td><strong>CDF {{ number_format($loan->monthly_repayment, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($loan->total_repayable)
                                <tr>
                                    <th>{{ __('total_repayable') }}:</th>
                                    <td><strong>CDF {{ number_format($loan->total_repayable, 2) }}</strong></td>
                                </tr>
                                @endif
                                @if($loan->first_payment_date)
                                <tr>
                                    <th>{{ __('first_payment') }}:</th>
                                    <td>{{ $loan->first_payment_date->format('d M Y') }}</td>
                                </tr>
                                @endif
                                @if($loan->maturity_date)
                                <tr>
                                    <th>{{ __('maturity_date') }}:</th>
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
                                <strong>{{ __('approval_notes') }}:</strong><br>
                                {{ $loan->approval_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($loan->verification_notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <strong>{{ __('verification_notes') }}:</strong><br>
                                {{ $loan->verification_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($loan->rejection_reason)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <strong>{{ __('rejection_reason') }}:</strong><br>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('guarantors') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('id_number') }}</th>
                                    <th>{{ __('phone') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('relationship') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('documents') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->guarantors as $index => $guarantor)
                                <tr>
                                    <td>{{ $guarantor->name }}</td>
                                    <td>{{ $guarantor->id_number ?? __('n_a') }}</td>
                                    <td>{{ $guarantor->phone ?? __('n_a') }}</td>
                                    <td>{{ $guarantor->email ?? __('n_a') }}</td>
                                    <td>{{ $guarantor->relationship ?? __('n_a') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $statusColor = $statusColors[$guarantor->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">
                                            {{ ucfirst($guarantor->status ?? 'pending') }}
                                        </span>
                                        @if($guarantor->verified_at)
                                            <br><small class="text-muted">{{ __('verified') }}: {{ $guarantor->verified_at->format('d M Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $guarantorDocs = $loan->documents ? $loan->documents->where('guarantor_id', $guarantor->id) : collect();
                                        @endphp
                                        @if($guarantorDocs->count() > 0)
                                            <span class="badge badge-info">{{ $guarantorDocs->count() }} {{ __('files') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('no_docs') }}</span>
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('collateral_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('type') }}:</strong> {{ ucfirst($loan->collateral_type) }}
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('value') }}:</strong> CDF {{ number_format($loan->collateral_value, 2) }}
                        </div>
                        <div class="col-md-12 mt-3">
                            <strong>{{ __('description') }}:</strong>
                            <p class="mb-0">{{ $loan->collateral_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents Section with Detailed View -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('supporting_documents') }}</h6>
                    @if($loan->documents && $loan->documents->count() > 0)
                        <span class="badge badge-info">{{ $loan->documents->count() }} {{ __('files') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($loan->documents && $loan->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('document') }}</th>
                                        <th>{{ __('category') }}</th>
                                        <th>{{ __('size') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('linked_to') }}</th>
                                        <th>{{ __('uploaded') }}</th>
                                        <th>{{ __('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loan->documents as $document)
                                    <tr>
                                        <td>
                                            <i class="fas {{ $document->file_icon }} mr-2"></i>
                                            {{ Str::limit($document->document_name, 30) }}
                                            @if($document->description)
                                                <br><small class="text-muted">{{ $document->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($document->category)) }}</span>
                                        </td>
                                        <td>{{ $document->formatted_size }}</td>
                                        <td>
                                            @php
                                                $docStatusColors = [
                                                    'pending' => 'warning',
                                                    'verified' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                                $docStatusColor = $docStatusColors[$document->verification_status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-{{ $docStatusColor }}">
                                                {{ ucfirst($document->verification_status) }}
                                            </span>
                                            @if($document->verified_at)
                                                <br><small>{{ $document->verified_at->format('d M Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($document->guarantor)
                                                {{ $document->guarantor->name }}
                                            @else
                                                <span class="text-muted">{{ __('loan_document') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $document->created_at->format('d M Y') }}</small><br>
                                            <small class="text-muted">{{ __('by') }} {{ $document->uploader->name ?? __('n_a') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.loans.download-document', $document) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i> {{ __('view') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Document Summary Cards -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body py-2">
                                        <small>{{ __('verified') }}</small>
                                        <h6>{{ $loan->documents->where('verification_status', 'verified')->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body py-2">
                                        <small>{{ __('pending') }}</small>
                                        <h6>{{ $loan->documents->where('verification_status', 'pending')->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body py-2">
                                        <small>{{ __('rejected') }}</small>
                                        <h6>{{ $loan->documents->where('verification_status', 'rejected')->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body py-2">
                                        <small>{{ __('total_size') }}</small>
                                        <h6>{{ round($loan->documents->sum('file_size') / (1024 * 1024), 2) }} MB</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">{{ __('no_documents_uploaded') }}</p>
                            <small class="text-muted">{{ __('documents_will_appear') }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Amortization Schedule -->
            @include('partials.amortization-schedule', ['loan' => $loan])

            <!-- Processing Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('processing_information') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('created_by') }}:</th>
                                    <td>
                                        @if($loan->creator)
                                            {{ $loan->creator->name }}
                                        @else
                                            <span class="text-muted">{{ __('system') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('created_at') }}:</th>
                                    <td>{{ $loan->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('treasurer') }}:</th>
                                    <td>
                                        @if($loan->treasurer)
                                            {{ $loan->treasurer->name }}
                                        @else
                                            <span class="text-muted">{{ __('not_assigned') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($loan->verified_by)
                                <tr>
                                    <th width="40%">{{ __('verified_by') }}:</th>
                                    <td>{{ $loan->verifier->name ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('verified_at') }}:</th>
                                    <td>{{ $loan->verified_at ? $loan->verified_at->format('d M Y H:i') : __('n_a') }}</td>
                                </tr>
                                @endif
                                @if($loan->approved_by)
                                <tr>
                                    <th>{{ __('approved_by') }}:</th>
                                    <td>{{ $loan->approver->name ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('approved_at') }}:</th>
                                    <td>{{ $loan->approved_at ? $loan->approved_at->format('d M Y H:i') : __('n_a') }}</td>
                                </tr>
                                @endif
                                @if($loan->disbursed_by)
                                <tr>
                                    <th>{{ __('disbursed_by') }}:</th>
                                    <td>{{ $loan->disburser->name ?? __('n_a') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('disbursed_at') }}:</th>
                                    <td>{{ $loan->disbursed_at ? $loan->disbursed_at->format('d M Y H:i') : __('n_a') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            @if(isset($loan->activities) && $loan->activities->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('activity_log') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($loan->activities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <small class="text-muted">{{ $activity->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ $activity->description }}</p>
                            @if($activity->causer)
                                <small>{{ __('by') }} {{ $activity->causer->name }}</small>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Assign Treasurer Modal (SINGLE MODAL) -->
<div class="modal fade" id="assignTreasurerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="assignTreasurerForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('assign_treasurer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_treasurer_id">{{ __('select_treasurer') }}</label>
                        <select class="form-control" id="modal_treasurer_id" name="treasurer_id" required>
                            <option value="">{{ __('choose_treasurer') }}</option>
                            @foreach($treasurers ?? [] as $treasurer)
                                <option value="{{ $treasurer->id }}">{{ $treasurer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        {{ __('assign_treasurer_will_move_to_under_verification') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('assign') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Verify Loan Modal -->
<div class="modal fade" id="verifyLoanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.verify', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('verify_loan_application') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('please_verify_all_guarantors_collateral_documents') }}
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="guarantors_verified" name="guarantors_verified" value="1" required>
                            <label class="custom-control-label" for="guarantors_verified">
                                {{ __('i_confirm_all_guarantors_verified_valid') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="collateral_verified" name="collateral_verified" value="1" required>
                            <label class="custom-control-label" for="collateral_verified">
                                {{ __('i_confirm_collateral_verified_valued_correctly') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="documents_verified" name="documents_verified" value="1" required>
                            <label class="custom-control-label" for="documents_verified">
                                {{ __('i_confirm_all_documents_reviewed_valid') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="verification_notes">{{ __('verification_notes') }}</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes"
                                  rows="4" required placeholder="{{ __('please_provide_details_of_verification') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-info">{{ __('verify_loan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Loan Modal -->
<div class="modal fade" id="approveLoanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.approve', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('approve_loan_application') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount_approved">{{ __('amount_to_approve_ugx') }}</label>
                                <input type="number" step="0.01" class="form-control" id="amount_approved"
                                       name="amount_approved" value="{{ $loan->amount_applied }}" required
                                       max="{{ $loan->amount_applied }}" min="1">
                                <small class="text-muted">{{ __('maximum_amount') }}: CDF {{ number_format($loan->amount_applied, 2) }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interest_rate">{{ __('interest_rate_percent') }}</label>
                                <input type="number" step="0.1" class="form-control" id="interest_rate"
                                       name="interest_rate" value="12" required min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_months">{{ __('duration_months') }}</label>
                                <input type="number" class="form-control" id="duration_months"
                                       name="duration_months" value="12" required min="1" max="60">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="approval_notes">{{ __('approval_notes') }}</label>
                                <textarea class="form-control" id="approval_notes" name="approval_notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>{{ __('monthly_payment') }}:</strong><br>
                                <span id="estimatedMonthly">CDF 0</span>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('total_interest') }}:</strong><br>
                                <span id="estimatedInterest">CDF 0</span>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('total_repayable') }}:</strong><br>
                                <span id="estimatedTotal">CDF 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('approve_loan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disburse Loan Modal -->
<div class="modal fade" id="disburseLoanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.disburse', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('disburse_loan') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>{{ __('loan_details') }}:</strong><br>
                        {{ __('amount') }}: CDF {{ number_format($loan->amount_approved, 2) }}<br>
                        {{ __('member') }}: {{ $loan->user->name }}<br>
                        {{ __('disbursement_date') }}: {{ now()->format('d M Y') }}
                    </div>

                    <div class="form-group">
                        <label for="disbursement_method">{{ __('disbursement_method') }}</label>
                        <select class="form-control" id="disbursement_method" name="disbursement_method" required>
                            <option value="cash">{{ __('cash') }}</option>
                            <option value="bank_transfer">{{ __('bank_transfer') }}</option>
                            <option value="mobile_money">{{ __('mobile_money') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="disbursement_notes">{{ __('notes') }}</label>
                        <textarea class="form-control" id="disbursement_notes" name="disbursement_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('confirm_disburse') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Loan Modal -->
<div class="modal fade" id="rejectLoanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('reject_loan_application') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">{{ __('rejection_reason') }}</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                  rows="4" required placeholder="{{ __('please_provide_reason_for_rejection') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('reject_loan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark Completed Modal -->
<div class="modal fade" id="markCompletedModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.loans.mark-completed', $loan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('mark_loan_as_completed') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ __('are_you_sure_mark_completed') }}
                    </div>
                    <p>{{ __('this_action_will_close_loan') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('mark_as_completed') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Loan calculator for approve modal
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount_approved');
        const interestInput = document.getElementById('interest_rate');
        const durationInput = document.getElementById('duration_months');

        if (amountInput && interestInput && durationInput) {
            function calculateLoan() {
                const amount = parseFloat(amountInput.value) || 0;
                const rate = parseFloat(interestInput.value) || 0;
                const months = parseInt(durationInput.value) || 1;

                if (amount > 0 && rate > 0) {
                    const monthlyRate = rate / 100 / 12;
                    let monthlyPayment;

                    if (monthlyRate === 0) {
                        monthlyPayment = amount / months;
                    } else {
                        monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
                    }

                    const totalPayment = monthlyPayment * months;
                    const totalInterest = totalPayment - amount;

                    document.getElementById('estimatedMonthly').textContent = 'CDF ' + Math.round(monthlyPayment).toLocaleString();
                    document.getElementById('estimatedInterest').textContent = 'CDF ' + Math.round(totalInterest).toLocaleString();
                    document.getElementById('estimatedTotal').textContent = 'CDF ' + Math.round(totalPayment).toLocaleString();
                }
            }

            amountInput.addEventListener('input', calculateLoan);
            interestInput.addEventListener('input', calculateLoan);
            durationInput.addEventListener('input', calculateLoan);

            // Initial calculation
            calculateLoan();
        }
    });

    // Function to open assign treasurer modal with dynamic action
    function openAssignTreasurerModal(loanId) {
        const form = document.getElementById('assignTreasurerForm');
        form.action = `/admin/loans/${loanId}/assign-treasurer`;

        // Clear previous selection
        const select = document.getElementById('modal_treasurer_id');
        if (select) {
            select.value = '';
        }

        // Show the modal using jQuery
        $('#assignTreasurerModal').modal('show');
    }
</script>
@endpush
