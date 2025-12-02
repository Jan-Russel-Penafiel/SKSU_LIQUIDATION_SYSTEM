<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Disbursements by Officer
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-person-badge mr-3 text-green-200"></i>
                        Disbursements by Officer
                    </h1>
                    <nav aria-label="breadcrumb" class="flex items-center space-x-2 text-sm">
                        <a href="<?= base_url('dashboard') ?>" class="text-green-100 hover:text-white transition-colors">
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <a href="<?= base_url('disbursement') ?>" class="text-green-100 hover:text-white transition-colors">
                            Disbursement List
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <span class="text-green-200">By Officer</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter Section -->
                <div class="mb-6">
                    <form method="GET" action="<?= base_url('disbursement/by-officer') ?>" class="bg-gray-50 rounded-xl p-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                                <select name="campus" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Campuses</option>
                                    <option value="Main Campus" <?= $filters['campus'] === 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                    <option value="Kalamansig Campus" <?= $filters['campus'] === 'Kalamansig Campus' ? 'selected' : '' ?>>Kalamansig Campus</option>
                                    <option value="Palimbang Campus" <?= $filters['campus'] === 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                    <option value="Isulan Campus" <?= $filters['campus'] === 'Isulan Campus' ? 'selected' : '' ?>>Isulan Campus</option>
                                    <option value="Bagumbayan Campus" <?= $filters['campus'] === 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                <select name="semester" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Semesters</option>
                                    <option value="1st Semester" <?= $filters['semester'] === '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                                    <option value="2nd Semester" <?= $filters['semester'] === '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                                    <option value="Summer" <?= $filters['semester'] === 'Summer' ? 'selected' : '' ?>>Summer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                                <input type="text" name="academic_year" value="<?= esc($filters['academic_year']) ?>" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="2024-2025">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">All Status</option>
                                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="verified" <?= $filters['status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                                    <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $filters['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-funnel mr-2"></i>
                                Apply Filters
                            </button>
                            <a href="<?= base_url('disbursement/by-officer') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-clockwise mr-2"></i>
                                Reset
                            </button>
                            <a href="<?= base_url('disbursement') ?>" class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-left mr-2"></i>
                                Back to Summary
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Officers Statistics -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-bar-chart mr-2 text-green-600"></i>
                        Disbursing Officers Performance
                    </h2>

                    <?php if (empty($officer_stats)): ?>
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-person-x text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No officers found</h3>
                            <p class="text-gray-500">No disbursing officers match your current filter criteria.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($officer_stats as $officer_stat): ?>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-6">
                                    <!-- Officer Info -->
                                    <div class="flex items-center mb-4">
                                        <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                            <i class="bi bi-person-badge text-green-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900"><?= esc($officer_stat['officer']['username']) ?></h3>
                                            <p class="text-sm text-gray-500"><?= esc($officer_stat['officer']['campus']) ?></p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $officer_stat['officer']['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= $officer_stat['officer']['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Statistics -->
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Total Disbursements:</span>
                                            <span class="font-semibold text-gray-900"><?= number_format($officer_stat['manual_stats']['total']) ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Total Amount:</span>
                                            <span class="font-semibold text-green-600">₱<?= number_format($officer_stat['manual_stats']['total_amount'], 2) ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Pending:</span>
                                            <span class="font-semibold text-yellow-600"><?= number_format($officer_stat['manual_stats']['pending']) ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Approved:</span>
                                            <span class="font-semibold text-green-600"><?= number_format($officer_stat['manual_stats']['approved']) ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Rejected:</span>
                                            <span class="font-semibold text-red-600"><?= number_format($officer_stat['manual_stats']['rejected']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <?php if ($officer_stat['manual_stats']['total'] > 0): ?>
                                    <div class="mt-4">
                                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                                            <span>Approval Rate</span>
                                            <span><?= number_format(($officer_stat['manual_stats']['approved'] / $officer_stat['manual_stats']['total']) * 100, 1) ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" 
                                                 style="width: <?= ($officer_stat['manual_stats']['approved'] / $officer_stat['manual_stats']['total']) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Action Button -->
                                    <div class="mt-6">
                                        <a href="<?= base_url('disbursement/officer/' . $officer_stat['officer']['id']) ?>" 
                                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <i class="bi bi-eye mr-2"></i>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Summary Statistics -->
                <?php if (!empty($officer_stats)): ?>
                <div class="mt-8 bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-graph-up mr-2 text-green-600"></i>
                        Overall Summary
                    </h3>
                    
                    <?php 
                    $total_officers = count($officer_stats);
                    $total_disbursements = array_sum(array_column(array_column($officer_stats, 'manual_stats'), 'total'));
                    $total_amount = array_sum(array_column(array_column($officer_stats, 'manual_stats'), 'total_amount'));
                    $total_pending = array_sum(array_column(array_column($officer_stats, 'manual_stats'), 'pending'));
                    $total_approved = array_sum(array_column(array_column($officer_stats, 'manual_stats'), 'approved'));
                    ?>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900"><?= $total_officers ?></div>
                            <div class="text-sm text-gray-600">Active Officers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?= number_format($total_disbursements) ?></div>
                            <div class="text-sm text-gray-600">Total Disbursements</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">₱<?= number_format($total_amount, 2) ?></div>
                            <div class="text-sm text-gray-600">Total Amount</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600"><?= number_format($total_pending) ?></div>
                            <div class="text-sm text-gray-600">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-emerald-600"><?= number_format($total_approved) ?></div>
                            <div class="text-sm text-gray-600">Approved</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>