<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Edit Account
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-person-gear mr-3 text-green-200"></i>
                        Edit Account - <?= esc($edit_user['username']) ?>
                    </h1>
                    <nav aria-label="breadcrumb" class="flex items-center space-x-2 text-sm">
                        <a href="<?= base_url('dashboard') ?>" class="text-green-100 hover:text-white transition-colors">
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <a href="<?= base_url('accounts') ?>" class="text-green-100 hover:text-white transition-colors">
                            Account Management
                        </a>
                        <i class="bi bi-chevron-right text-green-200"></i>
                        <span class="text-green-200">Edit Account</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <form action="<?= base_url('accounts/update/' . $edit_user['id']) ?>" method="POST" id="editAccountForm">
                    <?= csrf_field() ?>
                    
                    <!-- Account Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-person-circle mr-2 text-green-600"></i>
                            Account Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="username" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="Enter username"
                                       value="<?= old('username', $edit_user['username']) ?>" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                       placeholder="Enter email address"
                                       value="<?= old('email', $edit_user['email']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Role and Campus -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-shield-check mr-2 text-green-600"></i>
                            Role and Assignment
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" <?= old('role', $edit_user['role']) === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                    <option value="disbursing_officer" <?= old('role', $edit_user['role']) === 'disbursing_officer' ? 'selected' : '' ?>>Disbursing Officer</option>
                                    <option value="scholarship_coordinator" <?= old('role', $edit_user['role']) === 'scholarship_coordinator' ? 'selected' : '' ?>>Scholarship Coordinator</option>
                                    <option value="scholarship_chairman" <?= old('role', $edit_user['role']) === 'scholarship_chairman' ? 'selected' : '' ?>>Scholarship Chairman</option>
                                    <option value="accounting_officer" <?= old('role', $edit_user['role']) === 'accounting_officer' ? 'selected' : '' ?>>Accounting Officer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Campus <span class="text-red-500">*</span>
                                </label>
                                <select name="campus" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm" required>
                                    <option value="">Select Campus</option>
                                    <option value="Main Campus" <?= old('campus', $edit_user['campus']) === 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                    <option value="Kalamansig Campus" <?= old('campus', $edit_user['campus']) === 'Kalamansig Campus' ? 'selected' : '' ?>>Kalamansig Campus</option>
                                    <option value="Palimbang Campus" <?= old('campus', $edit_user['campus']) === 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                    <option value="Isulan Campus" <?= old('campus', $edit_user['campus']) === 'Isulan Campus' ? 'selected' : '' ?>>Isulan Campus</option>
                                    <option value="Bagumbayan Campus" <?= old('campus', $edit_user['campus']) === 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-lock mr-2 text-green-600"></i>
                            Password Update
                        </h2>
                        <p class="text-sm text-gray-600 mb-4">Leave password fields empty to keep the current password.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" 
                                           class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                           placeholder="Enter new password (optional)"
                                           minlength="8">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800" 
                                            onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-toggle-icon"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="confirm_password" 
                                           class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"
                                           placeholder="Confirm new password">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800" 
                                            onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password-toggle-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="bi bi-info-circle mr-2 text-green-600"></i>
                            Account Status
                        </h2>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Current Status:</p>
                                    <p class="text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $edit_user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $edit_user['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Account created: <?= date('M j, Y', strtotime($edit_user['created_at'])) ?></p>
                                    <p class="text-sm text-gray-600">Last updated: <?= date('M j, Y', strtotime($edit_user['updated_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="bi bi-check-circle mr-2"></i>
                            Update Account
                        </button>
                        
                        <a href="<?= base_url('accounts') ?>" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="bi bi-x-circle mr-2"></i>
                            Cancel
                        </a>

                        <?php if ($edit_user['id'] != $user['id']): ?>
                        <a href="<?= base_url('accounts/toggle-status/' . $edit_user['id']) ?>" 
                           class="inline-flex items-center justify-center px-6 py-3 border text-sm font-medium rounded-lg transition-colors <?= $edit_user['is_active'] ? 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' ?>"
                           onclick="return confirm('Are you sure you want to <?= $edit_user['is_active'] ? 'deactivate' : 'activate' ?> this account?')">
                            <i class="bi bi-<?= $edit_user['is_active'] ? 'pause' : 'play' ?>-circle mr-2"></i>
                            <?= $edit_user['is_active'] ? 'Deactivate' : 'Activate' ?> Account
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function togglePassword(fieldName) {
    const passwordField = document.querySelector(`input[name="${fieldName}"]`);
    const toggleIcon = document.getElementById(`${fieldName}-toggle-icon`);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}

$(document).ready(function() {
    // Form validation
    $('#editAccountForm').on('submit', function(e) {
        let hasError = false;
        const password = $('input[name="password"]').val();
        const confirmPassword = $('input[name="confirm_password"]').val();
        
        // Check password confirmation if password is provided
        if (password || confirmPassword) {
            if (password !== confirmPassword) {
                hasError = true;
                alert('Password confirmation does not match');
                $('input[name="confirm_password"]').addClass('border-red-500');
            }
            
            if (password.length < 8) {
                hasError = true;
                alert('Password must be at least 8 characters long');
                $('input[name="password"]').addClass('border-red-500');
            }
        }
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                hasError = true;
                $(this).addClass('border-red-500');
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('Please fill in all required fields correctly');
        }
    });
    
    // Remove error styling on input
    $('input, select').on('input change', function() {
        $(this).removeClass('border-red-500');
    });
});
</script>
<?= $this->endSection() ?>