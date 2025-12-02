<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Disbursement List
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-cash-stack mr-3 text-green-200"></i>
                        Disbursement List
                    </h1>
                    <nav aria-label="breadcrumb" class="flex items-center space-x-2 text-sm">
                        <a href="<?= base_url('dashboard') ?>" class="text-green-100 hover:text-white transition-colors">
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <span class="text-green-200">Disbursement List</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter Section -->
                <div class="mb-6">
                    <form method="GET" action="<?= base_url('disbursement') ?>" class="bg-gray-50 rounded-xl p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                                <input type="date" name="date_from" value="<?= esc($filters['date_from']) ?>" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                                <input type="date" name="date_to" value="<?= esc($filters['date_to']) ?>" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-funnel mr-2"></i>
                                Apply Filters
                            </button>
                            <a href="<?= base_url('disbursement') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-arrow-clockwise mr-2"></i>
                                Reset
                            </a>
                            <a href="<?= base_url('disbursement/by-officer') ?>" class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="bi bi-person-badge mr-2"></i>
                                View by Officer
                            </a>
                            <a href="<?= base_url('disbursement/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Add Scholarship Disbursement
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Individual Disbursements -->    
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-list mr-2 text-green-600"></i>
                            Individual Disbursements
                        </h2>
                    </div>

                    <?php if (empty($disbursements)): ?>
                        <div class="text-center py-12">
                            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-inbox text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No disbursements found</h3>
                            <p class="text-gray-500">No disbursements match your current filter criteria.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 data-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($disbursements as $disbursement): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?= esc($disbursement['id']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium"><?= esc($disbursement['recipient_name']) ?></div>
                                            <div class="text-xs text-gray-500"><?= esc($disbursement['recipient_id']) ?></div>
                                            <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full status-<?= esc($disbursement['status']) ?> mt-1">
                                                <?= esc(ucwords(str_replace('_', ' ', $disbursement['status']))) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                            ₱<?= number_format($disbursement['amount'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-medium text-gray-900"><?= esc($disbursement['campus']) ?></div>
                                            <div class="text-xs text-gray-500"><?= esc($disbursement['officer_name'] ?? $disbursement['username'] ?? 'Unassigned') ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($disbursement['disbursement_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="<?= base_url('disbursement/show/' . $disbursement['id']) ?>" 
                                                   class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                                    <i class="bi bi-eye mr-1"></i>
                                                    View
                                                </a>
                                                
                                                <?php if ($disbursement['status'] === 'pending' && $user['role'] === 'disbursing_officer'): ?>
                                                <button type="button" 
                                                        onclick="verifyDisbursement(<?= $disbursement['id'] ?>)"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                    <i class="bi bi-check-circle mr-1"></i>
                                                    Verify
                                                </button>
                                                <?php endif; ?>

                                                <?php if (in_array($disbursement['status'], ['pending', 'verified']) && in_array($user['role'], ['administrator', 'admin', 'chairman'])): ?>
                                                <button type="button" 
                                                        onclick="approveDisbursement(<?= $disbursement['id'] ?>)"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                    <i class="bi bi-shield-check mr-1"></i>
                                                    Approve
                                                </button>
                                                <?php elseif ($disbursement['status'] === 'verified' && $user['role'] === 'disbursing_officer'): ?>
                                                <button type="button" 
                                                        onclick="approveDisbursement(<?= $disbursement['id'] ?>)"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                    <i class="bi bi-shield-check mr-1"></i>
                                                    Approve
                                                </button>
                                                <?php endif; ?>
                                            </div>
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

<script>
function verifyDisbursement(disbursementId) {
    if (!confirm('Are you sure you want to verify this disbursement?')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    
    // Disable button and show loading
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass mr-1"></i>Verifying...';
    btn.className = btn.className.replace('bg-blue-600 hover:bg-blue-700', 'bg-gray-400');

    fetch(`<?= base_url('disbursement/verify') ?>/${disbursementId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Failed to verify disbursement');
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.className = btn.className.replace('bg-gray-400', 'bg-blue-600 hover:bg-blue-700');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while verifying the disbursement');
        btn.disabled = false;
        btn.innerHTML = originalContent;
        btn.className = btn.className.replace('bg-gray-400', 'bg-blue-600 hover:bg-blue-700');
    });
}

function approveDisbursement(disbursementId) {
    if (!confirm('Are you sure you want to approve this disbursement? This action will transform it into a liquidation record.')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    
    // Disable button and show loading
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass mr-1"></i>Approving...';
    btn.className = btn.className.replace('bg-green-600 hover:bg-green-700', 'bg-gray-400');

    fetch(`<?= base_url('disbursement/approve') ?>/${disbursementId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message || 'Failed to approve disbursement');
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.className = btn.className.replace('bg-gray-400', 'bg-green-600 hover:bg-green-700');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while approving the disbursement');
        btn.disabled = false;
        btn.innerHTML = originalContent;
        btn.className = btn.className.replace('bg-gray-400', 'bg-green-600 hover:bg-green-700');
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-100 border border-green-200 text-green-800' : 'bg-red-100 border border-red-200 text-red-800'
    }`;
    
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="bi bi-x"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<style>
.status-pending { @apply bg-yellow-100 text-yellow-800; }
.status-verified { @apply bg-blue-100 text-blue-800; }
.status-approved { @apply bg-green-100 text-green-800; }
.status-rejected { @apply bg-red-100 text-red-800; }
.status-disbursed { @apply bg-purple-100 text-purple-800; }
</style>

<?= $this->endSection() ?>