<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-building mr-3 text-green-600"></i>
            <?= esc($coordinator_campus) ?> - Campus Overview
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Comprehensive statistics and management for your campus</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="<?= base_url('manual-liquidation/create') ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-plus-circle mr-2"></i>
            Add Liquidation
        </a>
        <a href="<?= base_url('scholarship-coordinator/reports') ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-graph-up mr-2"></i>
            Generate Report
        </a>
    </div>
</div>

        <!-- Campus Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-blue-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Recipients</p>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($campus_stats['total_recipients']) ?></p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="bi bi-people-fill text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-green-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Liquidations</p>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($campus_stats['total_liquidations']) ?></p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="bi bi-file-earmark-text-fill text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-yellow-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Pending</p>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($campus_stats['pending_liquidations']) ?></p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="bi bi-clock-fill text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-emerald-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Approved</p>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($campus_stats['approved_liquidations']) ?></p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-full">
                            <i class="bi bi-check-circle-fill text-2xl text-emerald-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-purple-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Disbursements</p>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($campus_stats['total_disbursements']) ?></p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="bi bi-cash-stack text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Recent Liquidations -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Campus Liquidations -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <i class="bi bi-file-earmark-bar-graph mr-3"></i>
                                Recent Liquidations
                            </h2>
                            <a href="<?= base_url('scholarship-coordinator/manage-liquidations') ?>" 
                               class="text-purple-200 hover:text-white text-sm">
                                View All →
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <?php if (empty($campus_liquidations)): ?>
                            <div class="text-center py-8">
                                <i class="bi bi-inbox text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600">No liquidations found for this campus.</p>
                                <a href="<?= base_url('manual-liquidation/create') ?>" 
                                   class="mt-3 inline-flex items-center text-purple-600 hover:text-purple-800">
                                    <i class="bi bi-plus-circle mr-1"></i>Create first liquidation
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50 rounded-lg">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php foreach (array_slice($campus_liquidations, 0, 10) as $liquidation): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= esc($liquidation['recipient_code']) ?>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900">
                                                    ₱<?= number_format($liquidation['amount'], 2) ?>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900">
                                                    <?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                        <?php 
                                                        switch($liquidation['status']) {
                                                            case 'pending':
                                                                echo 'bg-yellow-100 text-yellow-800';
                                                                break;
                                                            case 'verified':
                                                                echo 'bg-blue-100 text-blue-800';
                                                                break;
                                                            case 'approved':
                                                                echo 'bg-green-100 text-green-800';
                                                                break;
                                                            case 'rejected':
                                                                echo 'bg-red-100 text-red-800';
                                                                break;
                                                            default:
                                                                echo 'bg-gray-100 text-gray-800';
                                                        }
                                                        ?>">
                                                        <?= esc(ucwords($liquidation['status'])) ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-sm">
                                                    <a href="<?= base_url('manual-liquidation/show/' . $liquidation['id']) ?>" 
                                                       class="text-purple-600 hover:text-purple-900">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="bi bi-pie-chart mr-3"></i>
                            Status Distribution
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600"><?= $campus_stats['pending_liquidations'] ?></div>
                                <div class="text-sm text-yellow-800">Pending</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    <?= count(array_filter($campus_liquidations, fn($l) => $l['status'] === 'verified')) ?>
                                </div>
                                <div class="text-sm text-blue-800">Verified</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600"><?= $campus_stats['approved_liquidations'] ?></div>
                                <div class="text-sm text-green-800">Approved</div>
                            </div>
                            <div class="text-center p-4 bg-red-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">
                                    <?= count(array_filter($campus_liquidations, fn($l) => $l['status'] === 'rejected')) ?>
                                </div>
                                <div class="text-sm text-red-800">Rejected</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Recipients & Quick Actions -->
            <div class="space-y-6">
                <!-- Campus Recipients -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-teal-700">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-people mr-3"></i>
                            Campus Recipients
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (empty($campus_recipients)): ?>
                            <div class="text-center py-6">
                                <i class="bi bi-person-plus text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">No active recipients</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3 max-h-80 overflow-y-auto">
                                <?php foreach (array_slice($campus_recipients, 0, 10) as $recipient): ?>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                <?= esc($recipient['first_name'] . ' ' . $recipient['last_name']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= esc($recipient['recipient_id']) ?> • <?= esc($recipient['course']) ?>
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($campus_recipients) > 10): ?>
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">
                                        Showing 10 of <?= count($campus_recipients) ?> recipients
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-orange-700">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-lightning-fill mr-3"></i>
                            Quick Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?= base_url('manual-liquidation/create') ?>" 
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors group w-full">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3 group-hover:bg-blue-200">
                                <i class="bi bi-plus-circle text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Create Liquidation</span>
                        </a>
                        
                        <a href="<?= base_url('scholarship-coordinator/manage-liquidations?status=pending') ?>" 
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition-colors group w-full">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3 group-hover:bg-yellow-200">
                                <i class="bi bi-clock text-yellow-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Review Pending</span>
                        </a>
                        
                        <a href="<?= base_url('scholarship-coordinator/reports') ?>" 
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors group w-full">
                            <div class="p-2 bg-green-100 rounded-lg mr-3 group-hover:bg-green-200">
                                <i class="bi bi-graph-up text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Campus Reports</span>
                        </a>
                        
                        <a href="<?= base_url('manual-liquidation/entry-by-campus') ?>" 
                           class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors group w-full">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3 group-hover:bg-purple-200">
                                <i class="bi bi-building text-purple-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Bulk Entry</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Disbursements -->
                <?php if (!empty($campus_disbursements)): ?>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-cash mr-3"></i>
                            Recent Disbursements
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            <?php foreach (array_slice($campus_disbursements, 0, 5) as $disbursement): ?>
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                <?= esc($disbursement['recipient_name']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                ₱<?= number_format($disbursement['amount'], 2) ?>
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            <?= $disbursement['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                            <?= esc(ucwords(str_replace('_', ' ', $disbursement['status']))) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>