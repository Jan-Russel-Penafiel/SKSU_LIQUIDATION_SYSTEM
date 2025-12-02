<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to SKSU Scholarship System!</title>
    <meta name="description" content="SKSU Scholarship Liquidation Monitoring System">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'green-primary': '#16a34a',
                        'green-secondary': '#22c55e', 
                        'green-light': '#86efac',
                        'green-dark': '#166534',
                        'green-accent': '#dcfce7',
                    }
                }
            }
        }
    </script>


</head>
<body class="bg-gray-50 min-h-screen text-sm">

<!-- HEADER: MENU + HERO SECTION -->
<header class="bg-gradient-to-br from-green-primary to-green-dark">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center p-4">
            <div class="flex items-center">
                <i class="bi bi-mortarboard-fill text-white text-3xl mr-3"></i>
                <span class="text-white font-bold text-xl">SKSU</span>
            </div>
            
            <nav class="hidden md:flex space-x-6">
                <a href="<?= base_url('dashboard') ?>" class="text-white hover:text-green-light transition-colors px-4 py-2 rounded-lg hover:bg-white/10">Dashboard</a>
                <a href="<?= base_url('login') ?>" class="text-white hover:text-green-light transition-colors px-4 py-2 rounded-lg hover:bg-white/10">Login</a>
                <a href="<?= base_url('manual-liquidation') ?>" class="text-white hover:text-green-light transition-colors px-4 py-2 rounded-lg hover:bg-white/10">Manual System</a>
                <a href="<?= base_url('atm-liquidation') ?>" class="text-white hover:text-green-light transition-colors px-4 py-2 rounded-lg hover:bg-white/10">ATM System</a>
            </nav>
            
            <button id="menuToggle" class="md:hidden text-white text-2xl">
                <i class="bi bi-list"></i>
            </button>
        </div>
        
        <div id="mobileMenu" class="hidden md:hidden bg-white/10 backdrop-blur-sm">
            <div class="px-4 py-2 space-y-1">
                <a href="<?= base_url('dashboard') ?>" class="block text-white hover:text-green-light transition-colors px-4 py-2 rounded">Dashboard</a>
                <a href="<?= base_url('login') ?>" class="block text-white hover:text-green-light transition-colors px-4 py-2 rounded">Login</a>
                <a href="<?= base_url('manual-liquidation') ?>" class="block text-white hover:text-green-light transition-colors px-4 py-2 rounded">Manual System</a>
                <a href="<?= base_url('atm-liquidation') ?>" class="block text-white hover:text-green-light transition-colors px-4 py-2 rounded">ATM System</a>
            </div>
        </div>

        <div class="text-center py-16 px-4">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                Welcome to SKSU
            </h1>
            <h2 class="text-2xl md:text-3xl text-green-light font-light">
                Scholarship Liquidation Monitoring System
            </h2>
            <p class="text-white/90 mt-4 text-lg max-w-2xl mx-auto">
                Streamline scholarship management with our comprehensive digital platform
            </p>
            <div class="mt-8 space-x-4">
                <a href="<?= base_url('login') ?>" class="inline-flex items-center bg-white text-green-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="bi bi-box-arrow-in-right mr-2"></i>
                    Get Started
                </a>
                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors">
                    <i class="bi bi-speedometer2 mr-2"></i>
                    View Dashboard
                </a>
            </div>
        </div>
    </div>
</header>

<!-- CONTENT -->
<main class="max-w-7xl mx-auto px-4 py-16">
    
    <!-- Features Section -->
    <section class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-green-light/20 hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-gradient-to-br from-green-primary to-green-secondary rounded-xl flex items-center justify-center mb-6">
                <i class="bi bi-people-fill text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Manual Liquidation</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                Comprehensive manual processing system for scholarship liquidation with detailed tracking and documentation capabilities.
            </p>
            <a href="<?= base_url('manual-liquidation') ?>" class="inline-flex items-center text-green-primary font-semibold hover:text-green-dark transition-colors">
                Learn More
                <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-lg border border-green-light/20 hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-gradient-to-br from-green-secondary to-green-light rounded-xl flex items-center justify-center mb-6">
                <i class="bi bi-credit-card-fill text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">ATM Integration</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                Automated ATM liquidation system that streamlines the financial processing and reduces manual intervention.
            </p>
            <a href="<?= base_url('atm-liquidation') ?>" class="inline-flex items-center text-green-primary font-semibold hover:text-green-dark transition-colors">
                Learn More
                <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-lg border border-green-light/20 hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-gradient-to-br from-green-light to-green-primary rounded-xl flex items-center justify-center mb-6">
                <i class="bi bi-graph-up text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Real-time Analytics</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                Advanced dashboard with real-time monitoring, analytics, and comprehensive reporting capabilities for informed decisions.
            </p>
            <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center text-green-primary font-semibold hover:text-green-dark transition-colors">
                Learn More
                <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-gradient-to-r from-green-primary to-green-secondary rounded-3xl p-8 md:p-16 text-white mb-16">
        <div class="text-center mb-12">
            <h3 class="text-4xl font-bold mb-4">System Performance</h3>
            <p class="text-green-light text-xl">Built for efficiency and reliability</p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">99.9%</div>
                <div class="text-green-light">System Uptime</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">24/7</div>
                <div class="text-green-light">Support Available</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">Fast</div>
                <div class="text-green-light">Processing Speed</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">Secure</div>
                <div class="text-green-light">Data Protection</div>
            </div>
        </div>
    </section>

    <!-- Getting Started Section -->
    <section class="text-center">
        <h3 class="text-4xl font-bold text-gray-800 mb-6">Ready to get started?</h3>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Access our comprehensive scholarship liquidation system and streamline your administrative processes today.
        </p>
        <div class="space-x-4">
            <a href="<?= base_url('login') ?>" class="inline-flex items-center bg-gradient-to-r from-green-primary to-green-secondary text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition-all transform hover:-translate-y-1">
                <i class="bi bi-box-arrow-in-right mr-2"></i>
                Access System
            </a>
            <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center border-2 border-green-primary text-green-primary px-8 py-4 rounded-xl font-semibold hover:bg-green-primary hover:text-white transition-all">
                <i class="bi bi-eye mr-2"></i>
                View Demo
            </a>
        </div>
    </section>

</main>

<!-- FOOTER -->
<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <i class="bi bi-mortarboard-fill text-green-light text-2xl mr-3"></i>
                    <span class="font-bold text-xl">SKSU Liquidation System</span>
                </div>
                <p class="text-gray-400 leading-relaxed">
                    Comprehensive scholarship liquidation monitoring system designed for efficiency and transparency.
                </p>
            </div>
            
            <div>
                <h4 class="font-semibold text-lg mb-4">Quick Links</h4>
                <div class="space-y-2">
                    <a href="<?= base_url('dashboard') ?>" class="block text-gray-400 hover:text-green-light transition-colors">Dashboard</a>
                    <a href="<?= base_url('manual-liquidation') ?>" class="block text-gray-400 hover:text-green-light transition-colors">Manual Liquidation</a>
                    <a href="<?= base_url('atm-liquidation') ?>" class="block text-gray-400 hover:text-green-light transition-colors">ATM Liquidation</a>
                    <a href="<?= base_url('login') ?>" class="block text-gray-400 hover:text-green-light transition-colors">Login</a>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-lg mb-4">System Info</h4>
                <div class="space-y-2 text-sm text-gray-400">
                    <p>Environment: <?= ENVIRONMENT ?></p>
                    <p>Page rendered in {elapsed_time} seconds</p>
                    <p>Memory usage: {memory_usage} MB</p>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; <?= date('Y') ?> Sultan Kudarat State University. Built with CodeIgniter 4.</p>
        </div>
    </div>
</footer>

<!-- SCRIPTS -->
<script>
    // Mobile menu toggle
    document.getElementById('menuToggle').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const menuToggle = document.getElementById('menuToggle');
        
        if (!mobileMenu.contains(event.target) && !menuToggle.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

<!-- -->

</body>
</html>
