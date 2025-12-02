<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SKSU Scholarship Liquidation Monitoring System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-600 via-green-700 to-green-800 flex items-center justify-center p-4 text-sm">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-green-200/20">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 p-2">
                    <img src="<?= base_url('sksu1.png') ?>" alt="SKSU Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Sultan Kudarat State University</h1>
                <p class="text-green-100 text-sm">Create New Account</p>
            </div>
            
            <!-- Register Form -->
            <div class="p-8">
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-6 flex items-center">
                    <i class="bi bi-check-circle-fill text-green-600 mr-3"></i>
                    <span class="text-green-800 text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6 flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3"></i>
                    <span class="text-red-800 text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-red-800 font-semibold text-sm mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <form action="<?= base_url('register') ?>" method="POST" class="space-y-5">
                    <?= csrf_field() ?>
                    
                    <!-- Username -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-person-fill text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="username" 
                                placeholder="Username" 
                                value="<?= old('username') ?>" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                            >
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope-fill text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Email Address" 
                                value="<?= old('email') ?>" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                            >
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-briefcase-fill text-gray-400"></i>
                            </div>
                            <select 
                                name="role" 
                                required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white appearance-none"
                            >
                                <option value="">Select Role</option>
                                <option value="disbursing_officer" <?= old('role') == 'disbursing_officer' ? 'selected' : '' ?>>Disbursing Officer</option>
                                <option value="scholarship_coordinator" <?= old('role') == 'scholarship_coordinator' ? 'selected' : '' ?>>Scholarship Coordinator</option>
                                <option value="scholarship_chairman" <?= old('role') == 'scholarship_chairman' ? 'selected' : '' ?>>Scholarship Chairman</option>
                                <option value="accounting_officer" <?= old('role') == 'accounting_officer' ? 'selected' : '' ?>>Accounting Officer</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campus -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-geo-alt-fill text-gray-400"></i>
                            </div>
                            <select 
                                name="campus" 
                                required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white appearance-none"
                            >
                                <option value="">Select Campus</option>
                                <option value="Main Campus" <?= old('campus') == 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                                <option value="Tacurong Campus" <?= old('campus') == 'Tacurong Campus' ? 'selected' : '' ?>>Tacurong Campus</option>
                                <option value="Bagumbayan Campus" <?= old('campus') == 'Bagumbayan Campus' ? 'selected' : '' ?>>Bagumbayan Campus</option>
                                <option value="Lutayan Campus" <?= old('campus') == 'Lutayan Campus' ? 'selected' : '' ?>>Lutayan Campus</option>
                                <option value="Palimbang Campus" <?= old('campus') == 'Palimbang Campus' ? 'selected' : '' ?>>Palimbang Campus</option>
                                <option value="Senator Ninoy Aquino Campus" <?= old('campus') == 'Senator Ninoy Aquino Campus' ? 'selected' : '' ?>>Senator Ninoy Aquino Campus</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="bi bi-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock-fill text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                placeholder="Password (minimum 8 characters)" 
                                required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                id="password"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword('password', 'passwordToggle')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="bi bi-eye-fill" id="passwordToggle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock-fill text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="confirm_password" 
                                placeholder="Confirm Password" 
                                required
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                id="confirm_password"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword('confirm_password', 'confirmPasswordToggle')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="bi bi-eye-fill" id="confirmPasswordToggle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="bi bi-person-plus mr-2"></i>
                        CREATE ACCOUNT
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Already have an account? 
                        <a href="<?= base_url('login') ?>" class="text-green-600 hover:text-green-700 font-medium transition-colors">
                            Login here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.classList.remove('bi-eye-fill');
                toggle.classList.add('bi-eye-slash-fill');
            } else {
                input.type = 'password';
                toggle.classList.remove('bi-eye-slash-fill');
                toggle.classList.add('bi-eye-fill');
            }
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
            alerts.forEach(function(alert) {
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            });
        }, 5000);

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // Remove existing strength indicator
            const existingIndicator = document.querySelector('.password-strength');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            if (password.length > 0) {
                const indicator = document.createElement('div');
                indicator.className = 'password-strength mt-2 text-xs';
                
                let strengthText = '';
                let strengthColor = '';
                
                switch (strength) {
                    case 1:
                        strengthText = 'Weak password';
                        strengthColor = 'text-red-600';
                        break;
                    case 2:
                        strengthText = 'Fair password';
                        strengthColor = 'text-yellow-600';
                        break;
                    case 3:
                        strengthText = 'Good password';
                        strengthColor = 'text-blue-600';
                        break;
                    case 4:
                        strengthText = 'Strong password';
                        strengthColor = 'text-green-600';
                        break;
                }
                
                indicator.innerHTML = `<span class="${strengthColor}">${strengthText}</span>`;
                this.parentNode.parentNode.appendChild(indicator);
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            return Math.min(strength, 4);
        }

        // Confirm password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            // Remove existing match indicator
            const existingIndicator = document.querySelector('.password-match');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            if (confirmPassword.length > 0) {
                const indicator = document.createElement('div');
                indicator.className = 'password-match mt-2 text-xs';
                
                if (password === confirmPassword) {
                    indicator.innerHTML = '<span class="text-green-600"><i class="bi bi-check-circle-fill mr-1"></i>Passwords match</span>';
                } else {
                    indicator.innerHTML = '<span class="text-red-600"><i class="bi bi-x-circle-fill mr-1"></i>Passwords do not match</span>';
                }
                
                this.parentNode.parentNode.appendChild(indicator);
            }
        });
    </script>
</body>
</html>