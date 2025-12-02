<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Review Liquidation - <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-50/30 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= base_url('scholarship-coordinator') ?>" class="text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="bi bi-house-door mr-2"></i>Coordinator Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="<?= base_url('scholarship-coordinator/manage-liquidations') ?>" class="text-gray-500 hover:text-indigo-600 transition-colors ml-1 md:ml-2">
                            Manage Liquidations
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 ml-1 md:ml-2 font-medium">Review Liquidation</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center mb-2">
                        <i class="bi bi-file-earmark-check text-indigo-600 mr-3"></i>
                        Review Liquidation
                    </h1>
                    <p class="text-lg text-gray-600">
                        Voucher #<?= esc($liquidation['voucher_number']) ?> - 
                        <?= esc($liquidation['first_name']) ?> <?= esc($liquidation['last_name']) ?>
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="<?= base_url('scholarship-coordinator/manage-liquidations') ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Back to Management
                    </a>
                    <button onclick="printLiquidation()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="bi bi-printer mr-2"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recipient Information -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="bi bi-person-circle mr-3"></i>
                            Recipient Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?= esc($liquidation['first_name']) ?> <?= esc($liquidation['middle_name']) ?> <?= esc($liquidation['last_name']) ?>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Code</label>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['recipient_code']) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                                <p class="text-lg text-gray-900"><?= esc($liquidation['campus']) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                <p class="text-lg text-gray-900"><?= esc($liquidation['course']) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                <p class="text-lg text-gray-900"><?= esc($liquidation['year_level']) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                <p class="text-lg text-gray-900"><?= esc($liquidation['contact_number']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liquidation Details -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="bi bi-receipt mr-3"></i>
                            Liquidation Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Number</label>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($liquidation['voucher_number']) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                <p class="text-2xl font-bold text-green-600">₱<?= number_format($liquidation['amount'], 2) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Liquidation Date</label>
                                <p class="text-lg text-gray-900"><?= date('F j, Y', strtotime($liquidation['liquidation_date'])) ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submission Date</label>
                                <p class="text-lg text-gray-900"><?= date('F j, Y g:i A', strtotime($liquidation['created_at'])) ?></p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Academic Period</label>
                                <p class="text-lg text-gray-900">
                                    <?= esc($liquidation['semester']) ?> <?= esc($liquidation['academic_year']) ?>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($liquidation['remarks'])): ?>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-900"><?= nl2br(esc($liquidation['remarks'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Documents/Attachments -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="bi bi-paperclip mr-3"></i>
                            Supporting Documents
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if (!empty($liquidation['attachments'])): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($liquidation['attachments'] as $attachment): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-2">
                                            <i class="bi bi-file-earmark text-2xl text-gray-400"></i>
                                            <span class="text-xs text-gray-500"><?= esc($attachment['type']) ?></span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 mb-1"><?= esc($attachment['filename']) ?></p>
                                        <p class="text-xs text-gray-500 mb-3">
                                            <?= esc($attachment['size']) ?> • 
                                            <?= date('M j, Y', strtotime($attachment['uploaded_at'])) ?>
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="<?= base_url('attachments/' . $attachment['file_path']) ?>" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition-colors">
                                                <i class="bi bi-eye mr-1"></i>View
                                            </a>
                                            <a href="<?= base_url('attachments/' . $attachment['file_path']) ?>" 
                                               download
                                               class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition-colors">
                                                <i class="bi bi-download mr-1"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="bi bi-file-earmark-x text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600">No supporting documents attached</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Status and Actions -->
            <div class="lg:col-span-1">
                <!-- Current Status -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Current Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold
                                <?php 
                                switch($liquidation['status']) {
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'verified': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'approved': echo 'bg-green-100 text-green-800'; break;
                                    case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <i class="bi bi-circle-fill mr-2 text-sm"></i>
                                <?= esc(ucwords($liquidation['status'])) ?>
                            </div>
                        </div>

                        <?php if (!empty($liquidation['processed_by'])): ?>
                            <div class="mt-4 text-sm text-gray-600 text-center">
                                <p>Processed by: <span class="font-medium"><?= esc($liquidation['processed_by']) ?></span></p>
                                <p>On: <?= date('M j, Y g:i A', strtotime($liquidation['processed_at'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <?php if (in_array($liquidation['status'], ['pending', 'verified'])): ?>
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Quick Actions -->
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="updateStatus('approved')" 
                                        class="flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Approve
                                </button>
                                <button onclick="updateStatus('rejected')" 
                                        class="flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-x-circle mr-2"></i>
                                    Reject
                                </button>
                            </div>

                            <!-- Comments -->
                            <div>
                                <label for="coordinator_remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                    Coordinator Remarks
                                </label>
                                <textarea id="coordinator_remarks" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                          placeholder="Add any comments or feedback..."></textarea>
                            </div>

                            <!-- Submit Review -->
                            <button onclick="submitReview()" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                                <i class="bi bi-send mr-2"></i>
                                Submit Review
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- History -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Review History</h3>
                    </div>
                    <div class="p-6">
                        <?php if (!empty($liquidation['history'])): ?>
                            <div class="space-y-4">
                                <?php foreach ($liquidation['history'] as $entry): ?>
                                    <div class="flex">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-person text-indigo-600 text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                <?= esc($entry['action']) ?>
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                by <?= esc($entry['user']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= date('M j, Y g:i A', strtotime($entry['created_at'])) ?>
                                            </p>
                                            <?php if (!empty($entry['remarks'])): ?>
                                                <p class="text-sm text-gray-700 mt-1 italic">
                                                    "<?= esc($entry['remarks']) ?>"
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-600 text-center py-4">
                                No review history available
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedStatus = '';

function updateStatus(status) {
    selectedStatus = status;
    
    // Update button states
    document.querySelectorAll('[onclick*="updateStatus"]').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-offset-2');
    });
    
    document.querySelector(`[onclick="updateStatus('${status}')"]`).classList.add('ring-2', 'ring-offset-2', 'ring-indigo-500');
}

function submitReview() {
    if (!selectedStatus) {
        alert('Please select an action (Approve or Reject)');
        return;
    }

    const remarks = document.getElementById('coordinator_remarks').value;
    
    if (selectedStatus === 'rejected' && !remarks.trim()) {
        alert('Please provide remarks when rejecting a liquidation');
        return;
    }

    const confirmMessage = selectedStatus === 'approved' 
        ? 'Are you sure you want to approve this liquidation?'
        : 'Are you sure you want to reject this liquidation?';
    
    if (confirm(confirmMessage)) {
        const formData = new FormData();
        formData.append('liquidation_id', <?= $liquidation['id'] ?>);
        formData.append('status', selectedStatus);
        formData.append('remarks', remarks);

        fetch('<?= base_url('scholarship-coordinator/update-liquidation-status') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Liquidation status updated successfully');
                location.reload();
            } else {
                alert(data.message || 'Failed to update liquidation status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }
}

function printLiquidation() {
    window.print();
}

// Print styles
const style = document.createElement('style');
style.innerHTML = `
    @media print {
        .no-print, nav, button, .actions { display: none !important; }
        .print-only { display: block !important; }
        body { background: white; }
        .shadow-lg { box-shadow: none; border: 1px solid #ccc; }
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>