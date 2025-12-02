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
            <div class="flex items-center justify-center w-full">
                <label for="liquidation_file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="bi bi-cloud-upload text-5xl text-gray-400 mb-4"></i>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500">CSV, XLSX, or XLS (MAX. 10MB)</p>
                        <div id="fileName" class="mt-4 text-sm text-green-600 font-medium hidden"></div>
                    </div>
                    <input id="liquidation_file" name="liquidation_file" type="file" class="hidden" accept=".csv,.xlsx,.xls" required />
                </label>
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

<?= $this->section('scripts') ?>
<script>
document.getElementById('liquidation_file').addEventListener('change', function(e) {
    const fileNameDiv = document.getElementById('fileName');
    if (this.files.length > 0) {
        const file = this.files[0];
        fileNameDiv.textContent = `Selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
        fileNameDiv.classList.remove('hidden');
    } else {
        fileNameDiv.classList.add('hidden');
    }
});
</script>
<?= $this->endSection() ?>
