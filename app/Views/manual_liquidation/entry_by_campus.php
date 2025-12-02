<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-building text-green-100 mr-3 text-base"></i>
                        Entry by Campus
                    </h1>
                    <nav class="text-green-100" aria-label="breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li><a href="<?= base_url('dashboard') ?>" class="hover:text-white transition-colors">Dashboard</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li><a href="<?= base_url('manual-liquidation') ?>" class="hover:text-white transition-colors">Manual Liquidation</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li class="text-green-200">Entry by Campus</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Campus Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="campus_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Campus <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors campus-transition" 
                                id="campus_select">
                            <option value="">Choose a campus...</option>
                            <?php foreach ($campuses as $campus): ?>
                            <option value="<?= esc($campus) ?>"><?= esc($campus) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="mt-2">
                            <div id="campus-status" class="hidden text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    <span class="flex w-2 h-2 mr-1.5 rounded-full"></span>
                                    <span class="status-text"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <div class="flex flex-col space-y-2">
                            <button class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all" 
                                    id="load_recipients">
                                <i class="bi bi-search mr-2"></i>Load Recipients
                            </button>
                            <p class="text-xs text-gray-500 text-center">Recipients auto-load when campus changes</p>
                        </div>
                    </div>
                </div>

                <!-- Recipients for Selected Campus -->
                <div id="campus-recipients" class="hidden recipients-container campus-transition">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="bi bi-people mr-2 text-green-600"></i>
                            Recipients in <span id="selected-campus-name" class="text-green-600"></span>
                        </h3>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-green-200/20 overflow-hidden mb-6 campus-transition">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="campus-recipients-table">
                                <thead class="bg-gradient-to-r from-green-50 to-green-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded focus:ring-green-500 border-gray-300" id="select-all">
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Recipient ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Year Level</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Scholarship Type</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="recipients-tbody" class="bg-white divide-y divide-gray-200">
                                    <!-- Recipients will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all" 
                                id="bulk-create">
                            <i class="bi bi-plus-square mr-2"></i>Create Bulk Entries
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Single Create Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50" id="singleCreateModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Create Liquidation Entry</h3>
            <button type="button" 
                    class="text-white hover:text-green-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg p-1 transition-colors" 
                    onclick="closeSingleModal()">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[70vh]">
            <form action="<?= base_url('manual-liquidation/store') ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="recipient_id" id="single_recipient_id">
                
                <div id="single-recipient-info" class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <!-- Recipient info will be displayed here -->
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="single_disbursing_officer" class="block text-sm font-medium text-gray-700">
                            Disbursing Officer <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                name="disbursing_officer_id" required>
                            <option value="">Select Officer</option>
                            <?php foreach ($officers as $officer): ?>
                                <option value="<?= $officer['id'] ?>"><?= esc($officer['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a disbursing officer.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="single_scholarship_coordinator" class="block text-sm font-medium text-gray-700">
                            Scholarship Coordinator
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                name="scholarship_coordinator_id">
                            <option value="">Select Coordinator</option>
                            <?php foreach ($coordinators as $coordinator): ?>
                                <option value="<?= $coordinator['id'] ?>"><?= esc($coordinator['username'] . ' - ' . $coordinator['campus']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="single_voucher_number" class="block text-sm font-medium text-gray-700">
                            Voucher Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               name="voucher_number" required>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please provide a voucher number.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="single_amount" class="block text-sm font-medium text-gray-700">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" 
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   name="amount" step="0.01" min="0" required>
                        </div>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter a valid amount.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="single_liquidation_date" class="block text-sm font-medium text-gray-700">
                            Liquidation Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               name="liquidation_date" required>
                    </div>

                    <div class="space-y-2">
                        <label for="single_semester" class="block text-sm font-medium text-gray-700">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="single_academic_year" class="block text-sm font-medium text-gray-700">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               name="academic_year" 
                               placeholder="e.g., 2024-2025" 
                               pattern="[0-9]{4}-[0-9]{4}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="single_campus" class="block text-sm font-medium text-gray-700">
                            Campus <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 focus:ring-green-500 focus:border-green-500" 
                               name="campus" readonly>
                    </div>

                    <div class="space-y-2">
                        <label for="single_description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea class="block w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                  name="description" rows="2" 
                                  placeholder="Enter liquidation description"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <button type="button" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            onclick="closeSingleModal()">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all">
                        Create Liquidation Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Create Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50" id="bulkCreateModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Create Bulk Liquidation Entries</h3>
            <button type="button" 
                    class="text-white hover:text-green-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg p-1 transition-colors" 
                    onclick="closeBulkModal()">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form action="<?= base_url('manual-liquidation/bulk-store') ?>" method="POST" id="bulk-form">
                <?= csrf_field() ?>
                <input type="hidden" name="recipient_ids" id="selected_recipient_ids">
                <input type="hidden" name="campus" id="bulk_campus">
                
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center">
                    <i class="bi bi-info-circle text-blue-600 mr-3 text-lg"></i>
                    <div class="text-blue-800">
                        <span class="font-semibold">Selected Recipients:</span> 
                        <span id="selected-count" class="font-bold">0</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="bulk_voucher_prefix" class="block text-sm font-medium text-gray-700">
                            Voucher Number Prefix
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="bulk_voucher_prefix" 
                               name="voucher_prefix" 
                               placeholder="e.g., VO-2024-" 
                               value="VO-<?= date('Y') ?>-">
                    </div>

                    <div class="space-y-2">
                        <label for="bulk_amount" class="block text-sm font-medium text-gray-700">
                            Amount (Same for all) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" 
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   id="bulk_amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="bulk_liquidation_date" class="block text-sm font-medium text-gray-700">
                            Liquidation Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="bulk_liquidation_date" 
                               name="liquidation_date" required>
                    </div>

                    <div class="space-y-2">
                        <label for="bulk_semester" class="block text-sm font-medium text-gray-700">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                id="bulk_semester" 
                                name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="bulk_academic_year" class="block text-sm font-medium text-gray-700">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="bulk_academic_year" 
                               name="academic_year" 
                               placeholder="e.g., 2024-2025" 
                               pattern="[0-9]{4}-[0-9]{4}" required>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="space-y-2">
                        <label for="bulk_description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea class="block w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                  id="bulk_description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Enter description for all liquidation entries"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <button type="button" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            onclick="closeBulkModal()">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all">
                        Create All Entries
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .campus-transition {
        transition: all 0.3s ease-in-out;
    }
    
    .loading-overlay {
        position: relative;
    }
    
    .loading-overlay::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 10;
        border-radius: 0.75rem;
    }
    
    .recipients-container {
        min-height: 200px;
        transition: all 0.3s ease-in-out;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let campusRecipientsTable = null;
    let recipientsData = {}; // Store recipient data by ID

    // Load recipients by campus - enhanced for continuous loading
    function loadRecipientsByCampus(campus) {
        if (!campus) {
            $('#campus-recipients').slideUp(300);
            updateCampusStatus('none', '');
            return;
        }

        console.log('Loading recipients for campus:', campus);

        // Show loading state
        const $button = $('#load_recipients');
        const originalText = $button.html();
        $button.prop('disabled', true).html('<i class="bi bi-hourglass-split mr-2"></i>Loading...');
        
        // Update status indicator
        updateCampusStatus('loading', `Loading recipients for ${campus}...`);

        // Clear previous selections
        recipientsData = {};
        updateSelectedCount();

        $.ajax({
            url: '<?= base_url('api/recipients/search') ?>',
            method: 'GET',
            data: { campus: campus },
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                console.log('API Response for', campus + ':', response);
                if (response.success && response.data && response.data.length > 0) {
                    displayCampusRecipients(response.data, campus);
                    updateCampusStatus('success', `${response.data.length} recipients loaded from ${campus}`);
                } else {
                    showNoRecipientsMessage(campus);
                    updateCampusStatus('warning', `No recipients found in ${campus}`);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {xhr, status, error});
                console.error('Response text:', xhr.responseText);
                
                let errorMessage = 'Error loading recipients';
                if (xhr.status === 404) {
                    errorMessage = 'API endpoint not found';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timed out';
                } else if (error) {
                    errorMessage += ': ' + error;
                }
                
                showErrorMessage(errorMessage, campus);
                updateCampusStatus('error', errorMessage);
            },
            complete: function() {
                // Restore button state
                $button.prop('disabled', false).html(originalText);
            }
        });
    }

    // Manual load button click
    $('#load_recipients').on('click', function() {
        const campus = $('#campus_select').val();
        if (!campus) {
            alert('Please select a campus first.');
            return;
        }
        loadRecipientsByCampus(campus);
    });

    // Auto-load when campus selection changes
    $('#campus_select').on('change', function() {
        const selectedCampus = $(this).val();
        if (selectedCampus) {
            // Auto-load recipients when a campus is selected
            setTimeout(function() {
                loadRecipientsByCampus(selectedCampus);
            }, 100); // Small delay to ensure smooth UX
        } else {
            // Hide recipients table when no campus is selected
            $('#campus-recipients').slideUp(300);
            recipientsData = {};
            updateSelectedCount();
            updateCampusStatus('none', '');
        }
    });

    // Update campus status indicator
    function updateCampusStatus(type, message) {
        const $status = $('#campus-status');
        const $statusSpan = $status.find('span').first();
        const $statusText = $status.find('.status-text');
        
        if (type === 'none' || !message) {
            $status.addClass('hidden');
            return;
        }
        
        // Remove all status classes
        $statusSpan.removeClass('bg-blue-100 text-blue-800 bg-green-100 text-green-800 bg-yellow-100 text-yellow-800 bg-red-100 text-red-800');
        $statusSpan.find('.w-2').removeClass('bg-blue-400 bg-green-400 bg-yellow-400 bg-red-400 animate-pulse');
        
        // Apply appropriate status styling
        switch(type) {
            case 'loading':
                $statusSpan.addClass('bg-blue-100 text-blue-800');
                $statusSpan.find('.w-2').addClass('bg-blue-400 animate-pulse');
                break;
            case 'success':
                $statusSpan.addClass('bg-green-100 text-green-800');
                $statusSpan.find('.w-2').addClass('bg-green-400');
                break;
            case 'warning':
                $statusSpan.addClass('bg-yellow-100 text-yellow-800');
                $statusSpan.find('.w-2').addClass('bg-yellow-400');
                break;
            case 'error':
                $statusSpan.addClass('bg-red-100 text-red-800');
                $statusSpan.find('.w-2').addClass('bg-red-400');
                break;
        }
        
        $statusText.text(message);
        $status.removeClass('hidden');
    }

    function displayCampusRecipients(recipients, campus) {
        console.log('Displaying', recipients.length, 'recipients for', campus);
        
        $('#selected-campus-name').text(campus);
        
        // Clear previous data
        recipientsData = {};
        
        let html = '';
        recipients.forEach(function(recipient) {
            // Store recipient data in global object
            recipientsData[recipient.id] = recipient;
            
            html += `<tr class="hover:bg-green-50 transition-colors fade-in">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded focus:ring-green-500 border-gray-300 recipient-checkbox" 
                           value="${recipient.id}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${recipient.recipient_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipient.first_name} ${recipient.last_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${recipient.course}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${recipient.year_level}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${recipient.scholarship_type}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all create-single" 
                            data-recipient-id="${recipient.id}">
                        <i class="bi bi-plus-circle mr-1"></i>Create
                    </button>
                </td>
            </tr>`;
        });

        // Destroy existing DataTable if it exists
        if (campusRecipientsTable) {
            campusRecipientsTable.destroy();
            campusRecipientsTable = null;
        }

        // Update table content with smooth transition
        $('#recipients-tbody').fadeOut(200, function() {
            $(this).html(html).fadeIn(200);
            
            // Initialize new DataTable after content is loaded
            campusRecipientsTable = $('#campus-recipients-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ],
                language: {
                    info: `Showing _START_ to _END_ of _TOTAL_ recipients from ${campus}`,
                    infoEmpty: `No recipients found for ${campus}`,
                    emptyTable: `No recipients available for ${campus}`
                }
            });
        });

        // Show the recipients section with animation
        $('#campus-recipients').removeClass('hidden').hide().slideDown(300);
        
        // Reset checkbox states
        $('#select-all').prop('checked', false);
        updateSelectedCount();
        
        console.log('Successfully loaded', recipients.length, 'recipients for', campus);
    }

    function showNoRecipientsMessage(campus) {
        $('#selected-campus-name').text(campus);
        
        // Destroy existing DataTable
        if (campusRecipientsTable) {
            campusRecipientsTable.destroy();
            campusRecipientsTable = null;
        }
        
        const noDataHtml = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="bi bi-inbox text-gray-400 text-4xl mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No Recipients Found</h3>
                        <p class="text-gray-500">No scholarship recipients found for ${campus}.</p>
                    </div>
                </td>
            </tr>
        `;
        
        $('#recipients-tbody').fadeOut(200, function() {
            $(this).html(noDataHtml).fadeIn(200);
        });
        
        $('#campus-recipients').removeClass('hidden').hide().slideDown(300);
        recipientsData = {};
        updateSelectedCount();
    }

    function showErrorMessage(message, campus) {
        $('#selected-campus-name').text(campus || 'Selected Campus');
        
        // Destroy existing DataTable
        if (campusRecipientsTable) {
            campusRecipientsTable.destroy();
            campusRecipientsTable = null;
        }
        
        const errorHtml = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-red-400 text-4xl mb-3"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Error Loading Recipients</h3>
                        <p class="text-gray-500">${message}</p>
                        <button onclick="loadRecipientsByCampus('${campus}')" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="bi bi-arrow-clockwise mr-2"></i>Retry
                        </button>
                    </div>
                </td>
            </tr>
        `;
        
        $('#recipients-tbody').fadeOut(200, function() {
            $(this).html(errorHtml).fadeIn(200);
        });
        
        $('#campus-recipients').removeClass('hidden').hide().slideDown(300);
        recipientsData = {};
        updateSelectedCount();
    }

    // Select all functionality
    $(document).on('change', '#select-all', function() {
        $('.recipient-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });

    $(document).on('change', '.recipient-checkbox', function() {
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const count = $('.recipient-checkbox:checked').length;
        $('#selected-count').text(count);
    }

    // Bulk create - enhanced for campus switching
    $('#bulk-create').on('click', function() {
        const selectedIds = [];
        $('.recipient-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        const currentCampus = $('#campus_select').val();
        
        if (!currentCampus) {
            alert('Please select a campus first.');
            return;
        }

        if (selectedIds.length === 0) {
            alert('Please select at least one recipient from ' + currentCampus + '.');
            return;
        }

        $('#selected_recipient_ids').val(JSON.stringify(selectedIds));
        $('#bulk_campus').val(currentCampus);
        
        // Set default values
        $('#bulk_liquidation_date').val(new Date().toISOString().split('T')[0]);
        const currentYear = new Date().getFullYear();
        $('#bulk_academic_year').val(`${currentYear}-${currentYear + 1}`);
        
        // Update modal title to show campus
        $('#bulkCreateModal .text-xl').text(`Create Bulk Liquidation Entries - ${currentCampus}`);
        
        showBulkModal();
    });

    // Single create button handler
    $(document).on('click', '.create-single', function() {
        const recipientId = $(this).data('recipient-id');
        const recipientData = recipientsData[recipientId];
        const campus = $('#campus_select').val();
        
        // Populate recipient info
        const recipientInfo = `
            <strong>Recipient:</strong> ${recipientData.first_name} ${recipientData.last_name}<br>
            <strong>ID:</strong> ${recipientData.recipient_id}<br>
            <strong>Course:</strong> ${recipientData.course}<br>
            <strong>Year Level:</strong> ${recipientData.year_level}
        `;
        $('#single-recipient-info').html(recipientInfo);
        
        // Set form values
        $('#single_recipient_id').val(recipientData.id);
        $('input[name="campus"]').val(campus);
        
        // Set default values
        $('input[name="liquidation_date"]').val(new Date().toISOString().split('T')[0]);
        const currentYear = new Date().getFullYear();
        $('input[name="academic_year"]').val(`${currentYear}-${currentYear + 1}`);
        
        showSingleModal();
    });

    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Set default values when page loads
    const currentYear = new Date().getFullYear();
    $('#bulk_academic_year').val(`${currentYear}-${currentYear + 1}`);
});

// Modal control functions
function showSingleModal() {
    document.getElementById('singleCreateModal').classList.remove('hidden');
    document.getElementById('singleCreateModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeSingleModal() {
    document.getElementById('singleCreateModal').classList.remove('flex');
    document.getElementById('singleCreateModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showBulkModal() {
    document.getElementById('bulkCreateModal').classList.remove('hidden');
    document.getElementById('bulkCreateModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeBulkModal() {
    document.getElementById('bulkCreateModal').classList.remove('flex');
    document.getElementById('bulkCreateModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals when clicking outside
document.getElementById('singleCreateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSingleModal();
    }
});

document.getElementById('bulkCreateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkModal();
    }
});
</script>
<?= $this->endSection() ?>