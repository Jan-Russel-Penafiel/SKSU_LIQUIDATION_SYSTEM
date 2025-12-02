<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="bi bi-file-earmark-spreadsheet text-blue-600 mr-3"></i>
                Batch Liquidation Details
            </h1>
            <p class="text-gray-600 mt-2">View batch liquidation information and all recipients</p>
        </div>
        <a href="<?= base_url('atm-liquidation') ?>" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="bi bi-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>
</div>

<!-- Batch Information Card -->
<div class="bg-white rounded-2xl shadow-lg border border-blue-200/20 mb-6">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100/50">
        <h5 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="bi bi-info-circle text-blue-600 mr-3"></i>
            Batch Information
        </h5>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Batch ID -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Batch ID</label>
                <p class="text-lg font-semibold text-gray-900">#<?= isset($batch['id']) ? esc($batch['id']) : 'N/A' ?></p>
            </div>

            <!-- Batch Name -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Batch Name</label>
                <p class="text-lg font-semibold text-gray-900"><?= isset($batch['batch_name']) ? esc($batch['batch_name']) : 'N/A' ?></p>
            </div>

            <!-- File Type -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">File Type</label>
                <p class="text-lg">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="bi bi-file-earmark-<?= isset($batch['file_type']) && $batch['file_type'] === 'csv' ? 'text' : 'excel' ?> mr-2"></i>
                        <?= isset($batch['file_type']) ? esc(strtoupper($batch['file_type'])) : 'N/A' ?>
                    </span>
                </p>
            </div>

            <!-- Total Recipients -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Total Recipients</label>
                <p class="text-lg font-semibold text-gray-900"><?= count($details) ?> recipients</p>
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Total Amount</label>
                <p class="text-lg font-bold text-green-600">₱<?= number_format($totalAmount, 2) ?></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <p class="text-lg">
                    <?php 
                    $statusClass = '';
                    $statusText = isset($batch['status']) ? ucfirst(str_replace('_', ' ', $batch['status'])) : 'Unknown';
                    if (isset($batch['status'])) {
                        switch ($batch['status']) {
                            case 'uploaded':
                                $statusClass = 'bg-gray-100 text-gray-800';
                                break;
                            case 'processing':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            case 'processed':
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
                    } else {
                        $statusClass = 'bg-gray-100 text-gray-800';
                    }
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                        <?= esc($statusText) ?>
                    </span>
                </p>
            </div>

            <!-- Semester -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Semester</label>
                <p class="text-lg font-semibold text-gray-900"><?= isset($batch['semester']) ? esc($batch['semester']) : 'N/A' ?></p>
            </div>

            <!-- Academic Year -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Academic Year</label>
                <p class="text-lg font-semibold text-gray-900"><?= isset($batch['academic_year']) ? esc($batch['academic_year']) : 'N/A' ?></p>
            </div>

            <!-- Created At -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Created Date</label>
                <p class="text-lg font-semibold text-gray-900"><?= isset($batch['created_at']) ? date('M j, Y g:i A', strtotime($batch['created_at'])) : 'N/A' ?></p>
            </div>

            <!-- Remarks (Full Width) -->
            <?php if (!empty($batch['remarks'])): ?>
            <div class="md:col-span-2 lg:col-span-3">
                <label class="block text-sm font-medium text-gray-500 mb-1">Remarks</label>
                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg"><?= esc($batch['remarks']) ?></p>
            </div>
            <?php endif; ?>

            <!-- Download File -->
            <?php if (!empty($batch['file_path'])): ?>
            <div class="md:col-span-2 lg:col-span-3">
                <a href="<?= base_url('atm-liquidation/download-batch-file/' . $batch['id']) ?>" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="bi bi-download mr-2"></i>
                    Download Original File
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recipients List -->
<div class="bg-white rounded-2xl shadow-lg border border-blue-200/20">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100/50">
        <h5 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="bi bi-people text-blue-600 mr-3"></i>
            Recipients in this Batch
            <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full ml-3"><?= count($details) ?> recipients</span>
        </h5>
    </div>
    <div class="p-6">
        <?php if (!empty($details)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Campus</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Transaction Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Reference Number</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($details as $detail): ?>
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= esc($detail['id']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900"><?= esc($detail['first_name'] . ' ' . $detail['last_name']) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($detail['recipient_code']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($detail['campus']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">₱<?= number_format($detail['amount'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($detail['transaction_date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($detail['reference_number'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            $statusClass = '';
                            $statusText = ucfirst(str_replace('_', ' ', $detail['status']));
                            switch ($detail['status']) {
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">₱<?= number_format($totalAmount, 2) ?></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-700 mb-2">No recipients found</h5>
            <p class="text-gray-500">This batch has no recipient records.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
