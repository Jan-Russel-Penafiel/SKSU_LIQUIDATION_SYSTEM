<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pending Manual Liquidations</h1>
        <p class="text-gray-600">Review and approve manual liquidation entries</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('chairman') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Liquidations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Manual Liquidation Entries</h3>
        <p class="text-blue-100 text-sm">Individual entries awaiting chairman approval</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Recipient</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Voucher Info</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Amount</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Campus</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Period</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($liquidations)): ?>
                        <?php foreach ($liquidations as $liquidation): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900">
                                        <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: <?= esc($liquidation['recipient_code']) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['voucher_number']) ?></div>
                                    <div class="text-sm text-gray-500">
                                        <i class="bi bi-calendar mr-1"></i>
                                        <?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-lg font-semibold text-gray-900">
                                    PHP <?= number_format($liquidation['amount'], 2) ?>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900"><?= esc($liquidation['campus']) ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['semester']) ?></div>
                                    <div class="text-sm text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="status-verified">For Approval</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?= base_url('chairman/manual/' . $liquidation['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors"
                                       title="Review Details">
                                        <i class="bi bi-eye mr-1"></i>
                                        Review
                                    </a>
                                    <button type="button" 
                                            onclick="approveModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors"
                                            title="Approve">
                                        <i class="bi bi-check mr-1"></i>
                                        Approve
                                    </button>
                                    <button type="button" 
                                            onclick="rejectModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors"
                                            title="Reject">
                                        <i class="bi bi-x mr-1"></i>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="bi bi-inbox text-4xl mb-4"></i>
                                    <p class="text-lg font-medium mb-2">No pending liquidations</p>
                                    <p class="text-sm">All manual liquidations have been reviewed</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                    <p class="text-gray-600 text-sm">This action will approve the liquidation entry</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">Recipient: <span id="approveRecipientName" class="font-medium"></span></p>
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
                    Approve Entry
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
                <p class="text-sm text-gray-700">Recipient: <span id="rejectRecipientName" class="font-medium"></span></p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea id="rejectRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Explain why this entry is being rejected..." required></textarea>
                <p class="text-xs text-gray-500 mt-1">A reason is required for rejection</p>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmReject()" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors">
                    Reject Entry
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
    document.getElementById('approveRecipientName').textContent = recipientName;
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
        url: '<?= base_url('chairman/approve-manual') ?>',
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
                setTimeout(() => location.reload(), 1000);
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
    document.getElementById('rejectRecipientName').textContent = recipientName;
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
        url: '<?= base_url('chairman/reject-manual') ?>',
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
                setTimeout(() => location.reload(), 1000);
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