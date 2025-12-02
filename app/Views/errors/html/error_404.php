<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= lang('Errors.pageNotFound') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center p-4 text-sm">
    <div class="max-w-md w-full text-center">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center">
                <i class="bi bi-exclamation-triangle text-6xl text-green-primary"></i>
            </div>
        </div>

        <!-- Error Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-green-200/20 p-8">
            <h1 class="text-5xl font-bold text-green-primary mb-4">404</h1>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            
            <div class="text-gray-600 mb-8">
                <?php if (ENVIRONMENT !== 'production') : ?>
                    <p class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-800 text-sm">
                        <?= nl2br(esc($message)) ?>
                    </p>
                <?php else : ?>
                    <p><?= lang('Errors.sorryCannotFind') ?></p>
                    <p class="text-sm mt-2">The page you're looking for might have been moved, deleted, or doesn't exist.</p>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="<?= base_url() ?>" 
                   class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all">
                    <i class="bi bi-house mr-2"></i>
                    Go to Homepage
                </a>
                
                <button onclick="history.back()" 
                        class="block w-full px-6 py-3 border border-green-300 rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Go Back
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>&copy; <?= date('Y') ?> SKSU Scholarship System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
