<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Disbursement Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-green-600 transition-colors">
                        <i class="bi bi-house-door mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="<?= base_url('disbursement') ?>" class="text-gray-500 hover:text-green-600 ml-1 md:ml-2 transition-colors">Disbursements</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 ml-1 md:ml-2 font-medium">Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-cash-stack mr-3 text-green-200"></i>
                        Disbursement Details #<?= esc($disbursement['id']) ?>
                    </h1>
                    <div class="flex items-center space-x-2">
                        <span class="status-<?= esc($disbursement['status']) ?> text-white px-3 py-1 rounded-full text-sm font-medium">
                            <?= esc(ucwords(str_replace('_', ' ', $disbursement['status']))) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Recipient Information -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-person mr-2 text-green-600"></i>
                        Recipient Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['recipient_name']) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Recipient/Student ID</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['recipient_id']) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Course/Program</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['course_program']) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Year Level</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['year_level']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-calendar-academic mr-2 text-green-600"></i>
                        Academic Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['semester']) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['academic_year']) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Campus</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['campus']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Disbursement Details -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-cash-stack mr-2 text-green-600"></i>
                        Disbursement Details
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Scholarship Type</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($disbursement['scholarship_type']) ?></p>
                        </div>

                        <div class="bg-gradient-to-r from-green-100 to-green-50 p-4 rounded-lg border-l-4 border-green-500">
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <p class="mt-1 text-lg font-bold text-green-700">₱<?= number_format($disbursement['amount'], 2) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Disbursement Method</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc(str_replace('_', ' ', $disbursement['disbursement_method'])) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Disbursement Date</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= date('F j, Y', strtotime($disbursement['disbursement_date'])) ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Disbursing Officer</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900"><?= esc($officer['username'] ?? 'N/A') ?></p>
                            <p class="text-xs text-gray-500"><?= esc($officer['campus'] ?? '') ?></p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 inline-flex px-2.5 py-1 text-xs font-medium rounded-full status-<?= esc($disbursement['status']) ?>">
                                <?= esc(ucwords(str_replace('_', ' ', $disbursement['status']))) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <?php if (!empty($disbursement['remarks'])): ?>
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-chat-left-text mr-2 text-green-600"></i>
                        Remarks
                    </h2>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-700"><?= esc($disbursement['remarks']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Timeline/History -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-clock-history mr-2 text-green-600"></i>
                        Timeline
                    </h2>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-plus text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Disbursement Created</p>
                                    <p class="text-xs text-gray-500"><?= date('F j, Y g:i A', strtotime($disbursement['created_at'])) ?></p>
                                </div>
                            </div>

                            <?php if ($disbursement['verification_date']): ?>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Verified</p>
                                    <p class="text-xs text-gray-500"><?= date('F j, Y g:i A', strtotime($disbursement['verification_date'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($disbursement['approval_date']): ?>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-shield-check text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Approved</p>
                                    <p class="text-xs text-gray-500"><?= date('F j, Y g:i A', strtotime($disbursement['approval_date'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($disbursement['disbursed_date']): ?>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-cash text-purple-600 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Disbursed</p>
                                    <p class="text-xs text-gray-500"><?= date('F j, Y g:i A', strtotime($disbursement['disbursed_date'])) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <a href="<?= base_url('disbursement') ?>" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Back to List
                    </a>
                    
                    <a href="<?= base_url('manual-liquidation') ?>" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="bi bi-list-check mr-2"></i>
                        View in Manual Liquidations
                    </a>

                    <?php if ($disbursement['status'] === 'approved'): ?>
                    <div class="inline-flex items-center justify-center px-6 py-3 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        Disbursement Approved & Liquidated
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<style>
.status-pending { @apply bg-yellow-100 text-yellow-800; }
.status-verified { @apply bg-blue-100 text-blue-800; }
.status-approved { @apply bg-green-100 text-green-800; }
.status-rejected { @apply bg-red-100 text-red-800; }
.status-disbursed { @apply bg-purple-100 text-purple-800; }
</style>