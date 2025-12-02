<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">ATM Liquidation Details</h1>
        <p class="text-gray-600">Detailed view of ATM liquidation record</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('chairman/pending-atm') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>
</div>

<!-- Liquidation Details -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Liquidation Information</h3>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Recipient Name</label>
                <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['recipient_name']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Recipient ID</label>
                <p class="text-gray-900"><?= esc($liquidation['recipient_code']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Campus</label>
                <p class="text-gray-900"><?= esc($liquidation['campus']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Transaction Date</label>
                <p class="text-lg font-semibold text-gray-900"><?= date('M j, Y', strtotime($liquidation['transaction_date'])) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Amount</label>
                <p class="text-lg font-semibold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Reference Number</label>
                <p class="text-gray-900"><?= esc($liquidation['reference_number']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Semester</label>
                <p class="text-gray-900"><?= esc($liquidation['semester']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Academic Year</label>
                <p class="text-gray-900"><?= esc($liquidation['academic_year']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                <span class="status-<?= str_replace('_', '-', $liquidation['status']) ?>"><?= esc(ucwords(str_replace('_', ' ', $liquidation['status']))) ?></span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Created Date</label>
                <p class="text-gray-900"><?= date('M j, Y g:i A', strtotime($liquidation['created_at'])) ?></p>
            </div>
            
            <?php if (!empty($liquidation['created_by_name'])): ?>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Created By</label>
                <p class="text-gray-900"><?= esc($liquidation['created_by_name']) ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($liquidation['file_type'])): ?>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Attached File</label>
                <div class="flex items-center space-x-2">
                    <i class="bi bi-file-earmark-<?= $liquidation['file_type'] === 'pdf' ? 'pdf' : 'text' ?> text-lg text-gray-600"></i>
                    <span class="text-gray-900"><?= strtoupper(esc($liquidation['file_type'])) ?> File</span>
                    <?php if (!empty($liquidation['file_path']) && file_exists($liquidation['file_path'])): ?>
                    <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="bi bi-download"></i> Download
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($liquidation['remarks'])): ?>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-600 mb-2">Remarks</label>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-900"><?= esc($liquidation['remarks']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Action Buttons -->
<?php if ($liquidation['status'] === 'verified'): ?>
<div class="flex items-center justify-center space-x-4">
    <button type="button" 
            onclick="approveModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>')"
            class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
        <i class="bi bi-check-circle mr-2"></i>
        Approve Liquidation
    </button>
    
    <button type="button" 
            onclick="rejectModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>')"
            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
        <i class="bi bi-x-circle mr-2"></i>
        Reject Liquidation
    </button>
</div>
<?php endif; ?>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Approve Liquidation</h3>
                    <p class="text-gray-600 text-sm">This action will approve the liquidation</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">Recipient: <span id="approveBatchName" class="font-medium"></span></p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Remarks (Optional)</label>
                <textarea id="approveRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any comments or notes..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmApprove()" class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="bi bi-x-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Reject Liquidation</h3>
                    <p class="text-gray-600 text-sm">Please provide a reason for rejection</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">Recipient: <span id="rejectBatchName" class="font-medium"></span></p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea id="rejectRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Explain why this liquidation is being rejected..." required></textarea>
                <p class="text-xs text-gray-500 mt-1">A reason is required for rejection</p>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmReject()" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
                    Reject
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentLiquidationId = null;

function approveModal(id, recipientName) {
    currentLiquidationId = id;
    document.getElementById('approveBatchName').textContent = recipientName;
    document.getElementById('approveRemarks').value = '';
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    currentLiquidationId = null;
}

function confirmApprove() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('approveRemarks').value;
    
    $.ajax({
        url: '<?= base_url('chairman/approve-atm') ?>',
        method: 'POST',
        data: {
            id: currentLiquidationId,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                closeApproveModal();
                showAlert('success', response.message);
                setTimeout(() => window.location.href = '<?= base_url('chairman/pending-atm') ?>', 1000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while approving the liquidation.');
        }
    });
}

function rejectModal(id, recipientName) {
    currentLiquidationId = id;
    document.getElementById('rejectBatchName').textContent = recipientName;
    document.getElementById('rejectRemarks').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    currentLiquidationId = null;
}

function confirmReject() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('rejectRemarks').value;
    
    if (!remarks.trim()) {
        showAlert('error', 'Please provide a reason for rejection.');
        return;
    }
    
    $.ajax({
        url: '<?= base_url('chairman/reject-atm') ?>',
        method: 'POST',
        data: {
            id: currentLiquidationId,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                closeRejectModal();
                showAlert('success', response.message);
                setTimeout(() => window.location.href = '<?= base_url('chairman/pending-atm') ?>', 1000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while rejecting the liquidation.');
        }
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const iconClass = type === 'success' ? 'bi-check-circle-fill text-green-600' : 'bi-exclamation-triangle-fill text-red-600';
    
    const alertHtml = `
        <div class="${alertClass} border rounded-xl px-4 py-3 mb-6 flex items-center">
            <i class="bi ${iconClass} mr-3 text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    const container = document.querySelector('.flex.items-center.justify-between.mb-8').nextElementSibling;
    container.insertAdjacentHTML('beforebegin', alertHtml);
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-black')) {
        closeApproveModal();
        closeRejectModal();
    }
}
</script>
<?= $this->endSection() ?>
