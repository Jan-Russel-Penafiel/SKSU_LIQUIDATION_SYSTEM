<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-lg">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <h1 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                    Entry by Officer
                </h1>
                <nav class="flex mt-2 sm:mt-0" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?= base_url('dashboard') ?>" class="text-green-200 hover:text-white transition-colors duration-200">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="<?= base_url('manual-liquidation') ?>" class="text-green-200 hover:text-white ml-1 md:ml-2 transition-colors duration-200">Manual Liquidation</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-100 ml-1 md:ml-2 font-medium">Entry by Officer</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="p-6">
            <!-- Officer Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="officer_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Disbursing Officer <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" id="officer_select">
                        <option value="">Choose an officer...</option>
                        <?php foreach ($officers as $officer): ?>
                        <option value="<?= $officer['id'] ?>" data-officer="<?= esc(json_encode($officer)) ?>">
                            <?= esc($officer['username'] . ' - ' . $officer['campus']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" id="load_officer_liquidations">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                        Load Liquidations
                    </button>
                </div>
            </div>

            <!-- Officer Info -->
            <div id="officer-info" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-green-800 mb-3">Officer Information:</h3>
                <div id="officer-details"></div>
            </div>

            <!-- Officer's Liquidations -->
            <div id="officer-liquidations" class="hidden">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2 sm:mb-0">
                        Liquidations by <span id="selected-officer-name" class="text-green-600"></span>
                    </h2>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" id="add-new-entry">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Add New Entry
                    </button>
                </div>
                
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="officer-liquidations-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="liquidations-tbody">
                                <!-- Liquidations will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Entry Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="addEntryModal">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
            <h3 class="text-lg font-semibold text-gray-800">Add New Liquidation Entry</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" onclick="closeModal('addEntryModal')">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <form action="<?= base_url('manual-liquidation/store') ?>" method="POST" class="p-4">
            <?= csrf_field() ?>
            <input type="hidden" name="disbursing_officer_id" id="modal_officer_id">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6" id="modal-officer-info"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="modal_recipient_search" class="block text-sm font-medium text-gray-700 mb-2">
                        Search Recipient <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                               id="modal_recipient_search" placeholder="Start typing recipient name or ID...">
                        <div id="recipient_dropdown" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                    </div>
                    <input type="hidden" name="recipient_id" id="modal_selected_recipient_id" required>
                    <p class="mt-1 text-sm text-red-600 hidden" id="recipient-error">Please select a recipient.</p>
                </div>

                <div>
                    <label for="modal_voucher_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Voucher Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                           id="modal_voucher_number" name="voucher_number" required>
                    <p class="mt-1 text-sm text-red-600 hidden" id="voucher-error">Please provide a voucher number.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="modal_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-lg">₱</span>
                        <input type="number" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                               id="modal_amount" name="amount" step="0.01" min="0" required>
                    </div>
                    <p class="mt-1 text-sm text-red-600 hidden" id="amount-error">Please enter a valid amount.</p>
                </div>

                <div>
                    <label for="modal_liquidation_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Liquidation Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                           id="modal_liquidation_date" name="liquidation_date" required>
                    <p class="mt-1 text-sm text-red-600 hidden" id="date-error">Please select the liquidation date.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="modal_semester" class="block text-sm font-medium text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                            id="modal_semester" name="semester" required>
                        <option value="">Select Semester</option>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 hidden" id="semester-error">Please select a semester.</p>
                </div>

                <div>
                    <label for="modal_academic_year" class="block text-sm font-medium text-gray-700 mb-2">
                        Academic Year <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                           id="modal_academic_year" name="academic_year" placeholder="e.g., 2024-2025" pattern="[0-9]{4}-[0-9]{4}" required>
                    <p class="mt-1 text-sm text-red-600 hidden" id="academic-year-error">Please enter academic year in format: YYYY-YYYY</p>
                </div>

                <div>
                    <label for="modal_campus" class="block text-sm font-medium text-gray-700 mb-2">
                        Campus <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500" 
                           id="modal_campus" name="campus" readonly>
                </div>
            </div>

            <div class="mb-6">
                <label for="modal_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                          id="modal_description" name="description" rows="3" placeholder="Enter liquidation description or remarks"></textarea>
            </div>

            <div id="modal-recipient-details" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <h4 class="text-lg font-semibold text-green-800 mb-2">Selected Recipient:</h4>
                <div id="modal-recipient-info"></div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200" onclick="closeModal('addEntryModal')">
                    Cancel
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Create Entry
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Status badge styles */
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .status-approved, .status-verified {
        background-color: #dcfce7;
        color: #166534;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .status-processing {
        background-color: #dbeafe;
        color: #1e40af;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Global function to close recipient modal (kept for compatibility)
function closeRecipientModal() {
    // No longer needed but kept for backward compatibility
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
}

$(document).ready(function() {
    let officerLiquidationsTable = null;
    let selectedOfficer = null;
    let recipientSearchTimeout = null;

    // Load officer liquidations
    $('#load_officer_liquidations').on('click', function() {
        const officerId = $('#officer_select').val();
        const officerData = $('#officer_select option:selected').data('officer');
        
        if (!officerId) {
            alert('Please select an officer first.');
            return;
        }

        selectedOfficer = officerData;
        displayOfficerInfo(officerData);
        loadOfficerLiquidations(officerId);
    });

    function displayOfficerInfo(officer) {
        const infoHtml = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="font-semibold text-green-800">Username:</span> ${officer.username}<br>
                    <span class="font-semibold text-green-800">Email:</span> ${officer.email}
                </div>
                <div>
                    <span class="font-semibold text-green-800">Role:</span> ${officer.role.replace('_', ' ').toUpperCase()}<br>
                    <span class="font-semibold text-green-800">Campus:</span> ${officer.campus}
                </div>
                <div>
                    <span class="font-semibold text-green-800">Status:</span> 
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${officer.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${officer.is_active ? 'Active' : 'Inactive'}
                    </span>
                </div>
            </div>
        `;
        
        $('#officer-details').html(infoHtml);
        $('#officer-info').removeClass('hidden');
        $('#selected-officer-name').text(officer.username);
    }

    function loadOfficerLiquidations(officerId) {
        $.ajax({
            url: '<?= base_url('api/manual-liquidation/by-officer') ?>',
            method: 'GET',
            data: { officer_id: officerId },
            success: function(response) {
                if (response.success) {
                    displayOfficerLiquidations(response.liquidations || [], response.statistics || {});
                    updateOfficerStats(response.statistics || {});
                } else {
                    $('#liquidations-tbody').html('<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No liquidations found for this officer.</td></tr>');
                    $('#officer-liquidations').removeClass('hidden');
                }
            },
            error: function() {
                alert('Error loading officer liquidations.');
            }
        });
    }

    function displayOfficerLiquidations(liquidations, stats) {
        let html = '';
        
        // Check if liquidations is defined and is an array
        if (!liquidations || !Array.isArray(liquidations)) {
            liquidations = [];
        }
        
        liquidations.forEach(function(liquidation) {
            let statusBadge = '';
            switch(liquidation.status.toLowerCase()) {
                case 'pending':
                    statusBadge = '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">PENDING</span>';
                    break;
                case 'approved':
                case 'verified':
                    statusBadge = '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">APPROVED</span>';
                    break;
                case 'rejected':
                    statusBadge = '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">REJECTED</span>';
                    break;
                default:
                    statusBadge = `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">${liquidation.status.toUpperCase()}</span>`;
            }

            // Construct recipient name from first_name and last_name
            const recipientName = `${liquidation.first_name || ''} ${liquidation.last_name || ''}`.trim() || 'Unknown Recipient';
            
            html += `<tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${liquidation.voucher_number}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipientName}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱${parseFloat(liquidation.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(liquidation.liquidation_date).toLocaleDateString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${statusBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex space-x-2">
                        <a href="<?= base_url('manual-liquidation/show/') ?>${liquidation.id}" 
                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded text-xs transition-colors duration-200" title="View">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        ${liquidation.status === 'pending' ? `
                        <a href="<?= base_url('manual-liquidation/edit/') ?>${liquidation.id}" 
                           class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded text-xs transition-colors duration-200" title="Edit">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                            </svg>
                        </a>` : ''}
                    </div>
                </td>
            </tr>`;
        });

        $('#liquidations-tbody').html(html);
        
        // Update statistics
        $('#total-entries').text(stats.total || 0);
        $('#approved-entries').text(stats.approved || 0);
        $('#pending-entries').text(stats.pending || 0);
        $('#total-amount').text('₱' + parseFloat(stats.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}));

        $('#officer-liquidations').removeClass('hidden');

        // Initialize or reinitialize DataTable
        if (officerLiquidationsTable) {
            officerLiquidationsTable.destroy();
        }
        
        officerLiquidationsTable = $('#officer-liquidations-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [5] }
            ]
        });
    }

    function updateOfficerStats(stats) {
        if (!stats || typeof stats !== 'object') {
            stats = {};
        }
        $('#total-entries').text(stats.total || 0);
        $('#pending-entries').text(stats.pending || 0);
        $('#approved-entries').text(stats.approved || 0);
        $('#total-amount').text('₱' + parseFloat(stats.total_amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}));
    }

    // Add new entry
    $('#add-new-entry').on('click', function() {
        if (!selectedOfficer) {
            alert('Please select an officer first.');
            return;
        }

        $('#modal_officer_id').val(selectedOfficer.id);
        $('#modal_campus').val(selectedOfficer.campus);
        
        const officerInfoHtml = `
            <span class="font-semibold text-blue-800">Creating entry for:</span> ${selectedOfficer.username} (${selectedOfficer.campus})
        `;
        $('#modal-officer-info').html(officerInfoHtml);
        
        // Clear previous selections
        $('#modal_recipient_search').val('');
        $('#modal_selected_recipient_id').val('');
        $('#modal-recipient-details').addClass('hidden');
        $('#modal_voucher_number').val('');
        $('#modal_amount').val('');
        $('#modal_description').val('');
        $('#modal_semester').val('');
        
        // Set default values
        $('#modal_liquidation_date').val(new Date().toISOString().split('T')[0]);
        const currentYear = new Date().getFullYear();
        $('#modal_academic_year').val(`${currentYear}-${currentYear + 1}`);
        
        openModal('addEntryModal');
    });

    // Recipient search in modal - direct search with dropdown
    $('#modal_recipient_search').on('input', function() {
        const searchTerm = $(this).val().trim();
        
        // Clear previous timeout
        if (recipientSearchTimeout) {
            clearTimeout(recipientSearchTimeout);
        }
        
        // Hide dropdown if search term is too short
        if (searchTerm.length < 2) {
            $('#recipient_dropdown').addClass('hidden').empty();
            return;
        }

        // Debounce search
        recipientSearchTimeout = setTimeout(function() {
            searchRecipients(searchTerm);
        }, 300);
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#modal_recipient_search, #recipient_dropdown').length) {
            $('#recipient_dropdown').addClass('hidden');
        }
    });

    // Allow search on Enter key
    $('#modal_recipient_search').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
        }
    });

    function searchRecipients(searchTerm) {
        // Get campus from selected officer
        const campus = selectedOfficer ? selectedOfficer.campus : '';

        $.ajax({
            url: '<?= base_url('api/recipients/search') ?>',
            method: 'GET',
            data: { 
                query: searchTerm,
                campus: campus
            },
            success: function(response) {
                if (response.success && response.recipients && response.recipients.length > 0) {
                    showRecipientDropdown(response.recipients);
                } else {
                    $('#recipient_dropdown').html('<div class="p-3 text-sm text-gray-500">No recipients found</div>').removeClass('hidden');
                }
            },
            error: function() {
                $('#recipient_dropdown').html('<div class="p-3 text-sm text-red-500">Error searching recipients</div>').removeClass('hidden');
            }
        });
    }

    function showRecipientDropdown(recipients) {
        let recipientOptions = '';
        recipients.forEach(function(recipient) {
            const fullName = `${recipient.first_name} ${recipient.last_name}`.trim();
            recipientOptions += `
                <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer recipient-option" 
                     data-recipient-id="${recipient.id}" data-recipient='${JSON.stringify(recipient)}'>
                    <div class="font-medium text-gray-900 text-sm">${fullName}</div>
                    <div class="text-xs text-gray-500">ID: ${recipient.recipient_id} | Campus: ${recipient.campus}</div>
                    <div class="text-xs text-gray-500">Course: ${recipient.course} | Year: ${recipient.year_level}</div>
                </div>
            `;
        });

        $('#recipient_dropdown').html(recipientOptions).removeClass('hidden');

        // Handle recipient selection
        $('.recipient-option').on('click', function() {
            const recipientId = $(this).data('recipient-id');
            const recipient = $(this).data('recipient');
            
            selectRecipient(recipientId, recipient);
            $('#recipient_dropdown').addClass('hidden');
        });
    }

    function selectRecipient(recipientId, recipient) {
        $('#modal_selected_recipient_id').val(recipientId);
        $('#modal_recipient_search').val(`${recipient.first_name} ${recipient.last_name}`.trim());
        
        // Show recipient details
        const recipientInfoHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold text-green-800">Name:</span> ${recipient.first_name} ${recipient.last_name}<br>
                    <span class="font-semibold text-green-800">ID:</span> ${recipient.recipient_id}<br>
                    <span class="font-semibold text-green-800">Email:</span> ${recipient.email || 'N/A'}
                </div>
                <div>
                    <span class="font-semibold text-green-800">Campus:</span> ${recipient.campus}<br>
                    <span class="font-semibold text-green-800">Course:</span> ${recipient.course}<br>
                    <span class="font-semibold text-green-800">Year Level:</span> ${recipient.year_level}
                </div>
            </div>
        `;
        
        $('#modal-recipient-info').html(recipientInfoHtml);
        $('#modal-recipient-details').removeClass('hidden');
        $('#recipient-error').addClass('hidden');
        $('#modal_recipient_search').removeClass('border-red-500');
    }

    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('border-red-500');
                const fieldName = $(this).attr('name') || $(this).attr('id');
                const errorElement = $(`#${fieldName}-error`);
                if (errorElement.length) {
                    errorElement.removeClass('hidden');
                }
            } else {
                $(this).removeClass('border-red-500');
                const fieldName = $(this).attr('name') || $(this).attr('id');
                const errorElement = $(`#${fieldName}-error`);
                if (errorElement.length) {
                    errorElement.addClass('hidden');
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
<?= $this->endSection() ?>