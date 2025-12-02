<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Approved Manual Liquidations</h1>
        <p class="text-gray-600">Manual liquidation entries approved by chairman</p>
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
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">Approved Manual Liquidation Entries</h3>
        <p class="text-purple-100 text-sm">Individual entries for accounting records</p>
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
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Officer</th>
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
                                <div>
                                    <div class="font-medium text-gray-900"><?= esc($liquidation['disbursing_officer']) ?></div>
                                    <div class="text-sm text-gray-500">Disbursing Officer</div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?= base_url('accounting/manual/' . $liquidation['id']) ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-sm font-medium transition-colors"
                                       title="View Details">
                                        <i class="bi bi-eye mr-1"></i>
                                        View
                                    </a>
                                    <span class="status-approved">Approved</span>
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
                                    <p class="text-sm">No manual liquidations have been approved yet</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Initialize DataTable with custom settings for this page
$(document).ready(function() {
    if ($.fn.DataTable && $('.data-table').length) {
        if (!$.fn.DataTable.isDataTable('.data-table')) {
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[4, 'desc']], // Order by period column
                language: {
                    search: "Search liquidations:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    {
                        targets: [2], // Amount column
                        type: 'num',
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'type') {
                                return data;
                            }
                            // For sorting, extract numeric value
                            return parseFloat(data.replace(/[^\d.-]/g, ''));
                        }
                    }
                ]
            });
        }
    }
});
</script>
<?= $this->endSection() ?>