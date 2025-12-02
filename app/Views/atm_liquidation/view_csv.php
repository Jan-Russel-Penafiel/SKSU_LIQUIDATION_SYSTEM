<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?= base_url('dashboard') ?>" class="text-gray-500 hover:text-green-600 transition-colors">
                    <i class="bi bi-house-door mr-2"></i>Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?= base_url('atm-liquidation') ?>" class="text-gray-500 hover:text-green-600 ml-1 md:ml-2 transition-colors">ATM Liquidations</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 ml-1 md:ml-2 font-medium"><?= esc($title) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-lg">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <h1 class="text-xl font-semibold text-white flex items-center">
                    <i class="bi bi-file-earmark-<?= $liquidation['file_type'] === 'csv' ? 'text' : ($liquidation['file_type'] === 'pdf' ? 'pdf' : 'excel') ?> mr-2"></i>
                    <?= esc($title) ?>
                </h1>
                <div class="flex items-center mt-2 sm:mt-0 space-x-2">
                    <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" 
                       class="inline-flex items-center px-4 py-2 bg-white text-green-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        <i class="bi bi-download mr-2"></i>Download File
                    </a>
                    <a href="<?= base_url('atm-liquidation/show/' . $liquidation['id']) ?>" 
                       class="inline-flex items-center px-4 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors font-medium">
                        <i class="bi bi-arrow-left mr-2"></i>Back to Details
                    </a>
                </div>
            </div>
        </div>

        <!-- File Information -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Liquidation ID</label>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">#<?= esc($liquidation['id']) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">File Type</label>
                    <p class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="bi bi-file-earmark-<?= $liquidation['file_type'] === 'csv' ? 'text' : ($liquidation['file_type'] === 'pdf' ? 'pdf' : 'excel') ?> mr-1"></i>
                            <?= esc(strtoupper($liquidation['file_type'])) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recipient</label>
                    <p class="mt-1 text-sm text-gray-900">
                        <?= esc($liquidation['recipient_name']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- File Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="bi bi-table text-green-600 mr-2"></i>
                File Content
                <?php if ($isLimited): ?>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        <i class="bi bi-exclamation-triangle mr-1"></i>
                        Limited View
                    </span>
                <?php endif; ?>
            </h2>
            <?php if ($isLimited): ?>
                <p class="mt-2 text-sm text-orange-600">
                    <i class="bi bi-info-circle mr-1"></i>
                    For performance reasons, only the first 1,000 rows are displayed. Download the file to view all records.
                </p>
            <?php endif; ?>
        </div>

        <?php if (!empty($headers) && !empty($csvData)): ?>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 csv-table">
                        <thead class="bg-gradient-to-r from-green-600 to-green-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                <?php foreach ($headers as $header): ?>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                    <?= esc($header) ?>
                                </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($csvData as $index => $row): ?>
                            <tr class="<?= $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?> hover:bg-green-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= $index + 1 ?>
                                </td>
                                <?php foreach ($row as $cell): ?>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 max-w-xs truncate" title="<?= esc($cell) ?>">
                                    <?= esc($cell) ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Footer with Statistics -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div class="text-sm text-gray-700">
                        <strong>Total Columns:</strong> <?= count($headers) ?> |
                        <strong>Rows Displayed:</strong> <?= number_format(count($csvData)) ?>
                        <?php if ($isLimited): ?>
                            of <?= number_format($liquidation['total_records'] ?? count($csvData)) ?> total
                        <?php endif; ?>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <a href="<?= base_url('atm-liquidation/download-file/' . $liquidation['id']) ?>" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="bi bi-download mr-2"></i>Download Complete File
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <i class="bi bi-file-earmark-x text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Data Available</h3>
                <p class="text-gray-500 mb-6">
                    The file appears to be empty or could not be read. Please check the file format and try uploading again.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="<?= base_url('atm-liquidation/show/' . $liquidation['id']) ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>Back to Details
                    </a>
                    <a href="<?= base_url('atm-liquidation') ?>" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="bi bi-list mr-2"></i>View All Batches
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable for CSV content if there's data
    <?php if (!empty($headers) && !empty($csvData)): ?>
    $('.csv-table').DataTable({
        order: [[0, 'asc']], // Order by row number
        pageLength: 25,
        responsive: true,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: 0 } // Disable sorting on row number column
        ],
        dom: '<"flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4"<"mb-2 sm:mb-0"f><"flex items-center space-x-2"l>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search in table...",
            lengthMenu: "Show _MENU_ rows",
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            },
            info: "Showing _START_ to _END_ of _TOTAL_ rows",
            infoEmpty: "No data available",
            infoFiltered: "(filtered from _MAX_ total rows)"
        }
    });

    // Style DataTable elements
    setTimeout(function() {
        // Style search input
        $('.dataTables_filter input').addClass('px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm');
        $('.dataTables_filter label').addClass('text-sm font-medium text-gray-700');
        
        // Style length select
        $('.dataTables_length select').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm');
        $('.dataTables_length label').addClass('text-sm font-medium text-gray-700');
        
        // Style pagination
        $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-green-50 hover:text-green-600 hover:border-green-300 transition-colors');
        $('.dataTables_paginate .paginate_button.current').addClass('bg-green-600 text-white border-green-600').removeClass('text-gray-500 border-gray-300');
        $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
        
        // Style info text
        $('.dataTables_info').addClass('text-sm text-gray-600');
    }, 100);
    <?php endif; ?>
    
    // Add click-to-copy functionality for cells
    $('td').on('click', function() {
        const text = $(this).attr('title') || $(this).text();
        if (text && text.trim() !== '') {
            navigator.clipboard.writeText(text.trim()).then(function() {
                // Show a brief tooltip
                const cell = $(this);
                const originalBg = cell.css('background-color');
                cell.css('background-color', '#d1fae5').animate({
                    backgroundColor: originalBg
                }, 1000);
            }).catch(function() {
                console.log('Copy failed');
            });
        }
    }.bind(this));
});

// Add tooltip for copy functionality
$(document).ready(function() {
    $('td[title]').attr('data-toggle', 'tooltip').attr('data-placement', 'top');
    
    // Show copy hint on hover
    $('td').hover(
        function() {
            $(this).css('cursor', 'pointer');
            if (!$(this).attr('title')) {
                $(this).attr('title', 'Click to copy: ' + $(this).text().trim());
            }
        },
        function() {
            $(this).css('cursor', 'default');
        }
    );
});
</script>

<style>
/* Custom styles for better table appearance */
.csv-table {
    font-size: 0.875rem;
}

.csv-table td {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.csv-table td:hover {
    background-color: #f0fdf4 !important;
    cursor: pointer;
}

/* DataTable responsive styles */
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        text-align: left;
        margin-bottom: 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
    }
}

/* Custom scrollbar for table */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f9fafb;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
<?= $this->endSection() ?>