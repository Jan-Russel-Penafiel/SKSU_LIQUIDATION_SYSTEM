<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Manual Liquidation Entry
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-plus-circle mr-3 text-green-200"></i>
                        Manual Liquidation Entry
                    </h1>
                    <nav aria-label="breadcrumb" class="flex items-center space-x-2 text-sm">
                        <a href="<?= base_url('dashboard') ?>" class="text-green-100 hover:text-white transition-colors">
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <a href="<?= base_url('manual-liquidation') ?>" class="text-green-100 hover:text-white transition-colors">
                            Manual Liquidation
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <span class="text-green-200">Create Entry</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Liquidation Entry Form -->
                <form action="<?= base_url('manual-liquidation/store') ?>" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label for="recipient_search" class="block text-sm font-medium text-gray-700">
                                        Search Recipient <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" 
                                            id="recipient_search" 
                                            placeholder="Type to search recipient by ID or name..."
                                            autocomplete="off"
                                        >
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                        <!-- Search results dropdown -->
                                        <div id="recipient_results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="recipient_id" id="selected_recipient_id" required>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a valid recipient.</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="voucher_number" class="block text-sm font-medium text-gray-700">
                                        Voucher Number <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="voucher_number" 
                                        name="voucher_number" 
                                        required
                                    >
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please provide a voucher number.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">
                                        Amount <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500 text-sm">₱</span>
                                        <input 
                                            type="number" 
                                            class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                            id="amount" 
                                            name="amount" 
                                            step="0.01" 
                                            min="0" 
                                            required
                                        >
                                    </div>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please enter a valid amount.</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="liquidation_date" class="block text-sm font-medium text-gray-700">
                                        Liquidation Date <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="liquidation_date" 
                                        name="liquidation_date" 
                                        required
                                    >
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select the liquidation date.</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="campus" class="block text-sm font-medium text-gray-700">
                                        Campus <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="campus" 
                                        name="campus" 
                                        required
                                    >
                                        <option value="">Select Campus</option>
                                        <option value="Main Campus">Main Campus</option>
                                        <option value="Kalamansig Campus">Kalamansig Campus</option>
                                        <option value="Palimbang Campus">Palimbang Campus</option>
                                        <option value="Isulan Campus">Isulan Campus</option>
                                        <option value="Bagumbayan Campus">Bagumbayan Campus</option>
                                    </select>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a campus.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label for="disbursing_officer_id" class="block text-sm font-medium text-gray-700">
                                        Assign Disbursing Officer <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="disbursing_officer_id" 
                                        name="disbursing_officer_id" 
                                        required
                                        disabled
                                    >
                                        <option value="">Select Campus First</option>
                                    </select>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a disbursing officer.</p>
                                    <p class="text-sm text-gray-500">Officers will be filtered based on the selected campus.</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="coordinator_id" class="block text-sm font-medium text-gray-700">
                                        Assign Scholarship Coordinator <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="coordinator_id" 
                                        name="coordinator_id" 
                                        required
                                        disabled
                                    >
                                        <option value="">Select Campus First</option>
                                    </select>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a scholarship coordinator.</p>
                                    <p class="text-sm text-gray-500">Coordinators will be filtered based on the selected campus.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-2">
                                    <label for="semester" class="block text-sm font-medium text-gray-700">
                                        Semester <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="semester" 
                                        name="semester" 
                                        required
                                    >
                                        <option value="">Select Semester</option>
                                        <option value="1st Semester">1st Semester</option>
                                        <option value="2nd Semester">2nd Semester</option>
                                        <option value="Summer">Summer</option>
                                    </select>
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a semester.</p>
                                </div>

                                <div class="space-y-2">
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700">
                                        Academic Year <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                        id="academic_year" 
                                        name="academic_year" 
                                        placeholder="e.g., 2024-2025" 
                                        pattern="[0-9]{4}-[0-9]{4}" 
                                        required
                                    >
                                    <p class="text-sm text-red-600 hidden peer-invalid:block">Please enter academic year in format: YYYY-YYYY</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <div class="space-y-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700">
                                        Description
                                    </label>
                                    <textarea 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm resize-none" 
                                        id="description" 
                                        name="description" 
                                        rows="3" 
                                        placeholder="Enter liquidation description or remarks"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Recipient Details (Populated after search) -->
                            <div id="recipient-details" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 hidden">
                                <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                                    <i class="bi bi-info-circle mr-2"></i>
                                    Selected Recipient Details
                                </h3>
                                <div id="recipient-info" class="text-blue-800"></div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                >
                                    <i class="bi bi-check-lg mr-2"></i>
                                    Create Liquidation Entry
                                </button>
                                <a 
                                    href="<?= base_url('manual-liquidation') ?>" 
                                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                >
                                    <i class="bi bi-arrow-left mr-2"></i>
                                    Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let searchTimeout;
    
    // Automatic recipient search as user types
    $('#recipient_search').on('keyup', function() {
        const searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            $('#recipient_results').addClass('hidden').empty();
            return;
        }
        
        // Debounce search to avoid too many requests
        searchTimeout = setTimeout(function() {
            searchRecipients(searchTerm);
        }, 300);
    });
    
    // Hide results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#recipient_search, #recipient_results').length) {
            $('#recipient_results').addClass('hidden');
        }
    });
    
    function searchRecipients(searchTerm) {
        $.ajax({
            url: '<?= base_url('api/recipients/search') ?>',
            method: 'GET',
            data: { search: searchTerm },
            beforeSend: function() {
                $('#recipient_results').html('<div class="p-3 text-center text-gray-500"><i class="bi bi-hourglass-split"></i> Searching...</div>').removeClass('hidden');
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displaySearchResults(response.data);
                } else {
                    $('#recipient_results').html('<div class="p-3 text-center text-gray-500">No recipients found</div>').removeClass('hidden');
                }
            },
            error: function() {
                $('#recipient_results').html('<div class="p-3 text-center text-red-500">Error searching recipients</div>').removeClass('hidden');
            }
        });
    }
    
    function displaySearchResults(recipients) {
        let html = '';
        recipients.forEach(function(recipient) {
            html += `
                <div class="recipient-item p-3 hover:bg-green-50 cursor-pointer border-b border-gray-100 last:border-b-0" data-recipient='${JSON.stringify(recipient)}'>
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">${recipient.first_name} ${recipient.last_name}</div>
                            <div class="text-sm text-gray-500">ID: ${recipient.recipient_id} | ${recipient.campus}</div>
                            <div class="text-xs text-gray-400">${recipient.course}</div>
                        </div>
                        <div class="text-xs text-green-600 font-medium">${recipient.scholarship_type}</div>
                    </div>
                </div>
            `;
        });
        
        $('#recipient_results').html(html).removeClass('hidden');
        
        // Handle recipient selection
        $('.recipient-item').on('click', function() {
            const recipientData = JSON.parse($(this).attr('data-recipient'));
            selectRecipient(recipientData);
        });
    }
    
    function selectRecipient(recipient) {
        // Set the recipient ID
        $('#selected_recipient_id').val(recipient.id);
        
        // Update search field with selected recipient
        $('#recipient_search').val(`${recipient.recipient_id} - ${recipient.first_name} ${recipient.last_name}`);
        
        // Hide results dropdown
        $('#recipient_results').addClass('hidden');
        
        // Auto-populate campus from recipient data
        $('#campus').val(recipient.campus).trigger('change');
        
        // Show recipient details
        const detailsHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div><span class="font-medium text-blue-900">Recipient ID:</span> <span class="text-blue-800">${recipient.recipient_id}</span></div>
                    <div><span class="font-medium text-blue-900">Name:</span> <span class="text-blue-800">${recipient.first_name} ${recipient.last_name}</span></div>
                    <div><span class="font-medium text-blue-900">Email:</span> <span class="text-blue-800">${recipient.email}</span></div>
                </div>
                <div class="space-y-2">
                    <div><span class="font-medium text-blue-900">Campus:</span> <span class="text-blue-800">${recipient.campus}</span></div>
                    <div><span class="font-medium text-blue-900">Course:</span> <span class="text-blue-800">${recipient.course}</span></div>
                    <div><span class="font-medium text-blue-900">Scholarship:</span> <span class="text-blue-800">${recipient.scholarship_type}</span></div>
                </div>
            </div>
        `;
        
        $('#recipient-info').html(detailsHtml);
        $('#recipient-details').removeClass('hidden');
    }
    
    // Original modal-based search removed - keeping for backward compatibility if needed
    $('#modal_search_btn, #modal_search').on('click keyup', function(e) {
        if (e.type === 'click' || e.keyCode === 13) {
            loadRecipients($('#modal_search').val());
        }
    });

    function loadRecipients(search = '') {
        $.ajax({
            url: '<?= base_url('api/recipients/search') ?>',
            method: 'GET',
            data: { search: search },
            success: function(response) {
                if (response.success) {
                    displayRecipients(response.data);
                } else {
                    $('#recipients-list').html('<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">No recipients found.</div>');
                }
            },
            error: function() {
                $('#recipients-list').html('<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">Error loading recipients.</div>');
            }
        });
    }

    function displayRecipients(recipients) {
        let html = '<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">';
        html += '<table class="min-w-full divide-y divide-gray-300">';
        html += '<thead class="bg-gray-50">';
        html += '<tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient ID</th>';
        html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>';
        html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>';
        html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>';
        html += '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th></tr>';
        html += '</thead><tbody class="bg-white divide-y divide-gray-200">';

        recipients.forEach(function(recipient) {
            html += `<tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${recipient.recipient_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${recipient.first_name} ${recipient.last_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${recipient.campus}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${recipient.course}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors select-recipient" 
                        data-id="${recipient.id}" data-info='${JSON.stringify(recipient)}'>
                        Select
                    </button>
                </td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        $('#recipients-list').html(html);

        // Handle recipient selection
        $('.select-recipient').on('click', function() {
            const recipientData = JSON.parse($(this).attr('data-info'));
            selectRecipient(recipientData);
        });
    }

    // Function moved to main script section above

    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Set current date as default
    $('#liquidation_date').val(new Date().toISOString().split('T')[0]);
    
    // Set current academic year
    const currentYearMain = new Date().getFullYear();
    const academicYearMain = `${currentYearMain}-${currentYearMain + 1}`;
    $('#academic_year').val(academicYearMain);
    
    // Officer filtering by campus for all forms
    const officersData = <?= json_encode($disbursing_officers ?? []) ?>;
    const coordinatorsData = <?= json_encode($scholarship_coordinators ?? $coordinators ?? []) ?>;
    
    // Function to update officer dropdown based on campus selection
    function updateOfficerDropdown(campusValue, officerSelector) {
        const officerSelect = $(officerSelector);
        
        officerSelect.empty();
        officerSelect.prop('disabled', true);
        
        if (campusValue) {
            const filteredOfficers = officersData.filter(officer => 
                officer.campus === campusValue
            );
            
            if (filteredOfficers.length > 0) {
                officerSelect.append('<option value="">Select Disbursing Officer</option>');
                filteredOfficers.forEach(officer => {
                    officerSelect.append(`<option value="${officer.id}">${officer.username}</option>`);
                });
                officerSelect.prop('disabled', false);
            } else {
                officerSelect.append('<option value="">No officers available for this campus</option>');
            }
        } else {
            officerSelect.append('<option value="">Select Campus First</option>');
        }
    }

    // Function to update coordinator dropdown based on campus selection
    function updateCoordinatorDropdown(campusValue, coordinatorSelector) {
        const coordinatorSelect = $(coordinatorSelector);
        
        coordinatorSelect.empty();
        coordinatorSelect.prop('disabled', true);
        
        if (campusValue) {
            const filteredCoordinators = coordinatorsData.filter(coordinator => 
                coordinator.campus === campusValue
            );
            
            if (filteredCoordinators.length > 0) {
                coordinatorSelect.append('<option value="">Select Scholarship Coordinator</option>');
                filteredCoordinators.forEach(coordinator => {
                    coordinatorSelect.append(`<option value="${coordinator.id}">${coordinator.username}</option>`);
                });
                coordinatorSelect.prop('disabled', false);
            } else {
                coordinatorSelect.append('<option value="">No coordinators available for this campus</option>');
            }
        } else {
            coordinatorSelect.append('<option value="">Select Campus First</option>');
        }
    }
    
    // Campus change handler
    $('#campus').on('change', function() {
        const selectedCampus = $(this).val();
        updateOfficerDropdown(selectedCampus, '#disbursing_officer_id');
        updateCoordinatorDropdown(selectedCampus, '#coordinator_id');
    });
    
    // Set default dates and academic years
    const currentDate = new Date().toISOString().split('T')[0];
    const currentYearAll = new Date().getFullYear();
    const academicYearAll = `${currentYearAll}-${currentYearAll + 1}`;
    
    // Set defaults for all date and academic year fields
    $('[id*="liquidation_date"]').val(currentDate);
    $('[id*="academic_year"]').val(academicYearAll);
    
    // Auto-populate campus and officer when recipient is selected
    function selectRecipient(recipient) {
        $('#selected_recipient_id').val(recipient.id);
        $('#recipient_search').val(`${recipient.recipient_id} - ${recipient.first_name} ${recipient.last_name}`);
        
        // Populate campus from recipient data and trigger officer filtering
        $('#campus').val(recipient.campus).trigger('change');
        
        // Show recipient details
        const detailsHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div><span class="font-medium text-blue-900">Recipient ID:</span> <span class="text-blue-800">${recipient.recipient_id}</span></div>
                    <div><span class="font-medium text-blue-900">Name:</span> <span class="text-blue-800">${recipient.first_name} ${recipient.last_name}</span></div>
                    <div><span class="font-medium text-blue-900">Email:</span> <span class="text-blue-800">${recipient.email}</span></div>
                </div>
                <div class="space-y-2">
                    <div><span class="font-medium text-blue-900">Campus:</span> <span class="text-blue-800">${recipient.campus}</span></div>
                    <div><span class="font-medium text-blue-900">Course:</span> <span class="text-blue-800">${recipient.course}</span></div>
                    <div><span class="font-medium text-blue-900">Scholarship:</span> <span class="text-blue-800">${recipient.scholarship_type}</span></div>
                </div>
            </div>
        `;
        
        $('#recipient-info').html(detailsHtml);
        $('#recipient-details').removeClass('hidden');
        closeModal();
    }
    
    // Override the existing selectRecipient function
    window.selectRecipient = selectRecipient;
});
</script>
<?= $this->endSection() ?>