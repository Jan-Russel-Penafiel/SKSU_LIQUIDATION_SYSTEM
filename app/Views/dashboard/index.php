<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-speedometer2 mr-3 text-green-600"></i>
            Dashboard
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Scholarship Liquidation Monitoring Overview</p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm bg-white" id="semesterFilter">
            <option value="">All Semesters</option>
            <option value="1st Semester" <?= (isset($filters['semester']) && $filters['semester'] === '1st Semester') ? 'selected' : '' ?>>1st Semester</option>
            <option value="2nd Semester" <?= (isset($filters['semester']) && $filters['semester'] === '2nd Semester') ? 'selected' : '' ?>>2nd Semester</option>
            <option value="Summer" <?= (isset($filters['semester']) && $filters['semester'] === 'Summer') ? 'selected' : '' ?>>Summer</option>
        </select>
        
        <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm bg-white" id="academicYearFilter">
            <option value="">All Academic Years</option>
            <option value="2023-2024" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2023-2024') ? 'selected' : '' ?>>2023-2024</option>
            <option value="2024-2025" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2024-2025') ? 'selected' : '' ?>>2024-2025</option>
            <option value="2025-2026" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2025-2026') ? 'selected' : '' ?>>2025-2026</option>
        </select>
    </div>
</div>



<!-- Campus Statistics -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
        <h2 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="bi bi-building mr-3 text-green-600"></i>
            Campus Liquidation Statistics
        </h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php if (!empty($campusStats)): ?>
                <?php foreach ($campusStats as $campusName => $stats): ?>
                <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-green-100">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-base font-bold text-gray-900"><?= esc($campusName) ?></h3>
                            <i class="bi bi-mortarboard text-2xl text-green-600"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Manual Recipients:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= number_format($stats['manual']['recipient_count'] ?? 0) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">ATM Records:</span>
                                <span class="text-sm font-semibold text-gray-900"><?= number_format($stats['atm']['total_records'] ?? 0) ?></span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-green-200">
                                <span class="text-xs font-semibold text-green-700">Total:</span>
                                <span class="text-base font-bold text-green-700"><?= number_format(($stats['manual']['recipient_count'] ?? 0) + ($stats['atm']['total_records'] ?? 0)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-8">
                    <i class="bi bi-building text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500">No campus data available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                <i class="bi bi-bar-chart mr-2 text-green-600"></i>
                Monthly Liquidation Trends
            </h3>
        </div>
        <div class="p-6">
            <canvas id="monthlyTrendChart" class="w-full" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="bi bi-clock-history mr-2 text-green-600"></i>
                Recent Manual Liquidations
            </h3>
            <a href="<?= base_url('manual-liquidation') ?>" class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition-colors">
                View All
            </a>
        </div>
        <div class="p-6">
            <?php if (!empty($recentManual)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Recipient</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Amount</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Status</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach (array_slice($recentManual, 0, 5) as $record): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 text-sm text-gray-900"><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></td>
                            <td class="py-3 text-sm font-medium text-gray-900">₱<?= number_format($record['amount'], 2) ?></td>
                            <td class="py-3">
                                <span class="status-<?= esc($record['status']) ?>">
                                    <?= esc(ucfirst($record['status'])) ?>
                                </span>
                            </td>
                            <td class="py-3 text-sm text-gray-600"><?= date('M j, Y', strtotime($record['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="bi bi-inbox text-4xl text-gray-300 mb-3 block"></i>
                <p class="text-gray-500">No recent manual liquidations found.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="bi bi-cloud-arrow-up mr-2 text-green-600"></i>
                Recent ATM Uploads
            </h3>
            <a href="<?= base_url('atm-liquidation') ?>" class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition-colors">
                View All
            </a>
        </div>
        <div class="p-6">
            <?php if (!empty($recentAtm)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Batch Name</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Records</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Status</th>
                            <th class="text-left py-2 text-sm font-semibold text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach (array_slice($recentAtm, 0, 5) as $batch): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 text-sm text-gray-900"><?= esc($batch['batch_name']) ?></td>
                            <td class="py-3 text-sm font-medium text-gray-900"><?= number_format($batch['total_records']) ?></td>
                            <td class="py-3">
                                <span class="status-<?= esc(str_replace('_', '-', $batch['status'])) ?>">
                                    <?= esc(ucfirst(str_replace('_', ' ', $batch['status']))) ?>
                                </span>
                            </td>
                            <td class="py-3 text-sm text-gray-600"><?= date('M j, Y', strtotime($batch['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="bi bi-inbox text-4xl text-gray-300 mb-3 block"></i>
                <p class="text-gray-500">No recent ATM uploads found.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize charts with sample data
    initializeCharts();
    
    // Filter change handlers
    $('#semesterFilter, #academicYearFilter').change(function() {
        updateDashboard();
    });
});

function initializeCharts() {
    // Monthly Trend Chart with real data
    const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($monthlyTrends['labels']) ?>,
            datasets: [
                {
                    label: 'Manual Liquidations',
                    data: <?= json_encode($monthlyTrends['manual']) ?>,
                    borderColor: 'rgb(22, 163, 74)',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'ATM Batches',
                    data: <?= json_encode($monthlyTrends['atm']) ?>,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
}

function updateDashboard() {
    const semester = $('#semesterFilter').val();
    const academicYear = $('#academicYearFilter').val();
    
    // Update URL with filters
    const params = new URLSearchParams();
    if (semester) params.set('semester', semester);
    if (academicYear) params.set('academic_year', academicYear);
    
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.pushState({}, '', newUrl);
    
    // Reload page with new filters
    window.location.reload();
}
</script>
<?= $this->endSection() ?>