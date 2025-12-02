<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-green-600 transition-colors">
                    <i class="bi bi-house-door mr-2"></i>Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?= base_url('atm-liquidation') ?>" class="text-gray-500 hover:text-green-600 ml-1 md:ml-2 transition-colors">ATM Liquidations</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 ml-1 md:ml-2 font-medium">Batch Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-lg">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <h1 class="text-xl font-semibold text-white flex items-center">
                    <i class="bi bi-credit-card mr-2"></i>
                    ATM Liquidation Details
                </h1>
                <div class="flex items-center mt-2 sm:mt-0 space-x-2">
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full status-<?= esc($liquidation['status']) ?>">
                        <?= esc(ucwords(str_replace('_', ' ', $liquidation['status']))) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Liquidation Information -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Liquidation ID</h3>
                    <p class="text-lg font-semibold text-gray-900">#<?= esc($liquidation['id']) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Recipient</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['recipient_name']) ?></p>
                    <p class="text-sm text-gray-600"><?= esc($liquidation['recipient_code']) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Campus</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['campus']) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Transaction Date</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= date('F j, Y', strtotime($liquidation['transaction_date'])) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Amount</h3>
                    <p class="text-lg font-semibold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Reference Number</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['reference_number']) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Created Date</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= date('F j, Y', strtotime($liquidation['created_at'])) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Academic Year</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['academic_year']) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Semester</h3>
                    <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['semester']) ?></p>
                </div>
            </div>

            <?php if (!empty($liquidation['remarks'])): ?>
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Remarks</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800"><?= esc($liquidation['remarks']) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($liquidation['file_path'])): ?>
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Attached File</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="bi bi-file-earmark-<?= $liquidation['file_type'] === 'csv' ? 'text' : ($liquidation['file_type'] === 'pdf' ? 'pdf' : 'excel') ?> text-3xl text-green-600 mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-900"><?= basename($liquidation['file_path']) ?></p>
                                <p class="text-sm text-gray-600">File Type: <?= strtoupper($liquidation['file_type']) ?></p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <?php if (in_array($liquidation['file_type'], ['csv', 'excel'])): ?>
                            <a href="<?= base_url('atm-liquidation/view-file/' . $liquidation['id']) ?>" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bi bi-eye mr-2"></i>View
                            </a>
                            <?php endif; ?>
                            <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="bi bi-download mr-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex flex-wrap gap-3 justify-center sm:justify-start">
        <a href="<?= base_url('atm-liquidation') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
            <i class="bi bi-arrow-left mr-2"></i>Back to List
        </a>
        
        <?php if (!empty($liquidation['file_path'])): ?>
            <?php if (in_array($liquidation['file_type'], ['csv', 'excel'])): ?>
            <a href="<?= base_url('atm-liquidation/view-file/' . $liquidation['id']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <i class="bi bi-eye mr-2"></i>View File
            </a>
            <?php endif; ?>
            
            <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <i class="bi bi-download mr-2"></i>Download File
            </a>
        <?php endif; ?>
        
        <?php if ($liquidation['status'] === 'pending' && in_array($user['role'], ['disbursing_officer', 'admin'])): ?>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center" onclick="verifyLiquidation(<?= $liquidation['id'] ?>)">
            <i class="bi bi-check-square mr-2"></i>Verify
        </button>
        <?php endif; ?>
        
        <?php if ($liquidation['status'] === 'verified' && $user['role'] === 'scholarship_chairman'): ?>
        <div class="flex gap-2">
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center" onclick="approveLiquidation(<?= $liquidation['id'] ?>)">
                <i class="bi bi-check-circle mr-2"></i>Approve
            </button>
            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center" onclick="rejectLiquidation(<?= $liquidation['id'] ?>)">
                <i class="bi bi-x-circle mr-2"></i>Reject
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Status badge styles */
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-verified {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .status-approved {
        background-color: #dcfce7;
        color: #166534;
    }
    .status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function verifyLiquidation(liquidationId) {
    if (confirm('Are you sure you want to verify this ATM liquidation?')) {
        window.location.href = '<?= base_url('atm-liquidation/verify/') ?>' + liquidationId;
    }
}

function approveLiquidation(liquidationId) {
    if (confirm('Are you sure you want to approve this ATM liquidation?')) {
        window.location.href = '<?= base_url('atm-liquidation/approve/') ?>' + liquidationId;
    }
}

function rejectLiquidation(liquidationId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason && reason.trim()) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('atm-liquidation/reject/') ?>' + liquidationId;
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'remarks';
        reasonInput.value = reason;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(reasonInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>