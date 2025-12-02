<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>SKSU Scholarship Liquidation Monitoring System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons (Local) -->
    <link rel="stylesheet" href="<?= base_url('assets/fonts/bootstrap-icons/bootstrap-icons.css') ?>">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    
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
    
    <style>
        .status-pending { 
            background-color: #eab308; 
            color: #713f12; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-verified { 
            background-color: #3b82f6; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-approved { 
            background-color: #22c55e; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-rejected { 
            background-color: #ef4444; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-uploaded { 
            background-color: #a855f7; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-processing { 
            background-color: #f97316; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-processed { 
            background-color: #10b981; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-sent-to-chairman { 
            background-color: #22d3ee; 
            color: #155e75; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-sent-to-accounting { 
            background-color: #16a34a; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-completed { 
            background-color: #15803d; 
            color: white; 
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem;
            margin: 0 0.25rem;
            font-size: 0.875rem !important;
            color: #374151;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            cursor: pointer;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f0fdf4;
            color: #15803d;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #16a34a;
            color: white;
            border-color: #16a34a;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem !important;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #22c55e40;
            border-color: #22c55e;
        }
        
        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem !important;
        }
        
        .dataTables_wrapper .dataTables_length select:focus {
            outline: none;
            box-shadow: 0 0 0 2px #22c55e40;
            border-color: #22c55e;
        }
        
        /* Global font sizing for consistency */
        input, select, textarea, button {
            font-size: 0.875rem; /* 14px */
        }
        
        .btn, .form-control, .form-select {
            font-size: 0.875rem; /* 14px */
        }
        
        table th, table td {
            font-size: 0.875rem !important; /* 14px */
        }
        
        /* DataTables specific styling */
        .dataTables_wrapper {
            font-size: 0.875rem !important;
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.875rem !important;
        }
        
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-size: 0.875rem !important;
        }
        
        .dataTables_wrapper table.dataTable thead th,
        .dataTables_wrapper table.dataTable thead td,
        .dataTables_wrapper table.dataTable tbody th,
        .dataTables_wrapper table.dataTable tbody td,
        .dataTables_wrapper table.dataTable tfoot th,
        .dataTables_wrapper table.dataTable tfoot td {
            font-size: 0.875rem !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-sm">
    <?php if (isset($user) && $user): ?>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-green-600 to-green-700 shadow-lg">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center text-white font-bold text-base hover:text-green-100 transition-colors">
                        <img src="<?= base_url('sksu1.png') ?>" alt="SKSU Logo" class="w-8 h-8 mr-2 rounded-full">
                        SKSU Liquidation Monitor
                    </a>
                </div>
                
                <div class="relative">
                    <div class="dropdown-container">
                        <button class="flex items-center text-white hover:text-green-100 focus:outline-none focus:text-green-100 transition-colors" onclick="toggleDropdown()">
                            <i class="bi bi-person-circle mr-2 text-base"></i>
                            <span class="text-sm font-medium"><?= esc($user['username']) ?></span>
                            <i class="bi bi-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <div class="text-sm font-semibold text-gray-900"><?= esc(ucfirst(str_replace('_', ' ', $user['role']))) ?></div>
                                <?php if ($user['campus']): ?>
                                <div class="text-xs text-gray-500"><?= esc($user['campus']) ?></div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="bi bi-box-arrow-right mr-2"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="flex h-screen pt-16">
        <!-- Sidebar -->
        <aside class="fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gradient-to-b from-green-700 to-green-800 shadow-xl overflow-y-auto">
            <div class="p-2">
                <nav class="space-y-1">
                    <?php if ($user['role'] === 'disbursing_officer'): ?>
                    <!-- Disbursing Officer - Only show Disbursement List -->
                    <a href="<?= base_url('disbursement') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'disbursement') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-cash-stack mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Disbursement List</span>
                    </a>
                    
                    <?php elseif ($user['role'] !== 'scholarship_chairman' && $user['role'] !== 'accounting_officer' && $user['role'] !== 'scholarship_coordinator'): ?>
                    <!-- Other roles (admin, administrator, etc.) - Show full menu -->
                    <a href="<?= base_url('dashboard') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= uri_string() == 'dashboard' ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-speedometer2 mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Manual System</h6>
                    </div>
                    
                    <a href="<?= base_url('manual-liquidation') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'manual-liquidation') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-file-text mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Manual Liquidations</span>
                    </a>
                    
                    <a href="<?= base_url('manual-liquidation/entry-by-recipient') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-person mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Entry by Recipient</span>
                    </a>
                    
                    <a href="<?= base_url('manual-liquidation/entry-by-campus') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-building mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Entry by Campus</span>
                    </a>
                    
                    <a href="<?= base_url('manual-liquidation/entry-by-officer') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-people mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Entry by Officer</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">ATM System</h6>
                    </div>
                    
                    <a href="<?= base_url('atm-liquidation') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'atm-liquidation') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-credit-card mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">ATM Liquidations</span>
                    </a>
                    
                    <a href="<?= base_url('atm-liquidation/create') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-person mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Per Recipient</span>
                    </a>
                    
                    <a href="<?= base_url('atm-liquidation/batch-upload') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-file-earmark-spreadsheet mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Batch Upload</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Reports & Management</h6>
                    </div>
                    
                    <a href="<?= base_url('disbursement') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'disbursement') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-cash-stack mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Disbursement List</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($user['role'] === 'admin'): ?>
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Administration</h6>
                    </div>
                    
                    <a href="<?= base_url('accounts') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'accounts') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-people mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Account Management</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($user['role'] === 'scholarship_chairman'): ?>
                    <a href="<?= base_url('chairman') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'chairman') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-speedometer2 mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Chairman Panel</h6>
                    </div>
                    
                    <a href="<?= base_url('chairman/pending-atm') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-credit-card mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">ATM Approvals</span>
                    </a>
                    
                    <a href="<?= base_url('chairman/pending-manual') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-file-text mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Manual Approvals</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($user['role'] === 'accounting_officer'): ?>
                    <a href="<?= base_url('accounting') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'accounting') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-speedometer2 mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Accounting Panel</h6>
                    </div>
                    
                    <a href="<?= base_url('accounting/atm-approved') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-check-circle mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Approved ATM</span>
                    </a>
                    
                    <a href="<?= base_url('accounting/atm-received') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-inbox mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Received ATM</span>
                    </a>
                    
                    <a href="<?= base_url('accounting/manual-approved') ?>" 
                       class="flex items-center px-3 py-1.5 ml-3 text-green-100 rounded-lg hover:bg-white/10 transition-all duration-200">
                        <i class="bi bi-file-earmark-check mr-2 text-green-300 text-sm"></i>
                        <span class="text-sm">Manual Approved</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($user['role'] === 'scholarship_coordinator'): ?>
                    <a href="<?= base_url('scholarship-coordinator') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= uri_string() == 'scholarship-coordinator' ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-speedometer2 mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    
                    <div class="pt-2">
                        <h6 class="px-3 py-1 text-xs font-semibold text-green-200 uppercase tracking-wider">Coordinator Panel</h6>
                    </div>
                    
                    <a href="<?= base_url('scholarship-coordinator/my-liquidations') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'scholarship-coordinator/my-liquidations') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-list-task mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">My Liquidations</span>
                    </a>
                    
                    <a href="<?= base_url('scholarship-coordinator/campus-overview') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'scholarship-coordinator/campus-overview') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-building mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Campus Overview</span>
                    </a>
                    
                    <a href="<?= base_url('scholarship-coordinator/manage-liquidations') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'scholarship-coordinator/manage-liquidations') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-gear mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Manage Liquidations</span>
                    </a>
                    
                    <a href="<?= base_url('scholarship-coordinator/reports') ?>" 
                       class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-white/10 transition-all duration-200 <?= strpos(uri_string(), 'scholarship-coordinator/reports') !== false ? 'bg-white/20 shadow-md' : '' ?>">
                        <i class="bi bi-file-earmark-bar-graph mr-2 text-green-200 text-sm"></i>
                        <span class="font-medium text-sm">Reports</span>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 bg-gradient-to-br from-green-50/30 to-white overflow-y-auto">
            <div class="p-6">
                
                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-6 flex items-center">
                    <i class="bi bi-check-circle-fill text-green-600 mr-3 text-lg"></i>
                    <div class="flex-1">
                        <span class="text-green-800 font-medium"><?= session()->getFlashdata('success') ?></span>
                    </div>
                    <button type="button" class="text-green-600 hover:text-green-800 transition-colors" onclick="this.parentElement.style.display='none'">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6 flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3 text-lg"></i>
                    <div class="flex-1">
                        <span class="text-red-800 font-medium"><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" class="text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.style.display='none'">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle-fill text-red-600 mr-3 text-lg mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-red-800 font-semibold mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside space-y-1 text-red-700">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.parentElement.style.display='none'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Page Content -->
                <?= $this->renderSection('content') ?>
                
            </div>
        </main>
    </div>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
    <!-- Custom Scripts -->
    <?= $this->renderSection('scripts') ?>
    
    <script>
        // Dropdown toggle function
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const dropdownContainer = document.querySelector('.dropdown-container');
            
            if (!dropdownContainer.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
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
        
        // Initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable && $('.data-table').length) {
                if (!$.fn.DataTable.isDataTable('.data-table')) {
                    $('.data-table').DataTable({
                        responsive: true,
                        pageLength: 25,
                        order: [[0, 'desc']],
                        language: {
                            search: "Search records:",
                            lengthMenu: "Show _MENU_ records per page",
                            info: "Showing _START_ to _END_ of _TOTAL_ records",
                            paginate: {
                                first: "First",
                                last: "Last",
                                next: "Next",
                                previous: "Previous"
                            }
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>