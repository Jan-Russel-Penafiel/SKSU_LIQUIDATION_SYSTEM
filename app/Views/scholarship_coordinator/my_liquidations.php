<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-list-task mr-3 text-green-600"></i>
            My Assigned Liquidations
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Liquidations assigned to you as coordinator</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-6">
    <div class="p-6">
            <form method="GET" action="<?= current_url() ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <option value="">All Status</option>
                            <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="verified" <?= $filters['status'] === 'verified' ? 'selected' : '' ?>>Verified</option>
                            <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $filters['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

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

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="bi bi-funnel mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

<!-- Liquidations Table -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-text mr-2 text-green-600"></i>
            Assigned Liquidations (<?= count($liquidations) ?>)
        </h2>
    </div>

            <?php if (empty($liquidations)): ?>
                <div class="p-12 text-center">
                    <i class="bi bi-inbox text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No Liquidations Found</h3>
                    <p class="text-gray-600">No liquidations match your current filters.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Recipient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Voucher
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($liquidations as $liquidation): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <i class="bi bi-person-fill text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: <?= esc($liquidation['recipient_code']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= esc($liquidation['voucher_number']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= esc($liquidation['semester']) ?> <?= esc($liquidation['academic_year']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            ₱<?= number_format($liquidation['amount'], 2) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= date('g:i A', strtotime($liquidation['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="<?= base_url('manual-liquidation/show/' . $liquidation['id']) ?>" 
                                               class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded text-xs transition-colors">
                                                <i class="bi bi-eye mr-1"></i>View
                                            </a>
                                            
                                            <?php if ($liquidation['status'] === 'pending'): ?>
                                                <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'verified')" 
                                                        class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded text-xs transition-colors">
                                                    <i class="bi bi-check-lg mr-1"></i>Verify
                                                </button>
                                                <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'rejected')" 
                                                        class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded text-xs transition-colors">
                                                    <i class="bi bi-x-lg mr-1"></i>Reject
                                                </button>
                                            <?php elseif ($liquidation['status'] === 'verified'): ?>
                                                <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'approved')" 
                                                        class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded text-xs transition-colors">
                                                    <i class="bi bi-check-circle mr-1"></i>Approve
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

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Update Status</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4" id="modalMessage">
                    Are you sure you want to update the status of this liquidation?
                </p>
                <textarea id="remarksInput" class="w-full p-2 border border-gray-300 rounded-md" 
                          placeholder="Optional remarks..." rows="3"></textarea>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex justify-end space-x-3">
                    <button onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                        Cancel
                    </button>
                    <button onclick="confirmStatusUpdate()" 
                            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none" 
                            id="confirmBtn">
                        Confirm
                    </button>
        </div>
    </div>
</div>

<script>
let currentLiquidationId = null;
let currentStatus = null;

function updateStatus(liquidationId, status) {
    currentLiquidationId = liquidationId;
    currentStatus = status;
    
    const modal = document.getElementById('statusModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const confirmBtn = document.getElementById('confirmBtn');
    
    modalTitle.textContent = `${status.charAt(0).toUpperCase() + status.slice(1)} Liquidation`;
    modalMessage.textContent = `Are you sure you want to ${status} this liquidation?`;
    
    if (status === 'verified') {
        confirmBtn.className = 'px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none';
    } else if (status === 'approved') {
        confirmBtn.className = 'px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none';
    } else if (status === 'rejected') {
        confirmBtn.className = 'px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('remarksInput').value = '';
    currentLiquidationId = null;
    currentStatus = null;
}

function confirmStatusUpdate() {
    const remarks = document.getElementById('remarksInput').value;
    
    fetch('<?= base_url('scholarship-coordinator/update-status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${currentLiquidationId}&status=${currentStatus}&remarks=${encodeURIComponent(remarks)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
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

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
<?= $this->endSection() ?>