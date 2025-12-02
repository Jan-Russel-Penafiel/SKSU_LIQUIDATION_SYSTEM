<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">ATM Liquidation Details</h1>
        <p class="text-gray-600">Detailed view of ATM liquidation record</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('accounting/atm-liquidations') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>
</div>

<!-- Liquidation Details -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
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
                <p class="text-gray-900"><?= esc($liquidation['reference_number'] ?? 'N/A') ?></p>
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
            
            <?php if (!empty($liquidation['approved_at'])): ?>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Approved Date</label>
                <p class="text-gray-900"><?= date('M j, Y g:i A', strtotime($liquidation['approved_at'])) ?></p>
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

<!-- Action Buttons (if approved) -->
<?php if ($liquidation['status'] === 'approved'): ?>
<div class="flex items-center justify-center">
    <button type="button" 
            onclick="receiveModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>')"
            class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
        <i class="bi bi-inbox mr-2"></i>
        Receive for Processing
    </button>
</div>
<?php endif; ?>

<!-- Receive Modal -->
<div id="receiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="bi bi-inbox text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Receive Liquidation</h3>
                    <p class="text-gray-600 text-sm">Receive this liquidation for accounting processing</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">Recipient: <span id="receiveBatchName" class="font-medium"></span></p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Processing Notes (Optional)</label>
                <textarea id="receiveRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any processing notes..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeReceiveModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmReceive()" class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                    Receive
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentLiquidationId = null;

function receiveModal(id, recipientName) {
    currentLiquidationId = id;
    document.getElementById('receiveBatchName').textContent = recipientName;
    document.getElementById('receiveRemarks').value = '';
    document.getElementById('receiveModal').classList.remove('hidden');
}

function closeReceiveModal() {
    document.getElementById('receiveModal').classList.add('hidden');
    currentLiquidationId = null;
}

function confirmReceive() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('receiveRemarks').value;
    
    $.ajax({
        url: '<?= base_url('accounting/receive-atm') ?>',
        method: 'POST',
        data: {
            id: currentLiquidationId,
            remarks: remarks,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                closeReceiveModal();
                alert(response.message);
                window.location.href = '<?= base_url('accounting/atm-liquidations') ?>';
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('An error occurred while receiving the liquidation.');
        }
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-black')) {
        closeReceiveModal();
    }
}
</script>
<?= $this->endSection() ?>
