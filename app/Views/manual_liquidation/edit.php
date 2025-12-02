<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="bi bi-pencil-square text-green-100 mr-3 text-xl"></i>
                        Edit Manual Liquidation
                    </h1>
                    <nav class="text-green-100" aria-label="breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li><a href="<?= base_url('dashboard') ?>" class="hover:text-white transition-colors">Dashboard</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li><a href="<?= base_url('manual-liquidation') ?>" class="hover:text-white transition-colors">Manual Liquidation</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li class="text-green-200">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
                        <i class="bi bi-check-circle text-green-600 mr-3 text-lg"></i>
                        <span class="text-green-800"><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center">
                        <i class="bi bi-exclamation-triangle text-red-600 mr-3 text-lg"></i>
                        <span class="text-red-800"><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="bi bi-exclamation-triangle text-red-600 mr-3 text-lg"></i>
                            <span class="text-red-800 font-semibold">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside ml-6 text-red-700">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('manual-liquidation/update/' . $liquidation['id']) ?>" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    
                    <!-- Current Information Display -->
                    <div class="mb-8">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
                            <h6 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                <i class="bi bi-info-circle text-blue-600 mr-2"></i>
                                Current Liquidation Information
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Voucher Number:</span> <?= esc($liquidation['voucher_number']) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Current Amount:</span> ₱<?= number_format($liquidation['amount'], 2) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Status:</span> 
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $liquidation['status'] == 'approved' ? 'bg-green-100 text-green-800' : ($liquidation['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= ucfirst($liquidation['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Recipient:</span> <?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Campus:</span> <?= esc($liquidation['campus']) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Semester:</span> <?= esc($liquidation['semester']) ?> - <?= esc($liquidation['academic_year']) ?>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Officer:</span> <?= esc($liquidation['disbursing_officer_name']) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Coordinator:</span> <?= esc($liquidation['coordinator_name']) ?>
                                    </div>
                                    <div class="text-sm text-blue-700">
                                        <span class="font-semibold">Date:</span> <?= date('M d, Y', strtotime($liquidation['liquidation_date'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="recipient_search" class="block text-sm font-medium text-gray-700">
                                Recipient <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-lg shadow-sm">
                                <input type="text" 
                                       class="flex-1 block w-full px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 text-gray-600 focus:ring-green-500 focus:border-green-500" 
                                       id="recipient_search" 
                                       value="<?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name'] . ' (' . $liquidation['recipient_code'] . ')') ?>" 
                                       readonly>
                                <button class="inline-flex items-center px-4 py-3 border border-l-0 border-gray-300 rounded-r-lg bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                        type="button" 
                                        id="change_recipient_btn">
                                    <i class="bi bi-pencil mr-2"></i>Change
                                </button>
                            </div>
                            <input type="hidden" name="recipient_id" value="<?= $liquidation['recipient_id'] ?>" required>
                        </div>

                        <div class="space-y-2">
                            <label for="disbursing_officer" class="block text-sm font-medium text-gray-700">
                                Disbursing Officer <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                    name="disbursing_officer_id" required>
                                <option value="">Select Officer</option>
                                <?php foreach ($officers as $officer): ?>
                                    <option value="<?= $officer['id'] ?>" <?= $liquidation['disbursing_officer_id'] == $officer['id'] ? 'selected' : '' ?>>
                                        <?= esc($officer['username']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a disbursing officer.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="scholarship_coordinator" class="block text-sm font-medium text-gray-700">
                                Scholarship Coordinator <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                    name="scholarship_coordinator_id" required>
                                <option value="">Select Coordinator</option>
                                <?php foreach ($coordinators as $coordinator): ?>
                                    <option value="<?= $coordinator['id'] ?>" <?= $liquidation['scholarship_coordinator_id'] == $coordinator['id'] ? 'selected' : '' ?>>
                                        <?= esc($coordinator['username'] . ' - ' . $coordinator['campus']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a scholarship coordinator.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="voucher_number" class="block text-sm font-medium text-gray-700">
                                Voucher Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   name="voucher_number" 
                                   value="<?= old('voucher_number', $liquidation['voucher_number']) ?>" required>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please provide a voucher number.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" 
                                       class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                       name="amount" 
                                       step="0.01" 
                                       min="0" 
                                       value="<?= old('amount', $liquidation['amount']) ?>" required>
                            </div>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter a valid amount.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="liquidation_date" class="block text-sm font-medium text-gray-700">
                                Liquidation Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   name="liquidation_date" 
                                   value="<?= old('liquidation_date', $liquidation['liquidation_date']) ?>" required>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a liquidation date.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="semester" class="block text-sm font-medium text-gray-700">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                    name="semester" required>
                                <option value="">Select Semester</option>
                                <option value="1st Semester" <?= old('semester', $liquidation['semester']) == '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                                <option value="2nd Semester" <?= old('semester', $liquidation['semester']) == '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                                <option value="Summer" <?= old('semester', $liquidation['semester']) == 'Summer' ? 'selected' : '' ?>>Summer</option>
                            </select>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a semester.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">
                                Academic Year <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   name="academic_year" 
                                   value="<?= old('academic_year', $liquidation['academic_year']) ?>"
                                   placeholder="e.g., 2024-2025" 
                                   pattern="[0-9]{4}-[0-9]{4}" required>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter a valid academic year (e.g., 2024-2025).</p>
                        </div>

                        <div class="space-y-2">
                            <label for="campus" class="block text-sm font-medium text-gray-700">
                                Campus <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   name="campus" 
                                   value="<?= old('campus', $liquidation['campus']) ?>" required>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter the campus.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                    name="status" required>
                                <option value="pending" <?= old('status', $liquidation['status']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="verified" <?= old('status', $liquidation['status']) == 'verified' ? 'selected' : '' ?>>Verified</option>
                                <option value="approved" <?= old('status', $liquidation['status']) == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= old('status', $liquidation['status']) == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a status.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea class="block w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Enter liquidation description"><?= old('description', $liquidation['description']) ?></textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="space-y-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea class="block w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                      name="remarks" 
                                      rows="3" 
                                      placeholder="Enter any remarks or additional notes"><?= old('remarks', $liquidation['remarks']) ?></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-8 pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
                        <a href="<?= base_url('manual-liquidation') ?>" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <i class="bi bi-arrow-left mr-2"></i>Back to List
                        </a>
                        <div class="flex space-x-4">
                            <a href="<?= base_url('manual-liquidation/show/' . $liquidation['id']) ?>" 
                               class="inline-flex items-center px-6 py-3 border border-blue-300 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i class="bi bi-eye mr-2"></i>View Details
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all">
                                <i class="bi bi-check-lg mr-2"></i>Update Liquidation
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Recipient Search Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50" id="recipientSearchModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
            <h5 class="text-xl font-bold text-white">Change Recipient</h5>
            <button type="button" 
                    class="text-white hover:text-green-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg p-1 transition-colors" 
                    onclick="closeModal()">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-6">
                <label for="modal_search" class="block text-sm font-medium text-gray-700 mb-2">Search Recipients</label>
                <input type="text" 
                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                       id="modal_search" 
                       placeholder="Enter recipient name or ID">
            </div>
            
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="recipientTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="recipientTableBody">
                            <!-- Recipients will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            showValidationErrors(this);
        } else {
            hideValidationErrors(this);
        }
        $(this).addClass('was-validated');
    });

    function showValidationErrors(form) {
        $(form).find('input:invalid, select:invalid, textarea:invalid').each(function() {
            const field = $(this);
            const feedback = field.siblings('.invalid-feedback');
            field.addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
            field.removeClass('border-gray-300 focus:border-green-500 focus:ring-green-500');
            feedback.removeClass('hidden');
        });
    }

    function hideValidationErrors(form) {
        $(form).find('input, select, textarea').each(function() {
            const field = $(this);
            const feedback = field.siblings('.invalid-feedback');
            field.removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
            field.addClass('border-gray-300 focus:border-green-500 focus:ring-green-500');
            feedback.addClass('hidden');
        });
    }

    // Change recipient functionality
    $('#change_recipient_btn').on('click', function() {
        loadRecipients();
        showModal();
    });

    // Search recipients
    $('#modal_search').on('keyup', function() {
        const searchTerm = $(this).val();
        if (searchTerm.length >= 2) {
            searchRecipients(searchTerm);
        } else if (searchTerm.length === 0) {
            loadRecipients();
        }
    });

    function showModal() {
        $('#recipientSearchModal').removeClass('hidden').addClass('flex');
        document.body.style.overflow = 'hidden';
    }

    window.closeModal = function() {
        $('#recipientSearchModal').removeClass('flex').addClass('hidden');
        document.body.style.overflow = '';
    };

    // Close modal when clicking outside
    $('#recipientSearchModal').on('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function loadRecipients() {
        $.ajax({
            url: '<?= base_url('api/recipients') ?>',
            method: 'GET',
            success: function(response) {
                displayRecipients(response.data || []);
            },
            error: function() {
                showErrorAlert('Error loading recipients.');
            }
        });
    }

    function searchRecipients(searchTerm) {
        $.ajax({
            url: '<?= base_url('api/recipients/search') ?>',
            method: 'GET',
            data: { term: searchTerm },
            success: function(response) {
                displayRecipients(response.data || []);
            },
            error: function() {
                showErrorAlert('Error searching recipients.');
            }
        });
    }

    function displayRecipients(recipients) {
        let html = '';
        recipients.forEach(function(recipient) {
            html += `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${recipient.recipient_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipient.first_name} ${recipient.last_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipient.course}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipient.campus}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all select-recipient" 
                            data-id="${recipient.id}" 
                            data-name="${recipient.first_name} ${recipient.last_name}" 
                            data-code="${recipient.recipient_id}">
                        Select
                    </button>
                </td>
            </tr>`;
        });

        $('#recipientTableBody').html(html);
    }

    // Select recipient
    $(document).on('click', '.select-recipient', function() {
        const recipientId = $(this).data('id');
        const recipientName = $(this).data('name');
        const recipientCode = $(this).data('code');

        $('input[name="recipient_id"]').val(recipientId);
        $('#recipient_search').val(recipientName + ' (' + recipientCode + ')');
        
        closeModal();
    });

    function showErrorAlert(message) {
        // Create and show a temporary alert
        const alertHtml = `
            <div class="fixed top-4 right-4 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center z-50 error-alert">
                <i class="bi bi-exclamation-triangle text-red-600 mr-3 text-lg"></i>
                <span class="text-red-800">${message}</span>
                <button class="ml-4 text-red-600 hover:text-red-800" onclick="$(this).parent().remove()">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>
        `;
        $('body').append(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.error-alert').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>