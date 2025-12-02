<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-inbox text-green-600 mr-3"></i>
            Received ATM Liquidations
        </h1>
        <p class="text-gray-600 mt-2 text-sm">ATM liquidation records received and currently in accounting processing</p>
    </div>
    
    <?php 
    $individualRecords = array_filter($liquidations ?? [], function($liq) {
        return empty($liq['atm_liquidation_id']);
    });
    $batchRecords = array_filter($liquidations ?? [], function($liq) {
        return !empty($liq['atm_liquidation_id']);
    });
    // Group batch records by atm_liquidation_id
    $batches = [];
    foreach ($batchRecords as $record) {
        $batchId = $record['atm_liquidation_id'];
        if (!isset($batches[$batchId])) {
            $batches[$batchId] = [
                'id' => $batchId,
                'batch_name' => $record['batch_name'] ?? 'Batch #' . $batchId,
                'status' => $record['status'],
                'created_at' => $record['created_at'],
                'uploader_name' => $record['uploader_name'] ?? 'N/A',
                'semester' => $record['semester'],
                'academic_year' => $record['academic_year'],
                'file_type' => $record['file_type'] ?? 'CSV',
                'accounting_received_date' => $record['accounting_received_date'] ?? null,
                'recipient_count' => 0,
                'total_amount' => 0,
                'total_records' => 0
            ];
        }
        $batches[$batchId]['recipient_count']++;
        $batches[$batchId]['total_amount'] += $record['amount'];
        $batches[$batchId]['total_records']++;
    }
    $batches = array_values($batches);
    ?>
    <div class="flex items-center space-x-4">
        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
            <?= count($individualRecords) + count($batches) ?> Processing
        </span>
        <a href="<?= base_url('accounting') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-t-2xl shadow-lg border-b-0 border border-green-200/20">
    <div class="flex border-b border-gray-200">
        <button onclick="switchTab('individual')" id="tab-individual" class="tab-btn active px-6 py-4 text-sm font-semibold text-green-600 border-b-2 border-green-600 focus:outline-none">
            <i class="bi bi-person mr-2"></i>Individual Records
            <span class="ml-2 bg-green-100 text-green-600 text-xs px-2.5 py-0.5 rounded-full font-semibold"><?= count($individualRecords) ?></span>
        </button>
        <button onclick="switchTab('batch')" id="tab-batch" class="tab-btn px-6 py-4 text-sm font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none">
            <i class="bi bi-file-earmark-spreadsheet mr-2"></i>Batch Records
            <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2.5 py-0.5 rounded-full font-semibold"><?= count($batches) ?></span>
        </button>
    </div>
</div>

<!-- Individual Liquidations Table -->
<div id="content-individual" class="tab-content bg-white rounded-b-2xl shadow-lg border border-t-0 border-green-200/20">
    <div class="p-6 border-b border-gray-200">
        <h5 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="bi bi-person text-green-600 mr-3"></i>
            Individual ATM Liquidations
            <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($individualRecords) ?> records</span>
        </h5>
    </div>
    <div class="p-6">
        <?php if (!empty($individualRecords)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table-individual">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Transaction Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Received Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($individualRecords as $liquidation): ?>
                    <tr class="hover:bg-green-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= esc($liquidation['id']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?= esc($liquidation['recipient_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['recipient_code']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">₱<?= number_format($liquidation['amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($liquidation['transaction_date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($liquidation['reference_number'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($liquidation['semester']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if (!empty($liquidation['accounting_received_date'])): ?>
                                <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($liquidation['accounting_received_date'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($liquidation['accounting_received_date'])) ?></div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Processing
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('accounting/atm/' . $liquidation['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors" 
                                   title="View Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                
                                <?php if (!empty($liquidation['file_path'])): ?>
                                <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-full hover:bg-purple-200 transition-colors" 
                                   title="Download File">
                                    <i class="bi bi-download text-sm"></i>
                                </a>
                                <?php endif; ?>
                                
                                <button type="button" 
                                        onclick="completeModal(<?= $liquidation['id'] ?>, '<?= esc($liquidation['recipient_name']) ?>', false)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-full hover:bg-green-200 transition-colors"
                                        title="Mark Complete">
                                    <i class="bi bi-check-circle text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-person text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No individual liquidations in processing</h5>
            <p class="text-gray-500">All individual liquidations have been completed.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Batch Liquidations Table -->
<div id="content-batch" class="tab-content hidden bg-white rounded-b-2xl shadow-lg border border-t-0 border-green-200/20">
    <div class="p-6 border-b border-gray-200">
        <h5 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="bi bi-file-earmark-spreadsheet text-green-600 mr-3"></i>
            Batch ATM Liquidations
            <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($batches) ?> batches</span>
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Received Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
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
                            <div class="text-xs text-gray-500 mt-1"><?= esc($batch['file_type']) ?> file</div>
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
                            <?php if (!empty($batch['accounting_received_date'])): ?>
                                <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($batch['accounting_received_date'])) ?></div>
                                <div class="text-xs text-gray-500"><?= date('g:i A', strtotime($batch['accounting_received_date'])) ?></div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Processing
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('accounting/atm-batch/' . $batch['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors" 
                                   title="View Batch Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <button type="button" 
                                        onclick="completeModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_name']) ?>', true)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-full hover:bg-green-200 transition-colors"
                                        title="Mark Complete">
                                    <i class="bi bi-check-circle text-sm"></i>
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
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No batch liquidations in processing</h5>
            <p class="text-gray-500">All batch liquidations have been completed.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Complete Processing</h3>
                    <p class="text-gray-600 text-sm">Mark this liquidation as completed</p>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-700"><span id="completeLabel">Record:</span> <span id="completeName" class="font-medium"></span></p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Completion Notes (Optional)</label>
                <textarea id="completeRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any completion notes..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeCompleteModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="confirmComplete()" class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                    Mark Complete
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
        $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors');
        $('.dataTables_paginate .paginate_button.current').addClass('bg-green-600 text-white border-green-600').removeClass('text-gray-500 border-gray-300');
    }, 100);
});

let currentLiquidationId = null;
let isBatch = false;

function switchTab(tab) {
    // Hide all tab contents
    $('.tab-content').addClass('hidden');
    // Remove active class from all tabs
    $('.tab-btn').removeClass('active text-green-600 border-green-600').addClass('text-gray-500 border-transparent');
    
    // Show selected tab content
    $('#content-' + tab).removeClass('hidden');
    // Add active class to selected tab
    $('#tab-' + tab).addClass('active text-green-600 border-green-600').removeClass('text-gray-500 border-transparent');
}

function completeModal(id, name, isBatchRecord) {
    currentLiquidationId = id;
    isBatch = isBatchRecord;
    document.getElementById('completeLabel').textContent = isBatch ? 'Batch:' : 'Recipient:';
    document.getElementById('completeName').textContent = name;
    document.getElementById('completeRemarks').value = '';
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    currentLiquidationId = null;
    isBatch = false;
}

function confirmComplete() {
    if (!currentLiquidationId) return;
    
    const remarks = document.getElementById('completeRemarks').value;
    const url = isBatch ? '<?= base_url('accounting/complete-atm-batch') ?>' : '<?= base_url('accounting/complete-atm') ?>';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: currentLiquidationId,
            remarks: remarks,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                closeCompleteModal();
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while completing the liquidation.');
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
    
    const container = document.querySelector('.flex.flex-col.lg\\:flex-row');
    if (container && container.nextElementSibling) {
        container.nextElementSibling.insertAdjacentHTML('beforebegin', alertHtml);
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('bg-black')) {
        closeCompleteModal();
    }
}
</script>
<?= $this->endSection() ?>