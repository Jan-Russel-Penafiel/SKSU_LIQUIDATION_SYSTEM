<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-speedometer2 mr-3 text-green-600"></i>
            Scholarship Coordinator Dashboard
        </h1>
        <p class="text-gray-600 mt-1 text-sm"><?= esc($coordinator_campus) ?> - Welcome back, <?= esc($user['username']) ?></p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="<?= base_url('scholarship-coordinator/campus-overview') ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-building mr-2"></i>
            Campus Overview
        </a>
        <a href="<?= base_url('scholarship-coordinator/my-liquidations') ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-list-task mr-2"></i>
            My Liquidations
        </a>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Campus Recipients</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format($total_recipients) ?></p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="bi bi-people-fill text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Active scholarship recipients</p>
                </div>
            </div>

    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">My Liquidations</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format(count($coordinator_liquidations)) ?></p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="bi bi-file-earmark-text-fill text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Assigned to me</p>
                </div>
            </div>

    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pending Reviews</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format(count($pending_liquidations)) ?></p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="bi bi-clock-fill text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Awaiting action</p>
                </div>
            </div>

            <!-- Campus Total Amount -->
            <div class="bg-white rounded-xl shadow-lg border border-purple-200/20 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Amount</p>
                            <p class="text-3xl font-bold text-gray-900">₱<?= number_format($manualStats['total_amount'] ?? 0, 2) ?></p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="bi bi-cash-stack text-2xl text-purple-600"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Campus liquidations</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Pending Actions -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Pending Liquidations -->
                <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-exclamation-triangle mr-3 text-green-600"></i>
                            Pending Liquidations - <?= esc($coordinator_campus) ?>
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (empty($pending_liquidations)): ?>
                            <div class="text-center py-8">
                                <i class="bi bi-check-circle-fill text-4xl text-green-500 mb-3"></i>
                                <p class="text-gray-600">No pending liquidations for your campus.</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach (array_slice($pending_liquidations, 0, 5) as $liquidation): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="font-medium text-gray-900">
                                                    <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                                </h3>
                                                <p class="text-sm text-gray-600">ID: <?= esc($liquidation['recipient_code']) ?></p>
                                            </div>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                Pending
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                            <div>
                                                <span class="text-gray-500">Amount:</span>
                                                <span class="font-medium">₱<?= number_format($liquidation['amount'], 2) ?></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Date:</span>
                                                <span class="font-medium"><?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="<?= base_url('scholarship-coordinator/review/' . $liquidation['id']) ?>" 
                                               class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition-colors">
                                                Review
                                            </a>
                                            <button onclick="approveLiquidation(<?= $liquidation['id'] ?>)" 
                                                    class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors">
                                                Quick Approve
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($pending_liquidations) > 5): ?>
                                <div class="mt-4 text-center">
                                    <a href="<?= base_url('scholarship-coordinator/manage-liquidations?status=pending') ?>" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        View all <?= count($pending_liquidations) ?> pending liquidations →
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-lightning-fill mr-3 text-green-600"></i>
                            Quick Actions
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="<?= base_url('manual-liquidation/create') ?>" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors group">
                                <div class="p-2 bg-blue-100 rounded-lg mr-4 group-hover:bg-blue-200">
                                    <i class="bi bi-plus-circle text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Create Liquidation</h3>
                                    <p class="text-sm text-gray-600">Add new liquidation entry</p>
                                </div>
                            </a>
                            
                            <a href="<?= base_url('scholarship-coordinator/campus-overview') ?>" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors group">
                                <div class="p-2 bg-purple-100 rounded-lg mr-4 group-hover:bg-purple-200">
                                    <i class="bi bi-graph-up text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Campus Analytics</h3>
                                    <p class="text-sm text-gray-600">View detailed statistics</p>
                                </div>
                            </a>
                            
                            <a href="<?= base_url('scholarship-coordinator/manage-liquidations') ?>" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors group">
                                <div class="p-2 bg-green-100 rounded-lg mr-4 group-hover:bg-green-200">
                                    <i class="bi bi-list-check text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Manage Liquidations</h3>
                                    <p class="text-sm text-gray-600">Review and approve</p>
                                </div>
                            </a>
                            
                            <a href="<?= base_url('scholarship-coordinator/reports') ?>" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-colors group">
                                <div class="p-2 bg-orange-100 rounded-lg mr-4 group-hover:bg-orange-200">
                                    <i class="bi bi-file-earmark-bar-graph text-orange-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Generate Reports</h3>
                                    <p class="text-sm text-gray-600">Campus performance</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Recent Activity -->
            <div class="space-y-6">
                <!-- My Recent Liquidations -->
                <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-clock-history mr-3 text-green-600"></i>
                            My Recent Activity
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (empty($coordinator_liquidations)): ?>
                            <div class="text-center py-6">
                                <i class="bi bi-inbox text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">No recent activity</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach (array_slice($coordinator_liquidations, 0, 5) as $liquidation): ?>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                Voucher #<?= esc($liquidation['voucher_number']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= date('M j, g:i A', strtotime($liquidation['updated_at'])) ?>
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            <?= $liquidation['status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($liquidation['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= esc(ucwords($liquidation['status'])) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Campus Status Summary -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-pie-chart mr-3"></i>
                            Campus Status Summary
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="font-medium text-yellow-600"><?= $manualStats['pending'] ?? 0 ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Verified</span>
                                <span class="font-medium text-blue-600"><?= $manualStats['verified'] ?? 0 ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Approved</span>
                                <span class="font-medium text-green-600"><?= $manualStats['approved'] ?? 0 ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Rejected</span>
                                <span class="font-medium text-red-600"><?= $manualStats['rejected'] ?? 0 ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">Total Records</span>
                                <span class="font-bold text-gray-900"><?= $manualStats['total_records'] ?? 0 ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Disbursements -->
                <?php if (!empty($recent_disbursements)): ?>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-teal-700">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="bi bi-cash mr-3"></i>
                            Recent Disbursements
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php foreach (array_slice($recent_disbursements, 0, 3) as $disbursement): ?>
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
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
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

<script>
function approveLiquidation(id) {
    if (confirm('Are you sure you want to approve this liquidation?')) {
        fetch('<?= base_url('scholarship-coordinator/update-status') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&status=approved`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        });
    }
}
</script>
<?= $this->endSection() ?>