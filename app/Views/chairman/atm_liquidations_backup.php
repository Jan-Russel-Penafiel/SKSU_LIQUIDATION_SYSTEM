<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pending ATM Liquidations</h1>
        <p class="text-gray-600">Review and approve ATM liquidation records awaiting chairman approval</p>
    </div>
    <div class="flex items-center space-x-4">
        <span class="bg-orange-100 text-orange-800 px-4 py-2 rounded-lg font-semibold">
            <?= count($liquidations) ?> Pending
        </span>
        <a href="<?= base_url('chairman') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pending ATM Liquidations</h1>
        <p class="text-gray-600">Review and approve ATM liquidation records awaiting chairman approval</p>
    </div>
    <div class="flex items-center space-x-4">
        <span class="bg-orange-100 text-orange-800 px-4 py-2 rounded-lg font-semibold">
            <?= count($liquidations) + count($batches) ?> Pending
        </span>
        <a href="<?= base_url('chairman') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Tabs -->
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button onclick="switchTab('individual')" id="tab-individual" class="tab-button py-4 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600">
                <i class="bi bi-person mr-2"></i>
                Individual Records
                <span class="ml-2 bg-orange-100 text-orange-600 py-0.5 px-2.5 rounded-full text-xs font-semibold"><?= count($liquidations) ?></span>
            </button>
            <button onclick="switchTab('batch')" id="tab-batch" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="bi bi-file-earmark-spreadsheet mr-2"></i>
                Batch Records
                <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs font-semibold"><?= count($batches) ?></span>
            </button>
        </nav>
    </div>
</div>

<!-- Individual Liquidations Table -->
<div id="individual-content" class="tab-content bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">ATM Liquidation Records</h3>
        <p class="text-orange-100 text-sm">Individual liquidation records awaiting chairman approval</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Recipient</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Transaction Info</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Amount</th>
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
                                <div class="font-medium text-gray-900"><?= esc($liquidation['recipient_name']) ?></div>
                                <div class="text-sm text-gray-500">ID: <?= esc($liquidation['recipient_code']) ?></div>
                                <div class="text-xs text-gray-400"><?= esc($liquidation['campus']) ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-900">Date: <?= date('M j, Y', strtotime($liquidation['transaction_date'])) ?></div>
                                <div class="text-xs text-gray-500">Ref: <?= esc($liquidation['reference_number']) ?></div>
                                <?php if (!empty($liquidation['file_type'])): ?>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="bi bi-file-earmark-<?= $liquidation['file_type'] === 'pdf' ? 'pdf' : 'text' ?>"></i>
                                    <?= strtoupper(esc($liquidation['file_type'])) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-900"><?= esc($liquidation['semester']) ?></div>
                                <div class="text-xs text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="status-<?= str_replace('_', '-', $liquidation['status']) ?>"><?= esc(ucwords(str_replace('_', ' ', $liquidation['status']))) ?></span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?= base_url('chairman/atm/' . $liquidation['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors" 
                                       title="View Details">
                                        <i class="bi bi-eye mr-1"></i>
                                        View
                                    </a>
                                    <button type="button" 
                                            onclick="approveModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition-colors"
                                            title="Approve">
                                        <i class="bi bi-check-circle mr-1"></i>
                                        Approve
                                    </button>
                                    <button type="button" 
                                            onclick="rejectModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors"
                                            title="Reject">
                                        <i class="bi bi-x-circle mr-1"></i>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                                <i class="bi bi-inbox text-4xl mb-3"></i>
                                <p>No pending ATM liquidations</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Batch Liquidations Table -->
<div id="content-batch" class="tab-content hidden bg-white rounded-b-2xl shadow-lg border border-t-0 border-orange-200/20">
    <div class="p-6 border-b border-gray-200">
        <h5 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="bi bi-file-earmark-spreadsheet text-orange-600 mr-3"></i>
            Batch ATM Liquidations
            <span class="bg-orange-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($batches ?? []) ?> batches</span>
        </h5>
    </div>
    <div class="p-6">
        <?php if (!empty($batches)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table-batch">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Batch ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Batch Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Recipients</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Semester / Year</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Uploaded By</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($batches as $batch): ?>
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                #<?= esc($batch['id']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900"><?= esc($batch['batch_name']) ?></div>
                            <?php if (!empty($batch['remarks'])): ?>
                            <div class="text-xs text-gray-500 mt-1 max-w-xs truncate"><?= esc($batch['remarks']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900"><?= esc($batch['recipient_count']) ?> recipients</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">₱<?= number_format($batch['total_amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($batch['semester']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($batch['academic_year']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($batch['uploader_name']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            $statusClass = '';
                            $statusText = ucfirst(str_replace('_', ' ', $batch['status']));
                            switch ($batch['status']) {
                                case 'uploaded':
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    break;
                                case 'processing':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'verified':
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    break;
                                case 'approved':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'rejected':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                            }
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= esc($statusText) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($batch['created_at'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('chairman/atm-batch/' . $batch['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors" 
                                   title="View Batch Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <button type="button" 
                                        onclick="approveBatchModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_name']) ?>')"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-full hover:bg-green-200 transition-colors"
                                        title="Approve Batch">
                                    <i class="bi bi-check-circle text-sm"></i>
                                </button>
                                <button type="button" 
                                        onclick="rejectBatchModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_name']) ?>')"
                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-full hover:bg-red-200 transition-colors"
                                        title="Reject Batch">
                                    <i class="bi bi-x-circle text-sm"></i>
                                </button>
                                <?php if (!empty($batch['file_path'])): ?>
                                <a href="<?= base_url('atm-liquidation/download-batch-file/' . $batch['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full hover:bg-purple-200 transition-colors" 
                                   title="Download File">
                                    <i class="bi bi-download text-sm"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-file-earmark-spreadsheet text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No pending batch liquidations</h5>
            <p class="text-gray-500">All batch liquidations have been processed.</p>
        </div>
        <?php endif; ?>
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
$(document).ready(function() {
    // Initialize DataTables with custom styling
    $('.data-table-individual').DataTable({
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: 'rtip',
        language: {
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });
    
    $('.data-table-batch').DataTable({
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: 'rtip',
        language: {
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });
    
    // Style DataTable pagination
    setTimeout(function() {
        $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-orange-50 hover:text-orange-600 hover:border-orange-300 transition-colors');
        $('.dataTables_paginate .paginate_button.current').addClass('bg-orange-600 text-white border-orange-600').removeClass('text-gray-500 border-gray-300');
    }, 100);
});

let currentLiquidationId = null;
let isBatch = false;

function switchTab(tab) {
    // Hide all tab contents
    $('.tab-content').addClass('hidden');
    // Remove active class from all tabs
    $('.tab-btn').removeClass('active text-orange-600 border-orange-600').addClass('text-gray-500 border-transparent');
    
    // Show selected tab content
    $('#content-' + tab).removeClass('hidden');
    // Add active class to selected tab
    $('#tab-' + tab).addClass('active text-orange-600 border-orange-600').removeClass('text-gray-500 border-transparent');
}

function approveModal(id, recipientName) {
    currentLiquidationId = id;
    isBatch = false;
    document.getElementById('approveBatchName').textContent = recipientName;
    document.getElementById('approveRemarks').value = '';
    document.getElementById('approveModal').classList.remove('hidden');
}

function approveBatchModal(id, batchName) {
    currentLiquidationId = id;
    isBatch = true;
    document.getElementById('approveBatchName').textContent = batchName;
    document.getElementById('approveRemarks').value = '';
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    currentLiquidationId = null;
    isBatch = false;
}

function confirmApprove() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('approveRemarks').value;
    const url = isBatch ? '<?= base_url('chairman/approve-atm-batch') ?>' : '<?= base_url('chairman/approve-atm') ?>';
    
    $.ajax({
        url: url,
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
            showAlert('error', 'An error occurred while approving.');
        }
    });
}

function rejectModal(id, recipientName) {
    currentLiquidationId = id;
    isBatch = false;
    document.getElementById('rejectBatchName').textContent = recipientName;
    document.getElementById('rejectRemarks').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function rejectBatchModal(id, batchName) {
    currentLiquidationId = id;
    isBatch = true;
    document.getElementById('rejectBatchName').textContent = batchName;
    document.getElementById('rejectRemarks').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    currentLiquidationId = null;
    isBatch = false;
}

function confirmReject() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('rejectRemarks').value;
    
    if (!remarks.trim()) {
        showAlert('error', 'Please provide a reason for rejection.');
        return;
    }
    
    const url = isBatch ? '<?= base_url('chairman/reject-atm-batch') ?>' : '<?= base_url('chairman/reject-atm') ?>';
    
    $.ajax({
        url: url,
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
            showAlert('error', 'An error occurred while rejecting.');
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
