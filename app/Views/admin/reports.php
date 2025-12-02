<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-bar-graph-fill mr-3 text-green-600"></i>
            Admin Reports & Analytics
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Comprehensive reporting for all liquidation activities and system performance</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <button onclick="exportReport()" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-file-earmark-excel mr-2"></i>
            Export to CSV
        </button>
        <button onclick="printReport()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center text-sm">
            <i class="bi bi-printer mr-2"></i>
            Print Report
        </button>
    </div>
</div>

<!-- Report Type Selection -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Type</h3>
        <form method="GET" action="<?= current_url() ?>" id="reportForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Category</label>
                    <select name="report_type" id="reportType" onchange="updateReportDescription()" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="submissions_by_recipient" <?= $filters['report_type'] === 'submissions_by_recipient' ? 'selected' : '' ?>>
                            Submissions by Recipient
                        </option>
                        <option value="submissions_by_campus" <?= $filters['report_type'] === 'submissions_by_campus' ? 'selected' : '' ?>>
                            Submissions by Campus
                        </option>
                        <option value="submissions_by_scholarship" <?= $filters['report_type'] === 'submissions_by_scholarship' ? 'selected' : '' ?>>
                            Submissions by Scholarship Type
                        </option>
                        <option value="approved_pending_proofs" <?= $filters['report_type'] === 'approved_pending_proofs' ? 'selected' : '' ?>>
                            Approved & Pending Proofs
                        </option>
                        <option value="voucher_summary" <?= $filters['report_type'] === 'voucher_summary' ? 'selected' : '' ?>>
                            Voucher Summary
                        </option>
                        <option value="officer_performance" <?= $filters['report_type'] === 'officer_performance' ? 'selected' : '' ?>>
                            Disbursing Officer Performance
                        </option>
                        <option value="coordinator_performance" <?= $filters['report_type'] === 'coordinator_performance' ? 'selected' : '' ?>>
                            Scholarship Coordinator Performance
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campus</label>
                    <select name="campus" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">All Campuses</option>
                        <?php foreach ($campuses as $campus): ?>
                            <option value="<?= esc($campus['campus']) ?>" <?= $filters['campus'] === $campus['campus'] ? 'selected' : '' ?>>
                                <?= esc($campus['campus']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scholarship Type</label>
                    <select name="scholarship_type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">All Types</option>
                        <?php foreach ($scholarship_types as $type): ?>
                            <option value="<?= esc($type['scholarship_type']) ?>" <?= $filters['scholarship_type'] === $type['scholarship_type'] ? 'selected' : '' ?>>
                                <?= esc($type['scholarship_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="verified" <?= $filters['status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                        <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $filters['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">All Semesters</option>
                        <option value="1st Semester" <?= $filters['semester'] === '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                        <option value="2nd Semester" <?= $filters['semester'] === '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                        <option value="Summer" <?= $filters['semester'] === 'Summer' ? 'selected' : '' ?>>Summer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                    <input type="text" name="academic_year" value="<?= esc($filters['academic_year']) ?>" 
                           placeholder="e.g., 2024-2025"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" value="<?= esc($filters['date_from']) ?>" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" value="<?= esc($filters['date_to']) ?>" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div id="reportDescription" class="text-sm text-gray-600"></div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class="bi bi-funnel mr-2"></i>Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Report Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900" id="reportTitle">
            <?php
            $titles = [
                'submissions_by_recipient' => 'Liquidation Submissions by Recipient',
                'submissions_by_campus' => 'Liquidation Submissions by Campus',
                'submissions_by_scholarship' => 'Liquidation Submissions by Scholarship Type',
                'approved_pending_proofs' => 'Approved and Pending Liquidation Proofs',
                'voucher_summary' => 'Manual System Voucher Summary',
                'officer_performance' => 'Disbursing Officer Performance Report',
                'coordinator_performance' => 'Scholarship Coordinator Performance Report'
            ];
            echo $titles[$filters['report_type']] ?? 'Report';
            ?>
        </h3>
    </div>

    <div class="p-6">
        <?php if (empty($report_data)): ?>
            <div class="text-center py-12">
                <i class="bi bi-inbox text-6xl text-gray-400 mb-4"></i>
                <h4 class="text-xl font-medium text-gray-900 mb-2">No Data Available</h4>
                <p class="text-gray-600">No data found for the selected filters and report type.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <!-- Submissions by Recipient Report -->
                <?php if ($filters['report_type'] === 'submissions_by_recipient'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholarship Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ATM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= esc($row['first_name'] . ' ' . $row['last_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= esc($row['recipient_id']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['scholarship_type']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['manual_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['atm_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['total_amount'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['approved_count'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['pending_count'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Submissions by Campus Report -->
                <?php elseif ($filters['report_type'] === 'submissions_by_campus'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manual Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ATM Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Manual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg ATM</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $row['total_recipients'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['manual_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['atm_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['total_amount'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱<?= number_format($row['avg_manual_amount'] ?? 0, 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱<?= number_format($row['avg_atm_amount'] ?? 0, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Submissions by Scholarship Type Report -->
                <?php elseif ($filters['report_type'] === 'submissions_by_scholarship'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholarship Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manual Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ATM Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Manual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg ATM</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($row['scholarship_type']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= $row['total_recipients'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['manual_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['atm_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['total_amount'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱<?= number_format($row['avg_manual_amount'] ?? 0, 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱<?= number_format($row['avg_atm_amount'] ?? 0, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Approved and Pending Proofs Report -->
                <?php elseif ($filters['report_type'] === 'approved_pending_proofs'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher/Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $row['liquidation_type'] === 'manual' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                            <?= esc(ucfirst($row['liquidation_type'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= esc($row['first_name'] . ' ' . $row['last_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= esc($row['recipient_id']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['voucher_number'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['amount'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($row['liquidation_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?= esc(ucwords($row['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['disbursing_officer'] ?? 'N/A') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Voucher Summary Report -->
                <?php elseif ($filters['report_type'] === 'voucher_summary'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($row['voucher_number']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= esc($row['first_name'] . ' ' . $row['last_name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= esc($row['recipient_id']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['amount'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($row['liquidation_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            <?php 
                                            switch($row['status']) {
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'verified': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'approved': echo 'bg-green-100 text-green-800'; break;
                                                case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= esc(ucwords($row['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= esc($row['semester']) ?> <?= esc($row['academic_year']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Officer Performance Report -->
                <?php elseif ($filters['report_type'] === 'officer_performance'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Officer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disbursements</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($row['username']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['total_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['total_disbursements'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['approved_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['pending_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['rejected_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['total_amount_liquidated'] + $row['total_amount_disbursed'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Coordinator Performance Report -->
                <?php elseif ($filters['report_type'] === 'coordinator_performance'): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coordinator</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Liquidations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients Managed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Processed</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($report_data as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($row['username']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($row['campus']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['total_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['verified_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['approved_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['rejected_liquidations'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                            <?= $row['total_recipients_managed'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱<?= number_format($row['total_amount_processed'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Report Summary -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Report Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600"><?= count($report_data) ?></div>
                        <div class="text-sm text-gray-600">Total Records</div>
                    </div>
                    <?php if (isset($report_data[0]['total_amount'])): ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            ₱<?= number_format(array_sum(array_column($report_data, 'total_amount')), 2) ?>
                        </div>
                        <div class="text-sm text-gray-600">Total Amount</div>
                    </div>
                    <?php endif; ?>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            <?= date('M j, Y') ?>
                        </div>
                        <div class="text-sm text-gray-600">Generated On</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Report descriptions
const reportDescriptions = {
    'submissions_by_recipient': 'Shows liquidation submissions grouped by individual recipients with their campus and scholarship details.',
    'submissions_by_campus': 'Displays aggregated liquidation data organized by campus locations.',
    'submissions_by_scholarship': 'Groups liquidation submissions by scholarship type and program.',
    'approved_pending_proofs': 'Lists all approved and pending liquidation proofs across all liquidation types.',
    'voucher_summary': 'Summary of all vouchers recorded in the manual liquidation system.',
    'officer_performance': 'Performance metrics for disbursing officers including liquidation counts and amounts.',
    'coordinator_performance': 'Performance analysis for scholarship coordinators managing campus operations.'
};

function updateReportDescription() {
    const reportType = document.getElementById('reportType').value;
    const description = reportDescriptions[reportType] || '';
    document.getElementById('reportDescription').textContent = description;
}

function exportReport() {
    const formData = new FormData(document.getElementById('reportForm'));
    
    fetch('<?= base_url('reports/export') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Convert array to CSV string
            const csvContent = data.data.map(row => row.map(field => `"${field}"`).join(',')).join('\n');
            
            // Create download link
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', data.filename);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        } else {
            alert('Export failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Export error:', error);
        alert('Export failed. Please try again.');
    });
}

function printReport() {
    window.print();
}

// Initialize description on page load
document.addEventListener('DOMContentLoaded', function() {
    updateReportDescription();
});

// Print styles
const style = document.createElement('style');
style.innerHTML = `
    @media print {
        body * { visibility: hidden; }
        .overflow-x-auto, .overflow-x-auto * { visibility: visible; }
        .overflow-x-auto { position: absolute; left: 0; top: 0; width: 100%; }
        nav, .breadcrumb, button, .flex.flex-col.sm\\:flex-row { display: none !important; }
        table { font-size: 12px; }
        .px-6 { padding-left: 8px !important; padding-right: 8px !important; }
        .py-4 { padding-top: 4px !important; padding-bottom: 4px !important; }
    }
`;
document.head.appendChild(style);
</script>

<?= $this->endSection() ?>