@extends('layouts.app')

@section('title', 'Apply for Loan')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Apply for Loan</h1>
        <a href="{{ route('member.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loan Application Form</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('member.loans.store') }}" enctype="multipart/form-data" id="loanForm">
                @csrf

                <!-- Loan Details -->
                <h5 class="border-bottom pb-2 mb-4">Loan Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount_applied">Loan Amount (UGX) <span class="text-danger">*</span></label>
                        <input type="number" step="1000" class="form-control" id="amount_applied"
                               name="amount_applied" value="{{ old('amount_applied') }}" required min="10000">
                        <small class="text-muted">Minimum: UGX 10,000 | Maximum: UGX 10,000,000</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="purpose">Loan Purpose <span class="text-danger">*</span></label>
                        <select class="form-control" id="purpose" name="purpose" required>
                            <option value="">Select Purpose</option>
                            <option value="Business">Business</option>
                            <option value="Education">Education</option>
                            <option value="Medical">Medical</option>
                            <option value="Agriculture">Agriculture</option>
                            <option value="Home Improvement">Home Improvement</option>
                            <option value="Debt Consolidation">Debt Consolidation</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Collateral Information -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">Collateral Information</h5>
                <div class="form-group mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="has_collateral" name="has_collateral" value="1">
                        <label class="custom-control-label" for="has_collateral">
                            I have collateral to secure this loan
                        </label>
                    </div>
                </div>

                <div id="collateral-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="collateral_type">Collateral Type</label>
                            <select class="form-control" id="collateral_type" name="collateral_type">
                                <option value="">Select Type</option>
                                <option value="land">Land</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="building">Building</option>
                                <option value="equipment">Equipment</option>
                                <option value="savings">Savings</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="collateral_value">Estimated Value (UGX)</label>
                            <input type="number" step="0.01" class="form-control" id="collateral_value" name="collateral_value">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="collateral_description">Collateral Description</label>
                            <textarea class="form-control" id="collateral_description" name="collateral_description" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Guarantors -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">Guarantors <span class="text-danger">*</span></h5>
                <p class="text-muted small">You need at least 1 guarantor, maximum 3.</p>

                <div id="guarantors-container">
                    <!-- Guarantor 1 -->
                    <div class="guarantor-row border p-3 mb-3 rounded">
                        <div class="row">
                            <div class="col-md-12 text-right mb-2">
                                <button type="button" class="btn btn-danger btn-sm remove-guarantor" style="display: none;">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="guarantors[0][name]" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="guarantors[0][phone]" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Email (Optional)</label>
                                <input type="email" class="form-control" name="guarantors[0][email]">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>ID Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="guarantors[0][id_number]" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Occupation</label>
                                <input type="text" class="form-control" name="guarantors[0][occupation]">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea class="form-control" name="guarantors[0][address]" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Relationship to Applicant</label>
                                <input type="text" class="form-control" name="guarantors[0][relationship]">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-primary" id="add-guarantor">
                    <i class="fas fa-plus"></i> Add Another Guarantor
                </button>

                <!-- Documents -->
                <h5 class="border-bottom pb-2 mb-4 mt-4">Supporting Documents</h5>
                <div class="form-group">
                    <label for="documents">Upload Documents (Optional)</label>
                    <input type="file" class="form-control-file" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Max 5 files, 5MB each. Allowed: PDF, JPG, JPEG, PNG</small>
                </div>

                <hr class="mt-4">

                <div class="text-right">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loan Calculator Card -->
    <div class="card shadow mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Loan Calculator</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="calc_amount">Loan Amount</label>
                    <input type="number" class="form-control" id="calc_amount" placeholder="Enter amount">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="calc_rate">Interest Rate (%)</label>
                    <input type="number" step="0.1" class="form-control" id="calc_rate" value="12">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="calc_months">Duration (Months)</label>
                    <input type="number" class="form-control" id="calc_months" value="12">
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="calculateBtn">
                <i class="fas fa-calculator"></i> Calculate
            </button>

            <div class="row mt-3" id="calcResults" style="display: none;">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <small>Monthly Payment</small>
                            <h6 id="calcMonthly">UGX 0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <small>Total Interest</small>
                            <h6 id="calcInterest">UGX 0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <small>Total Payment</small>
                            <h6 id="calcTotal">UGX 0</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle collateral fields
    document.getElementById('has_collateral').addEventListener('change', function() {
        document.getElementById('collateral-fields').style.display = this.checked ? 'block' : 'none';
    });

    // Guarantor management
    let guarantorCount = 1;
    const maxGuarantors = 3;

    document.getElementById('add-guarantor').addEventListener('click', function() {
        if (guarantorCount >= maxGuarantors) {
            alert('Maximum of ' + maxGuarantors + ' guarantors allowed');
            return;
        }

        const container = document.getElementById('guarantors-container');
        const template = document.querySelector('.guarantor-row').cloneNode(true);

        // Clear values and update indices
        template.querySelectorAll('input, textarea, select').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace(/\[\d+\]/, `[${guarantorCount}]`));
                if (element.tagName !== 'SELECT') {
                    element.value = '';
                }
            }
        });

        // Show remove button
        const removeBtn = template.querySelector('.remove-guarantor');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                template.remove();
                guarantorCount--;
            }
        });

        container.appendChild(template);
        guarantorCount++;
    });

    // Initialize remove buttons
    document.querySelectorAll('.remove-guarantor').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                this.closest('.guarantor-row').remove();
                guarantorCount--;
            }
        });
    });

    // Loan calculator
    document.getElementById('calculateBtn').addEventListener('click', function() {
        const amount = parseFloat(document.getElementById('calc_amount').value);
        const rate = parseFloat(document.getElementById('calc_rate').value);
        const months = parseInt(document.getElementById('calc_months').value);

        if (!amount || amount <= 0) {
            alert('Please enter a valid loan amount');
            return;
        }

        const monthlyRate = rate / 100 / 12;
        let monthlyPayment;

        if (monthlyRate === 0) {
            monthlyPayment = amount / months;
        } else {
            monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        }

        const totalPayment = monthlyPayment * months;
        const totalInterest = totalPayment - amount;

        document.getElementById('calcMonthly').textContent = 'UGX ' + Math.round(monthlyPayment).toLocaleString();
        document.getElementById('calcInterest').textContent = 'UGX ' + Math.round(totalInterest).toLocaleString();
        document.getElementById('calcTotal').textContent = 'UGX ' + Math.round(totalPayment).toLocaleString();

        document.getElementById('calcResults').style.display = 'flex';
    });

    function resetForm() {
        if (confirm('Reset all form fields?')) {
            document.getElementById('loanForm').reset();
            document.getElementById('collateral-fields').style.display = 'none';
            document.getElementById('calcResults').style.display = 'none';

            // Reset to single guarantor
            const container = document.getElementById('guarantors-container');
            while (container.children.length > 1) {
                container.removeChild(container.lastChild);
            }
            guarantorCount = 1;
        }
    }
</script>
@endpush
