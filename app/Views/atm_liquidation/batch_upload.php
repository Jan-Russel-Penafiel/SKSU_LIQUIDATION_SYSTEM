<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-file-earmark-spreadsheet text-green-600 mr-3"></i>
            Batch Upload ATM Liquidation
        </h1>
        <p class="text-gray-600">Upload CSV or Excel file with multiple liquidation records</p>
    </div>
    <a href="<?= base_url('atm-liquidation') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
        <i class="bi bi-arrow-left mr-2"></i>
        Back to List
    </a>
</div>

<!-- Instructions Card -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="bi bi-info-circle text-blue-600 text-2xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">File Format Requirements</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li class="flex items-start">
                    <i class="bi bi-check-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                    <span>File must be in CSV or Excel format (.csv, .xlsx, .xls)</span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-check-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                    <span>Required columns: <strong>recipient_code, transaction_date, amount, reference_number</strong></span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-check-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                    <span>Date format: YYYY-MM-DD (e.g., 2025-12-01)</span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-check-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                    <span>Amount should be numeric without currency symbols</span>
                </li>
                <li class="flex items-start">
                    <i class="bi bi-check-circle-fill text-blue-600 mr-2 mt-0.5"></i>
                    <span>Maximum file size: 10MB</span>
                </li>
            </ul>
            <div class="mt-4">
                <a href="<?= base_url('assets/templates/atm_liquidation_template.csv') ?>" class="inline-flex items-center text-blue-700 hover:text-blue-800 font-medium">
                    <i class="bi bi-download mr-2"></i>
                    Download Sample Template
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Upload Form -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-xl">
        <h3 class="text-lg font-semibold text-white">Upload Liquidation File</h3>
    </div>
    
    <form action="<?= base_url('atm-liquidation/process-batch') ?>" method="POST" enctype="multipart/form-data" class="p-6">
        <?= csrf_field() ?>
        
        <!-- Batch Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Batch Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="batch_name" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="e.g., December 2025 ATM Liquidation"
                       value="<?= old('batch_name') ?>">
                <?php if (isset($errors['batch_name'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= $errors['batch_name'] ?></p>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select name="semester" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Select Semester</option>
                    <option value="1st Semester" <?= old('semester') === '1st Semester' ? 'selected' : '' ?>>1st Semester</option>
                    <option value="2nd Semester" <?= old('semester') === '2nd Semester' ? 'selected' : '' ?>>2nd Semester</option>
                    <option value="Summer" <?= old('semester') === 'Summer' ? 'selected' : '' ?>>Summer</option>
                </select>
                <?php if (isset($errors['semester'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= $errors['semester'] ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Academic Year <span class="text-red-500">*</span>
                </label>
                <select name="academic_year" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Select Academic Year</option>
                    <option value="2023-2024" <?= old('academic_year') === '2023-2024' ? 'selected' : '' ?>>2023-2024</option>
                    <option value="2024-2025" <?= old('academic_year') === '2024-2025' ? 'selected' : '' ?>>2024-2025</option>
                    <option value="2025-2026" <?= old('academic_year') === '2025-2026' ? 'selected' : '' ?>>2025-2026</option>
                </select>
                <?php if (isset($errors['academic_year'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= $errors['academic_year'] ?></p>
                <?php endif; ?>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Remarks (Optional)
                </label>
                <input type="text" name="remarks" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Additional notes"
                       value="<?= old('remarks') ?>">
            </div>
        </div>
        
        <!-- File Upload -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Upload File <span class="text-red-500">*</span>
            </label>
            <div class="upload-area" id="uploadArea">
                <label for="liquidation_file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-300">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="uploadPrompt">
                        <i class="bi bi-cloud-upload text-6xl text-gray-400 mb-4"></i>
                        <p class="mb-2 text-lg text-gray-600"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-sm text-gray-500 mb-2">CSV, XLSX, or XLS files only</p>
                        <p class="text-xs text-gray-400">Maximum file size: 10MB</p>
                    </div>
                    <input id="liquidation_file" name="liquidation_file" type="file" class="hidden" accept=".csv,.xlsx,.xls" required />
                </label>
            </div>
            
            <!-- Enhanced File Preview Container -->
            <div id="file-preview" class="mt-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-300 rounded-xl p-6 hidden shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div id="file-icon" class="mr-4 text-4xl"></div>
                        <div class="flex-1">
                            <div id="file-name" class="font-semibold text-green-900 text-lg mb-2"></div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <i class="bi bi-hdd text-green-600 mr-2"></i>
                                    <span class="text-sm text-green-700">Size: <span id="file-size" class="font-medium"></span></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-file-earmark text-green-600 mr-2"></i>
                                    <span class="text-sm text-green-700">Type: <span id="file-type" class="font-medium"></span></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-clock text-green-600 mr-2"></i>
                                    <span class="text-sm text-green-700">Uploaded: <span id="upload-time" class="font-medium"></span></span>
                                </div>
                            </div>
                            <div class="flex items-center mt-3">
                                <i class="bi bi-check-circle-fill text-green-600 mr-2"></i>
                                <span class="text-sm text-green-700 font-medium">File ready for processing</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <button type="button" id="preview-file" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors px-3 py-2 hover:bg-blue-50 rounded-lg text-sm font-medium" title="Preview file content">
                            <i class="bi bi-eye mr-1"></i>
                            Preview
                        </button>
                        <button type="button" id="remove-file" class="flex items-center text-red-600 hover:text-red-800 transition-colors px-3 py-2 hover:bg-red-50 rounded-lg text-sm font-medium" title="Remove and select different file">
                            <i class="bi bi-x-circle mr-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            
            <?php if (isset($errors['liquidation_file'])): ?>
                <p class="text-red-500 text-xs mt-1"><?= $errors['liquidation_file'] ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="<?= base_url('atm-liquidation') ?>" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                <i class="bi bi-upload mr-2"></i>
                Upload and Process
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.upload-area {
    position: relative;
    transition: all 0.3s ease;
}

.upload-area:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.upload-area.dragover {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%) !important;
    border-color: #16a34a !important;
    transform: scale(1.02);
}

#file-preview {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.file-info-grid {
    display: grid;
    gap: 1rem;
}

@media (min-width: 768px) {
    .file-info-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.file-preview-button {
    transition: all 0.2s ease;
}

.file-preview-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Enhanced file type icons */
.bi-file-earmark-text {
    color: #059669 !important;
}

.bi-file-earmark-excel {
    color: #0d7377 !important;
}

/* Loading animation for file processing */
.processing {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('liquidation_file');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const filePreview = document.getElementById('file-preview');
    const removeBtn = document.getElementById('remove-file');
    const previewBtn = document.getElementById('preview-file');
    let currentFile = null;
    
    // File input change handler
    fileInput.addEventListener('change', function(e) {
        handleFileSelection(this.files[0]);
    });
    
    // Drag and drop handlers
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('border-green-500', 'bg-green-50');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-green-500', 'bg-green-50');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-green-500', 'bg-green-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            fileInput.files = files;
            handleFileSelection(file);
        }
    });
    
    // Remove file handler
    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        currentFile = null;
        hideFilePreview();
        showUploadPrompt();
    });
    
    // Preview file handler
    previewBtn.addEventListener('click', function() {
        if (currentFile) {
            previewFile(currentFile);
        }
    });
    
    function handleFileSelection(file) {
        if (!file) {
            hideFilePreview();
            showUploadPrompt();
            return;
        }
        
        if (!validateFile(file)) {
            fileInput.value = '';
            currentFile = null;
            hideFilePreview();
            showUploadPrompt();
            return;
        }
        
        currentFile = file;
        showFilePreview(file);
        hideUploadPrompt();
    }
    
    function validateFile(file) {
        const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        const allowedExtensions = ['.csv', '.xls', '.xlsx'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        const extension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(extension)) {
            alert('Invalid file type. Please select a CSV or Excel file.');
            return false;
        }
        
        if (file.size > maxSize) {
            alert('File size exceeds 10MB limit. Please select a smaller file.');
            return false;
        }
        
        return true;
    }
    
    function showFilePreview(file) {
        const fileName = file.name;
        const fileSize = formatFileSize(file.size);
        const extension = '.' + fileName.split('.').pop().toLowerCase();
        const uploadTime = new Date().toLocaleTimeString();
        
        // Set file icon based on extension
        let icon = '';
        let fileType = '';
        if (extension === '.csv') {
            icon = '<i class="bi bi-file-earmark-text text-green-600"></i>';
            fileType = 'CSV File';
        } else if (extension === '.xlsx') {
            icon = '<i class="bi bi-file-earmark-excel text-green-600"></i>';
            fileType = 'Excel File (XLSX)';
        } else if (extension === '.xls') {
            icon = '<i class="bi bi-file-earmark-excel text-green-600"></i>';
            fileType = 'Excel File (XLS)';
        } else {
            icon = '<i class="bi bi-file-earmark text-gray-600"></i>';
            fileType = 'Unknown';
        }
        
        document.getElementById('file-icon').innerHTML = icon;
        document.getElementById('file-name').textContent = fileName;
        document.getElementById('file-size').textContent = fileSize;
        document.getElementById('file-type').textContent = fileType;
        document.getElementById('upload-time').textContent = uploadTime;
        
        filePreview.classList.remove('hidden');
    }
    
    function hideFilePreview() {
        filePreview.classList.add('hidden');
    }
    
    function showUploadPrompt() {
        uploadPrompt.classList.remove('hidden');
    }
    
    function hideUploadPrompt() {
        uploadPrompt.classList.add('hidden');
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function previewFile(file) {
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            let content = e.target.result;
            let previewContent = '';
            
            if (file.name.toLowerCase().endsWith('.csv')) {
                // For CSV files, show first few lines
                const lines = content.split('\n').slice(0, 5);
                previewContent = '<pre class="text-sm bg-gray-100 p-3 rounded overflow-x-auto">' + lines.join('\n') + '\n... (showing first 5 lines)</pre>';
            } else {
                // For Excel files, show file info
                previewContent = '<div class="text-sm text-gray-600"><p><strong>File Type:</strong> Excel file</p><p><strong>Note:</strong> Excel files will be processed during upload. Preview not available for Excel files.</p></div>';
            }
            
            // Create a modal or alert to show preview
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full m-4 max-h-96 overflow-hidden">
                    <div class="p-4 bg-green-600 text-white flex justify-between items-center">
                        <h3 class="text-lg font-semibold">File Preview: ${file.name}</h3>
                        <button type="button" class="text-white hover:text-gray-200" onclick="this.closest('.fixed').remove()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-80">
                        ${previewContent}
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        };
        
        reader.readAsText(file);
    }
});
</script>
<?= $this->endSection() ?>
