<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Officer Disbursements - <?= esc($officer['username']) ?>
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
                        Disbursements - <?= esc($officer['username']) ?>
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
                        <span class="text-green-200"><?= esc($officer['username']) ?></span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Officer Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i>
                        Officer Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <div><span class="font-medium text-blue-900">Username:</span> <span class="text-blue-800"><?= esc($officer['username']) ?></span></div>
                            <div><span class="font-medium text-blue-900">Email:</span> <span class="text-blue-800"><?= esc($officer['email']) ?></span></div>
                        </div>
                        <div class="space-y-2">
                            <div><span class="font-medium text-blue-900">Role:</span> <span class="text-blue-800"><?= esc(ucwords(str_replace('_', ' ', $officer['role']))) ?></span></div>
                            <div><span class="font-medium text-blue-900">Campus:</span> <span class="text-blue-800"><?= esc($officer['campus']) ?></span></div>
                        </div>
                        <div class="space-y-2">
                            <div><span class="font-medium text-blue-900">Status:</span> 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $officer['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $officer['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-file-text text-3xl text-blue-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-200">Total Disbursements</p>
                                <p class="text-2xl font-bold"><?= number_format($stats['total']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-currency-exchange text-3xl text-green-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-200">Total Amount</p>
                                <p class="text-2xl font-bold">₱<?= number_format($stats['total_amount'], 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock text-3xl text-yellow-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-200">Pending</p>
                                <p class="text-2xl font-bold"><?= number_format($stats['pending']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle text-3xl text-emerald-200"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-emerald-200">Approved</p>
                                <p class="text-2xl font-bold"><?= number_format($stats['approved']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="mb-6">
                    <form method="GET" action="<?= base_url('disbursement/officer/' . $officer['id']) ?>" class="bg-gray-50 rounded-xl p-4">
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
                            <a href="<?= base_url('disbursement/officer/' . $officer['id']) ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-clockwise mr-2"></i>
                                Reset
                            </a>
                            <a href="<?= base_url('disbursement') ?>" class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-left mr-2"></i>
                                Back to Summary
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Disbursements Table -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-list-ul mr-2 text-green-600"></i>
                        Disbursement Details
                    </h2>

                    <?php if (empty($disbursements)): ?>
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-inbox text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No disbursements found</h3>
                            <p class="text-gray-500">No disbursements match your current filter criteria for this officer.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 data-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($disbursements as $disbursement): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= esc($disbursement['id']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= esc($disbursement['recipient_name']) ?>
                                            <div class="text-xs text-gray-500"><?= esc($disbursement['recipient_id']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= esc($disbursement['campus']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                            ₱<?= number_format($disbursement['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($disbursement['disbursement_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-<?= $disbursement['status'] ?>">
                                                <?= esc(ucfirst($disbursement['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="<?= base_url('disbursement/show/' . $disbursement['id']) ?>" 
                                               class="text-indigo-600 hover:text-indigo-900" title="View Details">
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
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<style>
.status-pending {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800;
}

.status-verified {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800;
}

.status-approved {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800;
}

.status-rejected {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800;
}

.status-disbursed {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800;
}
</style>