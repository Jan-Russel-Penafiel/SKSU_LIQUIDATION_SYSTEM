<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-8 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-3 rounded-xl shadow-lg mr-4">
                        <i class="bi bi-eye text-white text-xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-green-800">Liquidation Details</h1>
                </div>
                
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm">
                    <a href="<?= base_url('dashboard') ?>" class="text-green-600 hover:text-green-800 font-medium transition-colors">
                        Dashboard
                    </a>
                    <i class="bi bi-chevron-right text-green-400"></i>
                    <a href="<?= base_url('manual-liquidation') ?>" class="text-green-600 hover:text-green-800 font-medium transition-colors">
                        Manual Liquidation
                    </a>
                    <i class="bi bi-chevron-right text-green-400"></i>
                    <span class="text-green-800 font-semibold">View Details</span>
                </nav>
            </div>
        </div>
        <!-- Main Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Liquidation Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 h-fit">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-file-text mr-3 text-xl"></i>
                        Liquidation Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Voucher Number:</span>
                            <span class="text-green-700"><?= esc($liquidation['voucher_number']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Amount:</span>
                            <span class="text-2xl font-bold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Liquidation Date:</span>
                            <span class="text-green-700"><?= date('F d, Y', strtotime($liquidation['liquidation_date'])) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Semester:</span>
                            <span class="text-green-700"><?= esc($liquidation['semester']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Academic Year:</span>
                            <span class="text-green-700"><?= esc($liquidation['academic_year']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="font-semibold text-green-800">Campus:</span>
                            <span class="text-green-700"><?= esc($liquidation['campus']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 h-fit">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-person mr-3 text-xl"></i>
                        Recipient Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Recipient ID:</span>
                            <span class="text-green-700"><?= esc($liquidation['recipient_code']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Full Name:</span>
                            <span class="text-green-700"><?= esc($liquidation['first_name'] . ' ' . ($liquidation['middle_name'] ?? '') . ' ' . $liquidation['last_name']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Email:</span>
                            <span class="text-green-700"><?= esc($liquidation['recipient_email'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Course:</span>
                            <span class="text-green-700"><?= esc($liquidation['course'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Year Level:</span>
                            <span class="text-green-700"><?= esc($liquidation['year_level'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="font-semibold text-green-800">Campus:</span>
                            <span class="text-green-700"><?= esc($liquidation['recipient_campus'] ?? $liquidation['campus']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personnel Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Disbursing Officer -->
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20">
                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-person-badge mr-3 text-xl"></i>
                        Disbursing Officer
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Name:</span>
                            <span class="text-green-700"><?= esc($liquidation['disbursing_officer_name'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="font-semibold text-green-800">Email:</span>
                            <span class="text-green-700"><?= esc($liquidation['officer_email'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholarship Coordinator -->
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-person-workspace mr-3 text-xl"></i>
                        Scholarship Coordinator
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-green-100">
                            <span class="font-semibold text-green-800">Name:</span>
                            <span class="text-green-700"><?= esc($liquidation['coordinator_name'] ?? 'N/A') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="font-semibold text-green-800">Email:</span>
                            <span class="text-green-700"><?= esc($liquidation['coordinator_email'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description and Remarks -->
        <?php if (!empty($liquidation['description']) || !empty($liquidation['remarks'])): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <?php if (!empty($liquidation['description'])): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-file-earmark-text mr-3 text-xl"></i>
                        Description
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-green-700 leading-relaxed"><?= esc($liquidation['description']) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($liquidation['remarks'])): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-green-200/20">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-t-2xl">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="bi bi-chat-left-text mr-3 text-xl"></i>
                        Remarks
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-green-700 leading-relaxed"><?= esc($liquidation['remarks']) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-6 rounded-t-2xl">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="bi bi-clock-history mr-3 text-xl"></i>
                    Timeline
                </h3>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="absolute left-8 top-0 h-full w-0.5 bg-green-200"></div>
                    
                    <!-- Created Event -->
                    <div class="relative flex items-start mb-8 last:mb-0">
                        <div class="flex-shrink-0 w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-lg border-4 border-white relative z-10">
                            <i class="bi bi-plus-circle text-white text-xl"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <h4 class="text-lg font-semibold text-green-800 mb-2">Created</h4>
                                <p class="text-green-700 mb-2">Liquidation entry was created</p>
                                <div class="text-sm text-green-600">
                                    <?= date('F d, Y g:i A', strtotime($liquidation['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($liquidation['status'] != 'pending'): ?>
                    <!-- Status Change Event -->
                    <div class="relative flex items-start mb-8 last:mb-0">
                        <div class="flex-shrink-0 w-16 h-16 <?= $liquidation['status'] == 'approved' ? 'bg-green-500' : ($liquidation['status'] == 'verified' ? 'bg-blue-500' : 'bg-red-500') ?> rounded-full flex items-center justify-center shadow-lg border-4 border-white relative z-10">
                            <i class="bi bi-<?= $liquidation['status'] == 'approved' ? 'check-circle' : ($liquidation['status'] == 'verified' ? 'shield-check' : 'x-circle') ?> text-white text-xl"></i>
                        </div>
                        <div class="ml-6 flex-1">
                            <div class="<?= $liquidation['status'] == 'approved' ? 'bg-green-50 border-green-200' : ($liquidation['status'] == 'verified' ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200') ?> border rounded-xl p-4">
                                <h4 class="text-lg font-semibold <?= $liquidation['status'] == 'approved' ? 'text-green-800' : ($liquidation['status'] == 'verified' ? 'text-blue-800' : 'text-red-800') ?> mb-2">
                                    <?= ucfirst($liquidation['status']) ?>
                                </h4>
                                <p class="<?= $liquidation['status'] == 'approved' ? 'text-green-700' : ($liquidation['status'] == 'verified' ? 'text-blue-700' : 'text-red-700') ?> mb-2">
                                    Status changed to <?= $liquidation['status'] ?>
                                </p>
                                <div class="text-sm <?= $liquidation['status'] == 'approved' ? 'text-green-600' : ($liquidation['status'] == 'verified' ? 'text-blue-600' : 'text-red-600') ?>">
                                    <?= date('F d, Y g:i A', strtotime($liquidation['updated_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white rounded-2xl shadow-lg border border-green-200/20 p-6">
            <div>
                <a href="<?= base_url('manual-liquidation') ?>" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>

            <div class="flex flex-wrap gap-3">
                <?php if ($liquidation['status'] == 'pending'): ?>
                    <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'verified')" 
                            class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="bi bi-check-lg mr-2"></i>
                        Verify
                    </button>
                    <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'approved')" 
                            class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="bi bi-check2-all mr-2"></i>
                        Approve
                    </button>
                    <button onclick="rejectLiquidation(<?= $liquidation['id'] ?>)" 
                            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="bi bi-x-lg mr-2"></i>
                        Reject
                    </button>
                <?php elseif ($liquidation['status'] == 'verified'): ?>
                    <button onclick="updateStatus(<?= $liquidation['id'] ?>, 'approved')" 
                            class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="bi bi-check2-all mr-2"></i>
                        Approve
                    </button>
                    <button onclick="rejectLiquidation(<?= $liquidation['id'] ?>)" 
                            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                        <i class="bi bi-x-lg mr-2"></i>
                        Reject
                    </button>
                <?php endif; ?>
                
                <a href="<?= base_url('manual-liquidation/edit/' . $liquidation['id']) ?>" 
                   class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-105">
                    <i class="bi bi-pencil mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="rejectModalContent">
        <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold">Reject Liquidation</h3>
                <button type="button" onclick="closeRejectModal()" class="text-white hover:text-red-200 text-2xl font-bold">
                    &times;
                </button>
            </div>
        </div>
        <div class="p-6">
            <form id="rejectForm">
                <input type="hidden" id="reject_liquidation_id">
                <div class="mb-6">
                    <label for="reject_reason" class="block text-sm font-semibold text-green-800 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reject_reason" rows="4" required
                              class="w-full px-4 py-3 border-2 border-green-200 rounded-xl focus:border-green-500 focus:outline-none transition-colors resize-none" 
                              placeholder="Please provide a detailed reason for rejection"></textarea>
                </div>
            </form>
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-green-100">
            <button type="button" onclick="closeRejectModal()" 
                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                Cancel
            </button>
            <button type="button" onclick="submitRejection()" 
                    class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all duration-200 flex items-center">
                <i class="bi bi-x-lg mr-2"></i>
                Reject Liquidation
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateStatus(liquidationId, status) {
    if (confirm(`Are you sure you want to ${status} this liquidation?`)) {
        $.ajax({
            url: '<?= base_url('manual-liquidation/update-status') ?>',
            method: 'POST',
            data: {
                id: liquidationId,
                status: status,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to update status: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating liquidation status.');
            }
        });
    }
}

function rejectLiquidation(liquidationId) {
    document.getElementById('reject_liquidation_id').value = liquidationId;
    document.getElementById('reject_reason').value = '';
    showRejectModal();
}

function showRejectModal() {
    const modal = document.getElementById('rejectModal');
    const modalContent = document.getElementById('rejectModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    const modalContent = document.getElementById('rejectModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

function submitRejection() {
    const liquidationId = document.getElementById('reject_liquidation_id').value;
    const reason = document.getElementById('reject_reason').value;

    if (!reason.trim()) {
        alert('Please provide a reason for rejection.');
        return;
    }

    fetch('<?= base_url('manual-liquidation/update-status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            id: liquidationId,
            status: 'rejected',
            remarks: reason,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRejectModal();
            location.reload();
        } else {
            alert('Failed to reject liquidation: ' + data.message);
        }
    })
    .catch(() => {
        alert('Error rejecting liquidation.');
    });
}
</script>
<?= $this->endSection() ?>