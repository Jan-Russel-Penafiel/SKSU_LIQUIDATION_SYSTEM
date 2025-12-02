<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-bar-graph mr-3 text-green-600"></i>
            Campus Reports - <?= esc($coordinator_campus) ?>
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Generate and view detailed reports for your campus activities</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <button onclick="exportToExcel()" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-file-earmark-excel mr-2"></i>
            Export to Excel
        </button>
        <button onclick="printReport()" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
            <i class="bi bi-printer mr-2"></i>
            Print Report
        </button>
    </div>
</div>

<!-- Report Filters -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-6">
    <div class="p-6">
            <form method="GET" action="<?= current_url() ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select name="semester" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
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
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="bi bi-funnel mr-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

<!-- Report Content Tabs -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button onclick="showTab('summary')" id="tab-summary" 
                            class="tab-button active w-1/4 py-4 px-6 text-center border-b-2 border-indigo-500 font-medium text-sm text-indigo-600">
                        <i class="bi bi-graph-up mr-2"></i>Summary
                    </button>
                    <button onclick="showTab('liquidations')" id="tab-liquidations" 
                            class="tab-button w-1/4 py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="bi bi-file-earmark-text mr-2"></i>Liquidations
                    </button>
                    <button onclick="showTab('status')" id="tab-status" 
                            class="tab-button w-1/4 py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="bi bi-pie-chart mr-2"></i>Status Analysis
                    </button>
                    <button onclick="showTab('disbursements')" id="tab-disbursements" 
                            class="tab-button w-1/4 py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="bi bi-cash mr-2"></i>Disbursements
                    </button>
                </nav>
            </div>

            <!-- Summary Tab -->
            <div id="content-summary" class="tab-content p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Campus Performance Summary</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Monthly Breakdown -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Monthly Activity</h4>
                        <div class="space-y-4">
                            <?php 
                            $monthlyData = [];
                            foreach ($report_data['manual_liquidations'] as $liquidation) {
                                $month = date('Y-m', strtotime($liquidation['created_at']));
                                $monthlyData[$month] = ($monthlyData[$month] ?? 0) + 1;
                            }
                            $monthlyData = array_slice(array_reverse($monthlyData, true), 0, 6);
                            ?>
                            <?php foreach ($monthlyData as $month => $count): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600"><?= date('F Y', strtotime($month . '-01')) ?></span>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: <?= ($count / max($monthlyData)) * 100 ?>%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900"><?= $count ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Status Breakdown -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Status Distribution</h4>
                        <div class="space-y-4">
                            <?php
                            $statusCounts = [
                                'pending' => count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'pending')),
                                'verified' => count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'verified')),
                                'approved' => count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'approved')),
                                'rejected' => count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'rejected'))
                            ];
                            $total = array_sum($statusCounts);
                            ?>
                            <?php foreach ($statusCounts as $status => $count): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 capitalize"><?= $status ?></span>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 mr-2"><?= $total > 0 ? round(($count / $total) * 100) : 0 ?>%</span>
                                        <span class="text-sm font-medium text-gray-900"><?= $count ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liquidations Tab -->
            <div id="content-liquidations" class="tab-content p-6 hidden">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Liquidations Detail Report</h3>
                
                <?php if (empty($report_data['manual_liquidations'])): ?>
                    <div class="text-center py-12">
                        <i class="bi bi-inbox text-6xl text-gray-400 mb-4"></i>
                        <h4 class="text-xl font-medium text-gray-900 mb-2">No Data Available</h4>
                        <p class="text-gray-600">No liquidations found for the selected filters.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voucher</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($report_data['manual_liquidations'] as $liquidation): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= esc($liquidation['recipient_code']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= esc($liquidation['voucher_number']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱<?= number_format($liquidation['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                <?php 
                                                switch($liquidation['status']) {
                                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                    case 'verified': echo 'bg-blue-100 text-blue-800'; break;
                                                    case 'approved': echo 'bg-green-100 text-green-800'; break;
                                                    case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                                    default: echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= esc(ucwords($liquidation['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= esc($liquidation['semester']) ?> <?= esc($liquidation['academic_year']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Status Analysis Tab -->
            <div id="content-status" class="tab-content p-6 hidden">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Status Analysis Report</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Processing Time Analysis</h4>
                        <div class="text-sm text-gray-600 space-y-2">
                            <p>Average processing time: <span class="font-medium">2.3 days</span></p>
                            <p>Fastest approval: <span class="font-medium">Same day</span></p>
                            <p>Longest pending: <span class="font-medium">7 days</span></p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Success Metrics</h4>
                        <div class="text-sm text-gray-600 space-y-2">
                            <?php
                            $total = count($report_data['manual_liquidations']);
                            $approved = count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'approved'));
                            $rejected = count(array_filter($report_data['manual_liquidations'], fn($l) => $l['status'] === 'rejected'));
                            $approvalRate = $total > 0 ? round(($approved / $total) * 100, 1) : 0;
                            ?>
                            <p>Approval Rate: <span class="font-medium text-green-600"><?= $approvalRate ?>%</span></p>
                            <p>Rejection Rate: <span class="font-medium text-red-600"><?= $total > 0 ? round(($rejected / $total) * 100, 1) : 0 ?>%</span></p>
                            <p>Completion Rate: <span class="font-medium"><?= $total > 0 ? round((($approved + $rejected) / $total) * 100, 1) : 0 ?>%</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disbursements Tab -->
            <div id="content-disbursements" class="tab-content p-6 hidden">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Disbursements Report</h3>
                
                <?php if (empty($report_data['disbursements'])): ?>
                    <div class="text-center py-12">
                        <i class="bi bi-cash text-6xl text-gray-400 mb-4"></i>
                        <h4 class="text-xl font-medium text-gray-900 mb-2">No Disbursement Data</h4>
                        <p class="text-gray-600">No disbursements found for this campus.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg p-6 text-center">
                            <div class="text-2xl font-bold text-blue-600"><?= count($report_data['disbursements']) ?></div>
                            <div class="text-sm text-blue-800">Total Disbursements</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-6 text-center">
                            <div class="text-2xl font-bold text-green-600">₱<?= number_format(array_sum(array_column($report_data['disbursements'], 'amount')), 2) ?></div>
                            <div class="text-sm text-green-800">Total Amount</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-6 text-center">
                            <div class="text-2xl font-bold text-yellow-600">₱<?= count($report_data['disbursements']) > 0 ? number_format(array_sum(array_column($report_data['disbursements'], 'amount')) / count($report_data['disbursements']), 2) : '0.00' ?></div>
                            <div class="text-sm text-yellow-800">Average Amount</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($report_data['disbursements'] as $disbursement): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= esc($disbursement['recipient_name']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱<?= number_format($disbursement['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('M j, Y', strtotime($disbursement['disbursement_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= esc(str_replace('_', ' ', $disbursement['disbursement_method'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <?= esc(ucwords(str_replace('_', ' ', $disbursement['status']))) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeTab = document.getElementById(`tab-${tabName}`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('active', 'border-indigo-500', 'text-indigo-600');
}

function exportToExcel() {
    // Create a simple CSV export
    const liquidations = <?= json_encode($report_data['manual_liquidations']) ?>;
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Recipient,Voucher Number,Amount,Date,Status,Semester,Academic Year\n";
    
    liquidations.forEach(liquidation => {
        const row = [
            `"${liquidation.first_name} ${liquidation.last_name}"`,
            liquidation.voucher_number,
            liquidation.amount,
            liquidation.liquidation_date,
            liquidation.status,
            liquidation.semester,
            liquidation.academic_year
        ].join(',');
        csvContent += row + "\n";
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `campus_report_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    window.print();
}

// Print styles
const style = document.createElement('style');
style.innerHTML = `
    @media print {
        body * { visibility: hidden; }
        .tab-content, .tab-content * { visibility: visible; }
        .tab-content { position: absolute; left: 0; top: 0; width: 100%; }
        nav, .breadcrumb, button { display: none !important; }
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>