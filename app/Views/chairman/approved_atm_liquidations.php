<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-check-circle text-green-600 mr-3"></i>
            Approved ATM Liquidations
        </h1>
        <p class="text-gray-600 mt-2 text-sm">View all approved ATM liquidation records</p>
    </div>
    
    <div class="flex items-center space-x-4">
        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
            <?= count($approvedRecipients ?? []) + count($approvedBatches ?? []) ?> Approved
        </span>
        <a href="<?= base_url('chairman') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-t-2xl shadow-lg border-b-0 border border-green-200/20">
    <div class="flex border-b border-gray-200">
        <button onclick="switchTab('recipient')" id="tab-recipient" class="tab-btn active px-6 py-4 text-sm font-semibold text-green-600 border-b-2 border-green-600 focus:outline-none">
            <i class="bi bi-person-check mr-2"></i>Per Recipient
            <span class="ml-2 bg-green-100 text-green-600 text-xs px-2.5 py-0.5 rounded-full font-semibold"><?= count($approvedRecipients ?? []) ?></span>
        </button>
        <button onclick="switchTab('batch')" id="tab-batch" class="tab-btn px-6 py-4 text-sm font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none">
            <i class="bi bi-file-earmark-check mr-2"></i>Per Batch
            <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2.5 py-0.5 rounded-full font-semibold"><?= count($approvedBatches ?? []) ?></span>
        </button>
    </div>
</div>

<!-- Per Recipient Table -->
<div id="content-recipient" class="tab-content bg-white rounded-b-2xl shadow-lg border border-t-0 border-green-200/20">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h5 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="bi bi-person-check text-green-600 mr-3"></i>
                Approved by Recipient
                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($approvedRecipients ?? []) ?> records</span>
            </h5>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-gray-700">Total Amount:</span>
                <span class="text-lg font-bold text-green-600">₱<?= number_format($totalApprovedRecipientAmount ?? 0, 2) ?></span>
            </div>
        </div>
    </div>
    <div class="p-6">
        <?php if (!empty($approvedRecipients)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table-recipient">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Campus</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Transaction Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Approved Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($approvedRecipients as $liquidation): ?>
                    <tr class="hover:bg-green-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= esc($liquidation['id']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?= esc($liquidation['recipient_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['recipient_code']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($liquidation['campus']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($liquidation['transaction_date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($liquidation['reference_number'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($liquidation['semester']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($liquidation['approved_at'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('chairman/atm/' . $liquidation['id']) ?>" 
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
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-green-600">₱<?= number_format($totalApprovedRecipientAmount ?? 0, 2) ?></div>
                        </td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-person-check text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No approved individual liquidations</h5>
            <p class="text-gray-500">Individual recipient liquidations will appear here once approved.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Per Batch Table -->
<div id="content-batch" class="tab-content hidden bg-white rounded-b-2xl shadow-lg border border-t-0 border-green-200/20">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h5 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="bi bi-file-earmark-check text-green-600 mr-3"></i>
                Approved by Batch
                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($approvedBatches ?? []) ?> batches</span>
            </h5>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-gray-700">Total Amount:</span>
                <span class="text-lg font-bold text-green-600">₱<?= number_format($totalApprovedBatchAmount ?? 0, 2) ?></span>
            </div>
        </div>
    </div>
    <div class="p-6">
        <?php if (!empty($approvedBatches)): ?>
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Approved Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($approvedBatches as $batch): ?>
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                            <div class="text-sm font-bold text-green-600">₱<?= number_format($batch['total_amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($batch['semester']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($batch['academic_year']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($batch['uploader_name']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($batch['approved_at'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('chairman/atm/batch/' . $batch['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors" 
                                   title="View Batch Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
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
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Grand Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-green-600">₱<?= number_format($totalApprovedBatchAmount ?? 0, 2) ?></div>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-file-earmark-check text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No approved batch liquidations</h5>
            <p class="text-gray-500">Batch liquidations will appear here once approved.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTables with custom styling
    $('.data-table-recipient').DataTable({
        order: [[7, 'desc']], // Order by approved date
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center space-x-2"l><"flex-1"f>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search recipients...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });
    
    $('.data-table-batch').DataTable({
        order: [[6, 'desc']], // Order by approved date
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center space-x-2"l><"flex-1"f>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search batches...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });
    
    // Style DataTable elements
    setTimeout(function() {
        $('select[name$="_length"]').addClass('px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500');
        $('input[type="search"]').addClass('px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 w-full');
        $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors');
        $('.dataTables_paginate .paginate_button.current').addClass('bg-green-600 text-white border-green-600').removeClass('text-gray-500 border-gray-300');
    }, 100);
});

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
</script>
<?= $this->endSection() ?>
