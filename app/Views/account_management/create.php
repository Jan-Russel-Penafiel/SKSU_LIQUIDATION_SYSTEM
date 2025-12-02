<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Create New Account
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-50/30 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-person-plus mr-3 text-green-200"></i>
                        Create New Account
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
                        <span class="text-green-200">Create Account</span>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <form action="<?= base_url('accounts/store') ?>" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                id="username" 
                                name="username" 
                                value="<?= old('username') ?>"
                                required
                            >
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Please provide a valid username (3-50 characters).</p>
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                id="email" 
                                name="email" 
                                value="<?= old('email') ?>"
                                required
                            >
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Please provide a valid email address.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                id="password" 
                                name="password" 
                                minlength="8"
                                required
                            >
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Password must be at least 8 characters long.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                            >
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Please confirm your password.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm peer" 
                                id="role" 
                                name="role" 
                                required
                            >
                                <option value="">Select Role</option>
                                <option value="disbursing_officer" <?= old('role') === 'disbursing_officer' ? 'selected' : '' ?>>Disbursing Officer</option>
                                <option value="scholarship_coordinator" <?= old('role') === 'scholarship_coordinator' ? 'selected' : '' ?>>Scholarship Coordinator</option>
                                <option value="scholarship_chairman" <?= old('role') === 'scholarship_chairman' ? 'selected' : '' ?>>Scholarship Chairman</option>
                                <option value="accounting_officer" <?= old('role') === 'accounting_officer' ? 'selected' : '' ?>>Accounting Officer</option>
                            </select>
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a role.</p>
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
                                <option value="Main Campus" <?= old('campus') === 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                <option value="Kalamansig Campus" <?= old('campus') === 'Kalamansig Campus' ? 'selected' : '' ?>>Kalamansig Campus</option>
                                <option value="Palimbang Campus" <?= old('campus') === 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                <option value="Isulan Campus" <?= old('campus') === 'Isulan Campus' ? 'selected' : '' ?>>Isulan Campus</option>
                                <option value="Bagumbayan Campus" <?= old('campus') === 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                            </select>
                            <p class="text-sm text-red-600 hidden peer-invalid:block">Please select a campus.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                        >
                            <i class="bi bi-check-lg mr-2"></i>
                            Create Account
                        </button>
                        <a 
                            href="<?= base_url('accounts') ?>" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                        >
                            <i class="bi bi-arrow-left mr-2"></i>
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
    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Password confirmation validation
    $('#confirm_password').on('keyup', function() {
        var password = $('#password').val();
        var confirmPassword = $(this).val();
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    $('#password').on('keyup', function() {
        var confirmPassword = $('#confirm_password').val();
        if (confirmPassword) {
            $('#confirm_password').trigger('keyup');
        }
    });
});
</script>
<?= $this->endSection() ?>