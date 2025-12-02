<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
    <div class="mb-4 lg:mb-0">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-plus-circle text-green-600 mr-3"></i>
            Create ATM Liquidation
        </h2>
        <p class="text-gray-600 mt-1 text-sm">Create a new ATM liquidation record with optional CSV or Excel file attachment</p>
    </div>
    
    <a href="<?= base_url('atm-liquidation') ?>" class="inline-flex items-center border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
        <i class="bi bi-arrow-left mr-2"></i>
        Back to ATM Liquidations
    </a>
</div>

<!-- Instructions -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-6">
    <div class="p-6 border-b border-gray-200">
        <h5 class="text-xl font-semibold text-gray-900 flex items-center">
            <i class="bi bi-info-circle text-green-600 mr-3"></i>
            Liquidation Form Instructions
        </h5>
    </div>
    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-4">Required Information:</h6>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="bi bi-check-circle text-green-600 mr-3 mt-1"></i>
                        <span><strong>Recipient</strong> - Search and select the scholarship recipient</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-check-circle text-green-600 mr-3 mt-1"></i>
                        <span><strong>Transaction Date</strong> - Date of ATM withdrawal or transaction</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-check-circle text-green-600 mr-3 mt-1"></i>
                        <span><strong>Amount</strong> - Amount withdrawn or liquidated</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-check-circle text-green-600 mr-3 mt-1"></i>
                        <span><strong>Reference Number</strong> - Bank reference/transaction number</span>
                    </li>
                </ul>
                
                <h6 class="text-lg font-semibold text-gray-900 mb-4 mt-6">Required File Attachment:</h6>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <i class="bi bi-file-earmark-text text-green-600 mr-3 mt-1"></i>
                        <span><strong>CSV files</strong> (.csv) - Comma-separated values</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-file-earmark-excel text-green-600 mr-3 mt-1"></i>
                        <span><strong>Excel files</strong> (.xlsx, .xls) - Microsoft Excel format</span>
                    </li>
                    <li class="flex items-start">
                        <i class="bi bi-file-earmark-pdf text-green-600 mr-3 mt-1"></i>
                        <span><strong>PDF files</strong> (.pdf) - Bank certification or receipts</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h6 class="text-lg font-semibold text-gray-900 mb-4">Workflow Process:</h6>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-4 mt-1 flex-shrink-0">
                            1
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Pending Status</div>
                            <div class="text-gray-600 text-sm">Liquidation is created and awaits verification</div>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-4 mt-1 flex-shrink-0">
                            2
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Verification</div>
                            <div class="text-gray-600 text-sm">Disbursing officer verifies the transaction details</div>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-4 mt-1 flex-shrink-0">
                            3
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Approval</div>
                            <div class="text-gray-600 text-sm">Scholarship chairman approves the liquidation</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liquidation Form -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20">
    <div class="p-6 border-b border-gray-200">
        <h5 class="text-xl font-semibold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-plus text-green-600 mr-3"></i>
            Create Liquidation Record
        </h5>
    </div>
    <div class="p-6">
        <form action="<?= base_url('atm-liquidation/store') ?>" method="POST" enctype="multipart/form-data" id="liquidationForm">
            <?= csrf_field() ?>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search Recipient <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" 
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
                    <div class="text-sm text-gray-600 mt-1">Search and select the scholarship recipient for this liquidation</div>
                </div>
                
                <!-- Recipient Details (Populated after search) -->
                <div id="recipient-details" class="bg-blue-50 border border-blue-200 rounded-xl p-4 hidden">
                    <h6 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="bi bi-info-circle mr-2"></i>
                        Selected Recipient Details
                    </h6>
                    <div id="recipient-info" class="text-sm text-blue-800"></div>
                </div>                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" 
                               name="transaction_date" 
                               value="<?= old('transaction_date', date('Y-m-d')) ?>" 
                               max="<?= date('Y-m-d') ?>"
                               required>
                        <div class="text-sm text-gray-600 mt-1">Date of the ATM withdrawal or transaction</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                            <input type="number" 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" 
                                   name="amount" 
                                   placeholder="0.00" 
                                   step="0.01" 
                                   min="0.01"
                                   value="<?= old('amount') ?>" 
                                   required>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Amount withdrawn or liquidated</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reference Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" 
                               name="reference_number" 
                               placeholder="e.g., REF-2024-12345" 
                               value="<?= old('reference_number') ?>" 
                               required>
                        <div class="text-sm text-gray-600 mt-1">Bank reference or transaction number</div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester" <?= old('semester') === '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                            <option value="2nd Semester" <?= old('semester') === '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                            <option value="Summer" <?= old('semester') === 'Summer' ? 'selected' : '' ?>>Summer</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent" name="academic_year" required>
                            <option value="">Select Academic Year</option>
                            <option value="2023-2024" <?= old('academic_year') === '2023-2024' ? 'selected' : '' ?>>2023-2024</option>
                            <option value="2024-2025" <?= old('academic_year') === '2024-2025' ? 'selected' : '' ?>>2024-2025</option>
                            <option value="2025-2026" <?= old('academic_year') === '2025-2026' ? 'selected' : '' ?>>2025-2026</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Attach File <span class="text-red-500">*</span>
                        </label>
                        <div class="upload-area border-2 border-dashed border-gray-300 p-6 text-center rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer relative">
                            <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" name="attachment_file" 
                                   accept=".csv,.xlsx,.xls,.pdf" id="fileInput" required>
                            <i class="bi bi-paperclip text-4xl text-gray-400 mb-3 pointer-events-none"></i>
                            <h5 class="text-gray-600 font-semibold mb-2 text-sm pointer-events-none">Drag and drop your file here</h5>
                            <p class="text-gray-500 mb-3 text-xs pointer-events-none">or</p>
                            <div class="inline-block pointer-events-none">
                                <span class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors">
                                    Choose File
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 mt-3 pointer-events-none">
                                Maximum file size: 10MB<br>
                                Supported: CSV, Excel, PDF
                            </div>
                        </div>
                        
                        <!-- File Preview Container -->
                        <div id="file-preview" class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4 hidden">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div id="file-icon" class="mr-3 text-2xl"></div>
                                    <div>
                                        <div id="file-name" class="font-medium text-green-900"></div>
                                        <div id="file-size" class="text-sm text-green-700"></div>
                                    </div>
                                </div>
                                <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 transition-colors" title="Remove file">
                                    <i class="bi bi-x-circle text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Remarks (Optional)
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent resize-none" 
                                  name="remarks" 
                                  rows="3" 
                                  placeholder="Additional notes or comments..."><?= old('remarks') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                <a href="<?= base_url('atm-liquidation') ?>" class="inline-flex items-center border border-gray-300 text-gray-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    <i class="bi bi-x-circle mr-2"></i>
                    Cancel
                </a>
                
                <button type="submit" class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:-translate-y-1" id="submitBtn">
                    <i class="bi bi-check-circle mr-2"></i>
                    Create Liquidation
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.upload-area {
    position: relative;
}

.upload-area.dragover {
    background-color: #dcfce7 !important;
    border-color: #16a34a !important;
}

#recipient_results .recipient-item:hover {
    background-color: #dcfce7;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const fileInput = $('#fileInput');
    const uploadArea = $('.upload-area');
    const submitBtn = $('#submitBtn');
    const liquidationForm = $('#liquidationForm');
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
        
        // Show recipient details
        const detailsHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <div><span class="font-medium text-blue-900">Recipient ID:</span> <span class="text-blue-800">${recipient.recipient_id}</span></div>
                    <div><span class="font-medium text-blue-900">Name:</span> <span class="text-blue-800">${recipient.first_name} ${recipient.last_name}</span></div>
                    <div><span class="font-medium text-blue-900">Email:</span> <span class="text-blue-800">${recipient.email}</span></div>
                </div>
                <div class="space-y-1">
                    <div><span class="font-medium text-blue-900">Campus:</span> <span class="text-blue-800">${recipient.campus}</span></div>
                    <div><span class="font-medium text-blue-900">Course:</span> <span class="text-blue-800">${recipient.course}</span></div>
                    <div><span class="font-medium text-blue-900">Scholarship:</span> <span class="text-blue-800">${recipient.scholarship_type}</span></div>
                </div>
            </div>
        `;
        
        $('#recipient-info').html(detailsHtml);
        $('#recipient-details').removeClass('hidden');
    }
    
    // File input change handler
    fileInput.change(function() {
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            if (!isValidFile(file)) {
                alert('Please select a valid CSV, Excel, or PDF file.');
                $(this).val('');
                hideFilePreview();
            } else {
                showFilePreview(file);
            }
        } else {
            hideFilePreview();
        }
    });
    
    // Drag and drop handlers
    uploadArea.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });
    
    uploadArea.on('dragleave dragend', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });
    
    uploadArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (isValidFile(file)) {
                fileInput[0].files = files;
                showFilePreview(file);
            } else {
                alert('Please select a valid CSV, Excel, or PDF file.');
                hideFilePreview();
            }
        }
    });
    
    // Form submission
    liquidationForm.submit(function(e) {
        // Validate required fields
        if (!$(this)[0].checkValidity()) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Double-check file is present since it's required
        if (!fileInput[0].files || fileInput[0].files.length === 0) {
            e.preventDefault();
            alert('Please attach a file. File attachment is required.');
            return false;
        }
        
        // Show loading state
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="bi bi-hourglass-split mr-2"></i>Creating...');
    });
    

    
    // Remove file handler
    $(document).on('click', '#remove-file', function() {
        fileInput.val('');
        hideFilePreview();
    });
    
    function showFilePreview(file) {
        const fileName = file.name;
        const fileSize = formatFileSize(file.size);
        const extension = '.' + fileName.split('.').pop().toLowerCase();
        
        // Set file icon based on extension
        let icon = '';
        if (extension === '.csv') {
            icon = '<i class="bi bi-file-earmark-text text-green-600"></i>';
        } else if (extension === '.xlsx' || extension === '.xls') {
            icon = '<i class="bi bi-file-earmark-excel text-green-600"></i>';
        } else if (extension === '.pdf') {
            icon = '<i class="bi bi-file-earmark-pdf text-red-600"></i>';
        } else {
            icon = '<i class="bi bi-file-earmark text-gray-600"></i>';
        }
        
        $('#file-icon').html(icon);
        $('#file-name').text(fileName);
        $('#file-size').text(fileSize);
        $('#file-preview').removeClass('hidden');
    }
    
    function hideFilePreview() {
        $('#file-preview').addClass('hidden');
        $('#file-icon').empty();
        $('#file-name').empty();
        $('#file-size').empty();
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function isValidFile(file) {
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 
                             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'application/pdf'];
        const allowedExtensions = ['.csv', '.xls', '.xlsx', '.pdf'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        const extension = '.' + file.name.split('.').pop().toLowerCase();
        
        return (allowedTypes.includes(file.type) || allowedExtensions.includes(extension)) && file.size <= maxSize;
    }
    

});


</script>
<?= $this->endSection() ?>