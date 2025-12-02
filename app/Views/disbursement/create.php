<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Add Scholarship Disbursement
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-plus-circle mr-3 text-green-200"></i>
                        Add Scholarship Disbursement
                    </h1>
                    <nav aria-label="breadcrumb" class="flex items-center space-x-2 text-sm">
                        <a href="<?= base_url('dashboard') ?>" class="text-green-100 hover:text-white transition-colors">
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <a href="<?= base_url('disbursement') ?>" class="text-green-100 hover:text-white transition-colors">
                            Disbursement List
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <span class="text-green-200">Add Disbursement</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <form action="<?= base_url('disbursement/store') ?>" method="POST" id="disbursementForm">
                    <?= csrf_field() ?>
                    
                    <!-- Recipient Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-person mr-2 text-green-600"></i>
                            Recipient Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Recipient Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="recipient_name" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="Enter recipient full name"
                                       value="<?= old('recipient_name') ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Recipient ID/Student ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="recipient_id" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="Enter student/recipient ID"
                                       value="<?= old('recipient_id') ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Course/Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="course_program" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="e.g., Bachelor of Science in Information Technology"
                                       value="<?= old('course_program') ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Year Level <span class="text-red-500">*</span>
                                </label>
                                <select name="year_level" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" <?= old('year_level') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                                    <option value="2nd Year" <?= old('year_level') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                                    <option value="3rd Year" <?= old('year_level') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                                    <option value="4th Year" <?= old('year_level') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
                                    <option value="5th Year" <?= old('year_level') === '5th Year' ? 'selected' : '' ?>>5th Year</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-calendar-academic mr-2 text-green-600"></i>
                            Academic Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
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
                                <input type="text" name="academic_year" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="e.g., 2024-2025"
                                       value="<?= old('academic_year') ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Campus <span class="text-red-500">*</span>
                                </label>
                                <select name="campus" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Campus</option>
                                    <option value="Main Campus" <?= old('campus') === 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                    <option value="Kalamansig Campus" <?= old('campus') === 'Kalamansig Campus' ? 'selected' : '' ?>>Kalamansig Campus</option>
                                    <option value="Palimbang Campus" <?= old('campus') === 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                    <option value="Isulan Campus" <?= old('campus') === 'Isulan Campus' ? 'selected' : '' ?>>Isulan Campus</option>
                                    <option value="Bagumbayan Campus" <?= old('campus') === 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Disbursement Details -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-cash-stack mr-2 text-green-600"></i>
                            Disbursement Details
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Scholarship Type <span class="text-red-500">*</span>
                                </label>
                                <select name="scholarship_type" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Scholarship Type</option>
                                    <option value="CHED Scholarship" <?= old('scholarship_type') === 'CHED Scholarship' ? 'selected' : '' ?>>CHED Scholarship</option>
                                    <option value="TESDA Scholarship" <?= old('scholarship_type') === 'TESDA Scholarship' ? 'selected' : '' ?>>TESDA Scholarship</option>
                                    <option value="DOST Scholarship" <?= old('scholarship_type') === 'DOST Scholarship' ? 'selected' : '' ?>>DOST Scholarship</option>
                                    <option value="UniFAST" <?= old('scholarship_type') === 'UniFAST' ? 'selected' : '' ?>>UniFAST</option>
                                    <option value="Local Government Scholarship" <?= old('scholarship_type') === 'Local Government Scholarship' ? 'selected' : '' ?>>Local Government Scholarship</option>
                                    <option value="Private Scholarship" <?= old('scholarship_type') === 'Private Scholarship' ? 'selected' : '' ?>>Private Scholarship</option>
                                    <option value="Other" <?= old('scholarship_type') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Amount <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">₱</span>
                                    <input type="number" step="0.01" name="amount" 
                                           class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                           placeholder="0.00"
                                           value="<?= old('amount') ?>" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Disbursement Method <span class="text-red-500">*</span>
                                </label>
                                <select name="disbursement_method" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Method</option>
                                    <option value="Cash" <?= old('disbursement_method') === 'Cash' ? 'selected' : '' ?>>Cash</option>
                                    <option value="Check" <?= old('disbursement_method') === 'Check' ? 'selected' : '' ?>>Check</option>
                                    <option value="Bank_Transfer" <?= old('disbursement_method') === 'Bank_Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="ATM" <?= old('disbursement_method') === 'ATM' ? 'selected' : '' ?>>ATM</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Disbursement Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="disbursement_date" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       value="<?= old('disbursement_date', date('Y-m-d')) ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Assign Disbursing Officer <span class="text-red-500">*</span>
                                </label>
                                <select name="disbursing_officer_id" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Disbursing Officer</option>
                                    <?php if (isset($disbursing_officers)): ?>
                                        <?php foreach ($disbursing_officers as $officer): ?>
                                            <option value="<?= $officer['id'] ?>" <?= old('disbursing_officer_id') == $officer['id'] ? 'selected' : '' ?>>
                                                <?= esc($officer['username']) ?> - <?= esc($officer['campus']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Scholarship Coordinator
                                </label>
                                <select name="scholarship_coordinator_id" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">Select Coordinator (Optional)</option>
                                    <?php if (isset($scholarship_coordinators)): ?>
                                        <?php foreach ($scholarship_coordinators as $coordinator): ?>
                                            <option value="<?= $coordinator['id'] ?>" <?= old('scholarship_coordinator_id') == $coordinator['id'] ? 'selected' : '' ?>>
                                                <?= esc($coordinator['username']) ?> - <?= esc($coordinator['campus']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-chat-left-text mr-2 text-green-600"></i>
                            Additional Information
                        </h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks (Optional)
                            </label>
                            <textarea name="remarks" rows="4" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                      placeholder="Enter any additional notes or remarks about this disbursement..."><?= old('remarks') ?></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="bi bi-check-circle mr-2"></i>
                            Save Disbursement
                        </button>
                        
                        <a href="<?= base_url('disbursement') ?>" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="bi bi-x-circle mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Set default academic year to current
    const currentYear = new Date().getFullYear();
    const nextYear = currentYear + 1;
    const defaultAcademicYear = currentYear + '-' + nextYear;
    
    if (!$('input[name="academic_year"]').val()) {
        $('input[name="academic_year"]').val(defaultAcademicYear);
    }
    
    // Form validation
    $('#disbursementForm').on('submit', function(e) {
        let hasError = false;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                hasError = true;
                $(this).addClass('border-red-500');
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        
        // Validate amount
        const amount = parseFloat($('input[name="amount"]').val());
        if (isNaN(amount) || amount <= 0) {
            hasError = true;
            $('input[name="amount"]').addClass('border-red-500');
            alert('Please enter a valid amount greater than 0');
        }
        
        if (hasError) {
            e.preventDefault();
            alert('Please fill in all required fields correctly');
        }
    });
    
    // Remove error styling on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('border-red-500');
    });
});
</script>
<?= $this->endSection() ?>