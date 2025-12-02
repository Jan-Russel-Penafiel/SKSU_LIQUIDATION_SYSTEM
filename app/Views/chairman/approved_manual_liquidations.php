<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">
    <div>
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-check-circle-fill text-emerald-600 mr-3"></i>
            Approved Manual Liquidations
        </h1>
        <p class="text-gray-600 mt-2 text-sm">View all approved manual liquidation records</p>
    </div>
    
    <div class="flex items-center space-x-4">
        <span class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-lg font-semibold">
            <?= count($approvedManual ?? []) ?> Approved
        </span>
        <a href="<?= base_url('chairman') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Records</p>
                <p class="text-3xl font-bold text-emerald-600"><?= count($approvedManual ?? []) ?></p>
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <i class="bi bi-file-text text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Amount</p>
                <p class="text-3xl font-bold text-emerald-600">₱<?= number_format($totalApprovedAmount ?? 0, 2) ?></p>
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <i class="bi bi-cash-stack text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Unique Recipients</p>
                <p class="text-3xl font-bold text-emerald-600"><?= count($recipientStats ?? []) ?></p>
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <i class="bi bi-people text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Approved Manual Liquidations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h5 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="bi bi-file-check text-emerald-600 mr-3"></i>
                Approved Manual Liquidations
                <span class="bg-emerald-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($approvedManual ?? []) ?> records</span>
            </h5>
        </div>
    </div>
    <div class="p-6">
        <?php if (!empty($approvedManual)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 data-table-manual">
                <thead class="bg-gradient-to-r from-emerald-600 to-emerald-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Campus</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Voucher Number</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Liquidation Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Approved Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($approvedManual as $liquidation): ?>
                    <tr class="hover:bg-emerald-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= esc($liquidation['id']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?= esc($liquidation['first_name']) ?> <?= esc($liquidation['last_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['recipient_id'] ?? 'N/A') ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($liquidation['campus']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= esc($liquidation['voucher_number']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-emerald-600">₱<?= number_format($liquidation['amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= esc($liquidation['semester']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($liquidation['academic_year']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="<?= esc($liquidation['description']) ?>">
                                <?= esc($liquidation['description']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= !empty($liquidation['updated_at']) ? date('M j, Y', strtotime($liquidation['updated_at'])) : 'N/A' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="<?= base_url('chairman/manual/' . $liquidation['id']) ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors" 
                                   title="View Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total Amount:</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-emerald-600">₱<?= number_format($totalApprovedAmount ?? 0, 2) ?></div>
                        </td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-file-check text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No approved manual liquidations</h5>
            <p class="text-gray-500">Manual liquidations will appear here once approved.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTables with custom styling
    $('.data-table-manual').DataTable({
        order: [[8, 'desc']], // Order by approved date
        pageLength: 25,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: '<"flex justify-between items-center mb-4"<"flex items-center space-x-2"l><"flex-1"f>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search liquidations...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });
    
    // Style DataTable elements
    setTimeout(function() {
        $('select[name$="_length"]').addClass('px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500');
        $('input[type="search"]').addClass('px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full');
        $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition-colors');
        $('.dataTables_paginate .paginate_button.current').addClass('bg-emerald-600 text-white border-emerald-600').removeClass('text-gray-500 border-gray-300');
    }, 100);
});
</script>
<?= $this->endSection() ?>
