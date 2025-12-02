<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Review Batch ATM Liquidation</h1>
        <p class="text-gray-600">Review batch details and all recipients before approval</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('chairman/pending-atm') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>
</div>

<!-- Batch Information -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Batch Information</h3>
        <p class="text-blue-100 text-sm">Details of the batch upload</p>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Batch ID</label>
                <p class="text-gray-900 font-semibold">#<?= esc($batch['id']) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Batch Name</label>
                <p class="text-gray-900 font-semibold"><?= esc($batch['batch_name']) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Status</label>
                <span class="status-<?= str_replace('_', '-', $batch['status']) ?>"><?= esc(ucwords(str_replace('_', ' ', $batch['status']))) ?></span>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">File Type</label>
                <p class="text-gray-900">
                    <i class="bi bi-file-earmark-<?= isset($batch['file_type']) && $batch['file_type'] === 'pdf' ? 'pdf' : 'text' ?>"></i>
                    <?= isset($batch['file_type']) ? strtoupper(esc($batch['file_type'])) : 'N/A' ?>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Semester</label>
                <p class="text-gray-900"><?= isset($batch['semester']) ? esc($batch['semester']) : 'N/A' ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Academic Year</label>
                <p class="text-gray-900"><?= isset($batch['academic_year']) ? esc($batch['academic_year']) : 'N/A' ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Total Recipients</label>
                <p class="text-gray-900 font-semibold"><?= count($details) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Total Amount</label>
                <p class="text-green-600 font-bold text-lg">₱<?= number_format($totalAmount, 2) ?></p>
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-500 block mb-1">Uploaded On</label>
                <p class="text-gray-900"><?= isset($batch['created_at']) ? date('M j, Y g:i A', strtotime($batch['created_at'])) : 'N/A' ?></p>
            </div>
        </div>
        
        <?php if (isset($batch['remarks']) && !empty($batch['remarks'])): ?>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <label class="text-sm font-medium text-gray-500 block mb-2">Remarks</label>
            <p class="text-gray-700 bg-gray-50 rounded-lg p-4"><?= esc($batch['remarks']) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (isset($batch['file_path']) && !empty($batch['file_path'])): ?>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="<?= base_url('atm-liquidation/download-batch-file/' . $batch['id']) ?>" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                <i class="bi bi-download mr-2"></i>
                Download Original File
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recipients Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Recipients in this Batch</h3>
        <p class="text-green-100 text-sm">All scholarship recipients included in this batch</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">#</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Recipient</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Campus</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Transaction Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Reference Number</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($details)): ?>
                        <?php foreach ($details as $index => $detail): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-600"><?= $index + 1 ?></td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-900"><?= esc($detail['recipient_name']) ?></div>
                                <div class="text-sm text-gray-500">ID: <?= esc($detail['recipient_code']) ?></div>
                            </td>
                            <td class="py-3 px-4 text-gray-700"><?= esc($detail['campus']) ?></td>
                            <td class="py-3 px-4 text-sm text-gray-700"><?= date('M j, Y', strtotime($detail['transaction_date'])) ?></td>
                            <td class="py-3 px-4 text-sm text-gray-600"><?= esc($detail['reference_number']) ?></td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">₱<?= number_format($detail['amount'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                                <i class="bi bi-inbox text-4xl mb-3"></i>
                                <p>No recipients found in this batch</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="5" class="py-4 px-4 text-right text-gray-700">Total Amount:</td>
                        <td class="py-4 px-4 text-right text-green-600 text-lg">₱<?= number_format($totalAmount, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<?php if (isset($batch['status']) && $batch['status'] === 'verified'): ?>
<div class="flex justify-end space-x-3 mt-6">
    <button type="button" 
            onclick="rejectBatch()"
            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
        <i class="bi bi-x-circle mr-2"></i>
        Reject Batch
    </button>
    <button type="button" 
            onclick="approveBatch()"
            class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
        <i class="bi bi-check-circle mr-2"></i>
        Approve Batch
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
                    <h3 class="text-lg font-semibold text-gray-900">Approve Batch</h3>
                    <p class="text-gray-600 text-sm">This will approve all recipients in this batch</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">You are about to approve <span class="font-bold"><?= count($details) ?> recipients</span> with a total amount of <span class="font-bold text-green-600">₱<?= number_format($totalAmount, 2) ?></span></p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Reject Batch</h3>
                    <p class="text-gray-600 text-sm">Please provide a reason for rejection</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">This will reject all <span class="font-bold"><?= count($details) ?> recipients</span> in this batch</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea id="rejectRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Explain why this batch is being rejected..." required></textarea>
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
function approveBatch() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function confirmApprove() {
    const remarks = document.getElementById('approveRemarks').value;
    
    $.ajax({
        url: '<?= base_url('chairman/approve-atm-batch') ?>',
        method: 'POST',
        data: {
            id: <?= $batch['id'] ?>,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                window.location.href = '<?= base_url('chairman/pending-atm') ?>';
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('An error occurred while approving the batch.');
        }
    });
}

function rejectBatch() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function confirmReject() {
    const remarks = document.getElementById('rejectRemarks').value;
    
    if (!remarks.trim()) {
        alert('Please provide a reason for rejection.');
        return;
    }
    
    $.ajax({
        url: '<?= base_url('chairman/reject-atm-batch') ?>',
        method: 'POST',
        data: {
            id: <?= $batch['id'] ?>,
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                window.location.href = '<?= base_url('chairman/pending-atm') ?>';
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('An error occurred while rejecting the batch.');
        }
    });
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
