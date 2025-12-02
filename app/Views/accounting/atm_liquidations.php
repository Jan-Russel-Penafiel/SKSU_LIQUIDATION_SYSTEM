<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Approved ATM Liquidations</h1>
        <p class="text-gray-600">ATM liquidation batches approved by chairman</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="<?= base_url('accounting') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Liquidations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Approved ATM Liquidation Batches</h3>
        <p class="text-green-100 text-sm">Ready for accounting processing</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Batch Details</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Upload Info</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Records</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Period</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Approval Date</th>
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
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['batch_name']) ?></div>
                                    <div class="text-sm text-gray-500">
                                        <?= esc($liquidation['file_type']) ?> file
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['uploader_name']) ?></div>
                                    <div class="text-sm text-gray-500">
                                        <i class="bi bi-calendar mr-1"></i>
                                        <?= date('M j, Y g:i A', strtotime($liquidation['created_at'])) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900"><?= number_format($liquidation['total_records']) ?></div>
                                    <div class="text-sm text-gray-500">total records</div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['semester']) ?></div>
                                    <div class="text-sm text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <?php if ($liquidation['chairman_approval_date']): ?>
                                        <div class="font-medium text-gray-900">
                                            <?= date('M j, Y', strtotime($liquidation['chairman_approval_date'])) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= date('g:i A', strtotime($liquidation['chairman_approval_date'])) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400">N/A</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="status-approved">Approved</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?= base_url('accounting/atm/' . $liquidation['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors"
                                       title="View Details">
                                        <i class="bi bi-eye mr-1"></i>
                                        View
                                    </a>
                                    <button type="button" 
                                            onclick="receiveModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['batch_name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors"
                                            title="Receive for Processing">
                                        <i class="bi bi-inbox mr-1"></i>
                                        Receive
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
                                    <p class="text-lg font-medium mb-2">No approved liquidations</p>
                                    <p class="text-sm">No ATM liquidations have been approved yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                    <p class="text-gray-600 text-sm">Receive this batch for accounting processing</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700">Batch: <span id="receiveBatchName" class="font-medium"></span></p>
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
                    Receive Batch
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentLiquidationId = null;

function receiveModal(id, batchName) {
    currentLiquidationId = id;
    document.getElementById('receiveBatchName').textContent = batchName;
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
            remarks: remarks
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                closeReceiveModal();
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while receiving the liquidation.');
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

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-black')) {
        closeReceiveModal();
    }
}
</script>
<?= $this->endSection() ?>