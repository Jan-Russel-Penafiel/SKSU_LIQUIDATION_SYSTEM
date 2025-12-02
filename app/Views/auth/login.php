<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SKSU Scholarship Liquidation Monitoring System</title>
    
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
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-green-200/20">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 p-2">
                    <img src="<?= base_url('sksu1.png') ?>" alt="SKSU Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Sultan Kudarat State University</h1>
                <p class="text-green-100 text-sm">Scholarship Liquidation Monitoring System</p>
            </div>
            
            <!-- Login Form -->
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
                
                <form action="<?= base_url('login') ?>" method="POST" class="space-y-6">
                    <?= csrf_field() ?>
                    
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
                    
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock-fill text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                placeholder="Password" 
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                            >
                        </div>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="bi bi-box-arrow-in-right mr-2"></i>
                        LOGIN
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Don't have an account? 
                        <a href="<?= base_url('register') ?>" class="text-green-600 hover:text-green-700 font-medium transition-colors">
                            Register here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>