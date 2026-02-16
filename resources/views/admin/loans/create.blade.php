@extends('layouts.app')

@section('title', 'Create Loan Application')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Loan Application</h1>
        <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
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
            <form action="{{ route('admin.loans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Member Selection -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Select Member <span class="text-danger">*</span></label>
                            <select class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id" required>
                                <option value="">Choose Member...</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} ({{ $member->membership_number ?? 'No #' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount_applied">Amount Applied (UGX) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount_applied') is-invalid @enderror"
                                   id="amount_applied" name="amount_applied" value="{{ old('amount_applied') }}"
                                   required min="1">
                            @error('amount_applied')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Loan Purpose -->
                <div class="form-group">
                    <label for="purpose">Loan Purpose <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('purpose') is-invalid @enderror"
                              id="purpose" name="purpose" rows="3" required>{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Loan Calculator with Better UI -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Loan Calculator - Preview Amortization Schedule</h6>
                        <span class="badge badge-light">Preview Only</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preview_interest_rate">Interest Rate (%)</label>
                                    <input type="number" step="0.1" class="form-control"
                                           id="preview_interest_rate" value="12" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preview_duration">Duration (Months)</label>
                                    <input type="number" class="form-control"
                                           id="preview_duration" value="12" min="1" max="60">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-block" id="calculateLoan">
                                        <i class="fas fa-calculator"></i> Calculate Schedule
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Summary Cards -->
                        <div class="row mt-3" id="loanSummary" style="display: none;">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body py-2">
                                        <small>Monthly Payment</small>
                                        <h6 id="monthlyPayment">UGX 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body py-2">
                                        <small>Total Interest</small>
                                        <h6 id="totalInterest">UGX 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body py-2">
                                        <small>Total Payment</small>
                                        <h6 id="totalPayment">UGX 0</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body py-2">
                                        <small>First Payment</small>
                                        <h6 id="firstPaymentDate">{{ now()->addMonth()->format('M Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amortization Table -->
                        <div class="table-responsive mt-3" id="amortizationTable" style="display: none;">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Payment Date</th>
                                        <th>Monthly Payment</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="amortizationBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Collateral Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Collateral Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="has_collateral" name="has_collateral" value="1" {{ old('has_collateral') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="has_collateral">
                                    <strong>Has Collateral</strong>
                                </label>
                            </div>
                        </div>

                        <div id="collateral-fields" style="{{ old('has_collateral') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_type">Collateral Type</label>
                                        <select class="form-control @error('collateral_type') is-invalid @enderror"
                                                id="collateral_type" name="collateral_type">
                                            <option value="">Select Type</option>
                                            <option value="land" {{ old('collateral_type') == 'land' ? 'selected' : '' }}>Land/Property</option>
                                            <option value="vehicle" {{ old('collateral_type') == 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                                            <option value="building" {{ old('collateral_type') == 'building' ? 'selected' : '' }}>Building</option>
                                            <option value="equipment" {{ old('collateral_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                            <option value="savings" {{ old('collateral_type') == 'savings' ? 'selected' : '' }}>Savings</option>
                                            <option value="other" {{ old('collateral_type') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('collateral_type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="collateral_value">Estimated Value (UGX)</label>
                                        <input type="number" step="0.01" class="form-control @error('collateral_value') is-invalid @enderror"
                                               id="collateral_value" name="collateral_value" value="{{ old('collateral_value') }}">
                                        @error('collateral_value')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="collateral_description">Collateral Description</label>
                                        <textarea class="form-control @error('collateral_description') is-invalid @enderror"
                                                  id="collateral_description" name="collateral_description" rows="2" placeholder="Provide detailed description of the collateral">{{ old('collateral_description') }}</textarea>
                                        @error('collateral_description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guarantors Information (External People) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Guarantors Information</h6>
                        <small class="text-muted">Add external guarantors who are not members of the system</small>
                    </div>
                    <div class="card-body">
                        <div id="guarantors-container">
                            <!-- Guarantor Template - First Guarantor -->
                            <div class="guarantor-row border p-3 mb-3 rounded">
                                <div class="row">
                                    <div class="col-md-12 text-right mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-guarantor" style="display: none;">
                                            <i class="fas fa-trash"></i> Remove Guarantor
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][name]"
                                                   placeholder="Enter guarantor full name"
                                                   value="{{ old('guarantors.0.name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][phone]"
                                                   placeholder="Enter phone number"
                                                   value="{{ old('guarantors.0.phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email (Optional)</label>
                                            <input type="email" class="form-control"
                                                   name="guarantors[0][email]"
                                                   placeholder="Enter email address"
                                                   value="{{ old('guarantors.0.email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ID Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][id_number]"
                                                   placeholder="Enter national ID number"
                                                   value="{{ old('guarantors.0.id_number') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Occupation</label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][occupation]"
                                                   placeholder="Enter occupation"
                                                   value="{{ old('guarantors.0.occupation') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea class="form-control"
                                                      name="guarantors[0][address]"
                                                      rows="2" placeholder="Enter physical address">{{ old('guarantors.0.address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Relationship to Member</label>
                                            <input type="text" class="form-control"
                                                   name="guarantors[0][relationship]"
                                                   placeholder="e.g., Brother, Friend, Business Partner"
                                                   value="{{ old('guarantors.0.relationship') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary" id="add-guarantor">
                            <i class="fas fa-plus"></i> Add Another Guarantor
                        </button>
                        <small class="text-muted d-block mt-2">Maximum 3 guarantors allowed. All guarantors must provide valid ID and contact information.</small>
                    </div>
                </div>

                <!-- Documents Section with 15MB Limit -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Supporting Documents</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>File Size Limit:</strong> Maximum 15MB per file. Allowed formats: PDF, JPG, JPEG, PNG
                        </div>

                        <div id="documents-container">
                            <!-- Document Template - First Document -->
                            <div class="document-row border p-3 mb-3 rounded">
                                <div class="row">
                                    <div class="col-md-12 text-right mb-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-document" style="display: none;">
                                            <i class="fas fa-trash"></i> Remove Document
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Document Category <span class="text-danger">*</span></label>
                                            <select class="form-control" name="document_categories[0]" required>
                                                <option value="">Select Category</option>
                                                <option value="guarantor_id">Guarantor ID</option>
                                                <option value="collateral_photo">Collateral Photo</option>
                                                <option value="collateral_document">Collateral Document</option>
                                                <option value="proof_of_income">Proof of Income</option>
                                                <option value="bank_statement">Bank Statement</option>
                                                <option value="application_form">Application Form</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Link to Guarantor (Optional)</label>
                                            <select class="form-control" name="document_guarantor[0]">
                                                <option value="">Not linked to guarantor</option>
                                                @for($i = 0; $i < 3; $i++)
                                                    <option value="guarantor_{{ $i }}">Guarantor {{ $i + 1 }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Upload File <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control-file"
                                                   name="documents[0]"
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   data-max-size="15728640" {{-- 15MB in bytes --}}
                                                   required>
                                            <small class="text-muted">Max 15MB</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description (Optional)</label>
                                            <input type="text" class="form-control"
                                                   name="document_descriptions[0]"
                                                   placeholder="Brief description of the document"
                                                   value="{{ old('document_descriptions.0') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary" id="add-document">
                            <i class="fas fa-plus"></i> Add Another Document
                        </button>
                        <small class="text-muted d-block mt-2">Maximum 10 documents allowed. Upload clear, legible copies.</small>

                        <!-- Document type hints -->
                        <div class="alert alert-light small p-2 mt-3">
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>Recommended documents:</strong>
                            <ul class="mb-0 mt-1 pl-3">
                                <li>Guarantor ID copies (link to specific guarantor)</li>
                                <li>Collateral photos/documents</li>
                                <li>Proof of income (payslips, bank statements)</li>
                                <li>Bank statements (last 3-6 months)</li>
                                <li>Completed application form</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note about Loan Processing:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Interest rate, duration, and monthly payments will be set during loan approval</li>
                        <li>Guarantors will be contacted and verified by the treasurer during the verification process</li>
                        <li>All uploaded documents will be reviewed during verification (15MB max per file)</li>
                        <li>Collateral will be inspected and verified by the assigned treasurer</li>
                        <li>This application will be pending until assigned to a treasurer for verification</li>
                    </ul>
                </div>

                <hr>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Submit Application
                    </button>
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
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

        // Update indices and clear values
        template.querySelectorAll('input, textarea, select').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace(/\[\d+\]/, `[${guarantorCount}]`));
                if (element.tagName === 'SELECT') {
                    element.value = '';
                } else {
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

    // Initialize remove buttons for existing rows
    document.querySelectorAll('.remove-guarantor').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.guarantor-row').length > 1) {
                this.closest('.guarantor-row').remove();
                guarantorCount--;
            }
        });
    });

    // Document management
    let documentCount = 1;
    const maxDocuments = 10;

    document.getElementById('add-document').addEventListener('click', function() {
        if (documentCount >= maxDocuments) {
            alert('Maximum of ' + maxDocuments + ' documents allowed');
            return;
        }

        const container = document.getElementById('documents-container');
        const template = document.querySelector('.document-row').cloneNode(true);

        // Update indices and clear values
        template.querySelectorAll('select, input').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace(/\[\d+\]/, `[${documentCount}]`));
                if (element.tagName === 'INPUT' && element.type === 'file') {
                    element.value = '';
                } else if (element.tagName === 'SELECT') {
                    element.value = '';
                } else {
                    element.value = '';
                }
            }
        });

        // Show remove button
        const removeBtn = template.querySelector('.remove-document');
        removeBtn.style.display = 'inline-block';
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.document-row').length > 1) {
                template.remove();
                documentCount--;
            }
        });

        container.appendChild(template);
        documentCount++;
    });

    // Initialize remove buttons for existing documents
    document.querySelectorAll('.remove-document').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.document-row').length > 1) {
                this.closest('.document-row').remove();
                documentCount--;
            }
        });
    });

    // File size validation (15MB limit)
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.hasAttribute('data-max-size')) {
            const maxSize = parseInt(e.target.getAttribute('data-max-size'));
            if (e.target.files.length > 0) {
                const fileSize = e.target.files[0].size;
                if (fileSize > maxSize) {
                    alert('File size exceeds 15MB limit. Please choose a smaller file.');
                    e.target.value = '';
                }

                // Show file name and size
                const fileRow = e.target.closest('.document-row');
                const sizeInMB = (fileSize / (1024 * 1024)).toFixed(2);
                const fileName = e.target.files[0].name;

                // Create or update file info display
                let infoDiv = fileRow.querySelector('.file-info');
                if (!infoDiv) {
                    infoDiv = document.createElement('div');
                    infoDiv.className = 'file-info small text-muted mt-1';
                    e.target.closest('.form-group').appendChild(infoDiv);
                }
                infoDiv.innerHTML = `<i class="fas fa-file"></i> ${fileName} (${sizeInMB} MB)`;
            }
        }
    });

    // Loan Calculator Function with real-time validation
    document.getElementById('calculateLoan').addEventListener('click', function() {
        const amount = parseFloat(document.getElementById('amount_applied').value);
        const interestRate = parseFloat(document.getElementById('preview_interest_rate').value);
        const months = parseInt(document.getElementById('preview_duration').value);

        if (!amount || amount <= 0) {
            alert('Please enter a valid loan amount first');
            document.getElementById('amount_applied').focus();
            return;
        }

        if (interestRate <= 0) {
            alert('Please enter a valid interest rate');
            return;
        }

        if (months < 1) {
            alert('Please enter a valid duration');
            return;
        }

        // Calculate monthly payment using standard loan amortization formula
        const monthlyRate = interestRate / 100 / 12;
        let monthlyPayment;

        if (monthlyRate === 0) {
            monthlyPayment = amount / months;
        } else {
            monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        }

        const totalPayment = monthlyPayment * months;
        const totalInterest = totalPayment - amount;

        // Display summary with formatted numbers
        document.getElementById('monthlyPayment').textContent = 'UGX ' + Math.round(monthlyPayment).toLocaleString();
        document.getElementById('totalInterest').textContent = 'UGX ' + Math.round(totalInterest).toLocaleString();
        document.getElementById('totalPayment').textContent = 'UGX ' + Math.round(totalPayment).toLocaleString();

        document.getElementById('loanSummary').style.display = 'flex';

        // Generate amortization schedule
        let balance = amount;
        let schedule = '';
        let paymentDate = new Date();

        for (let i = 1; i <= months; i++) {
            const interestPayment = balance * monthlyRate;
            const principalPayment = monthlyPayment - interestPayment;
            balance -= principalPayment;

            // Format date - increment month for each payment
            const currentDate = new Date(paymentDate);
            currentDate.setMonth(currentDate.getMonth() + i);
            const dateStr = currentDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });

            schedule += `<tr>
                <td>${i}</td>
                <td>${dateStr}</td>
                <td>UGX ${Math.round(monthlyPayment).toLocaleString()}</td>
                <td>UGX ${Math.round(principalPayment).toLocaleString()}</td>
                <td>UGX ${Math.round(interestPayment).toLocaleString()}</td>
                <td>UGX ${Math.max(0, Math.round(balance)).toLocaleString()}</td>
            </tr>`;
        }

        document.getElementById('amortizationBody').innerHTML = schedule;
        document.getElementById('amortizationTable').style.display = 'block';
    });

    // Optional: Auto-calculate when amount changes (if needed)
    document.getElementById('amount_applied').addEventListener('change', function() {
        if (this.value > 0) {
            // You could auto-trigger calculation here if desired
        }
    });
</script>
@endpush


