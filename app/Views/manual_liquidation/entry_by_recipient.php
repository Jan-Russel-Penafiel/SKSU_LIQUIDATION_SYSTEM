<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-green-200/20 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <h1 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-person-check text-green-100 mr-3 text-base"></i>
                        Entry by Recipient
                    </h1>
                    <nav class="text-green-100" aria-label="breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li><a href="<?= base_url('dashboard') ?>" class="hover:text-white transition-colors">Dashboard</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li><a href="<?= base_url('manual-liquidation') ?>" class="hover:text-white transition-colors">Manual Liquidation</a></li>
                            <li><i class="bi bi-chevron-right text-xs"></i></li>
                            <li class="text-green-200">Entry by Recipient</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-green-200/20 overflow-hidden">
            <div class="p-6">
                <!-- Recipient Selection -->
                <div class="mb-6">
                    <div class="max-w-md">
                        <label for="recipient_search" class="block text-sm font-medium text-gray-700 mb-2">
                            Search Recipient <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <input type="text" 
                                   id="recipient_search" 
                                   class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Enter recipient ID, name, or email">
                            <button type="button" 
                                    id="search_btn"
                                    class="border border-l-0 border-gray-300 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-r-md transition-colors duration-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recipients Table -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200/20 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="recipients-table">
                            <thead class="bg-gradient-to-r from-green-50 to-green-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Recipient ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Campus</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Year Level</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Scholarship Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recipients as $recipient): ?>
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($recipient['recipient_id']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($recipient['first_name'] . ' ' . $recipient['last_name']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($recipient['campus']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($recipient['course']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($recipient['year_level']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($recipient['scholarship_type']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all create-liquidation" 
                                                data-recipient="<?= esc(json_encode($recipient)) ?>">
                                            <i class="bi bi-plus-circle mr-1"></i>Create
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Liquidation Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50" id="createLiquidationModal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Create Manual Liquidation Entry</h3>
            <button type="button" 
                    class="text-white hover:text-green-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 rounded-lg p-1 transition-colors" 
                    onclick="closeModal()">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form action="<?= base_url('manual-liquidation/store') ?>" method="POST" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="recipient_id" id="modal_recipient_id">
                
                <!-- Recipient Info Display -->
                <div id="recipient-info" class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="voucher_number" class="block text-sm font-medium text-gray-700">
                            Voucher Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="voucher_number" 
                               name="voucher_number" required>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please provide a voucher number.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="amount" class="block text-sm font-medium text-gray-700">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" 
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.01" 
                                   min="0" required>
                        </div>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter a valid amount.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="liquidation_date" class="block text-sm font-medium text-gray-700">
                            Liquidation Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="liquidation_date" 
                               name="liquidation_date" required>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select the liquidation date.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="semester" class="block text-sm font-medium text-gray-700">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                id="semester" 
                                name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please select a semester.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="academic_year" class="block text-sm font-medium text-gray-700">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="academic_year" 
                               name="academic_year" 
                               placeholder="e.g., 2024-2025" 
                               pattern="[0-9]{4}-[0-9]{4}" required>
                        <p class="text-red-500 text-sm mt-1 hidden invalid-feedback">Please enter academic year in format: YYYY-YYYY</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label for="campus" class="block text-sm font-medium text-gray-700">
                            Campus <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 focus:ring-green-500 focus:border-green-500" 
                               id="campus" 
                               name="campus" readonly>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea class="block w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Enter liquidation description or remarks"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <button type="button" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                            onclick="closeModal()">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg transform hover:scale-105 transition-all">
                        Create Liquidation Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize recipients table
    $('#recipients-table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ]
    });

    // Search functionality
    $('#search_btn, #recipient_search').on('click keyup', function(e) {
        if (e.type === 'click' || e.keyCode === 13) {
            const searchTerm = $('#recipient_search').val();
            $('#recipients-table').DataTable().search(searchTerm).draw();
        }
    });

    // Create liquidation modal
    $('.create-liquidation').on('click', function() {
        const recipient = JSON.parse($(this).attr('data-recipient'));
        
        // Set recipient data
        $('#modal_recipient_id').val(recipient.id);
        $('#campus').val(recipient.campus);
        
        // Display recipient info
        const infoHtml = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Recipient ID:</strong> ${recipient.recipient_id}<br>
                    <strong>Name:</strong> ${recipient.first_name} ${recipient.last_name}<br>
                    <strong>Email:</strong> ${recipient.email}
                </div>
                <div class="col-md-6">
                    <strong>Campus:</strong> ${recipient.campus}<br>
                    <strong>Course:</strong> ${recipient.course}<br>
                    <strong>Scholarship:</strong> ${recipient.scholarship_type}
                </div>
            </div>
        `;
        
        $('#recipient-info').html(infoHtml);
        
        // Set default values
        $('#liquidation_date').val(new Date().toISOString().split('T')[0]);
        const currentYear = new Date().getFullYear();
        $('#academic_year').val(`${currentYear}-${currentYear + 1}`);
        
        showModal();
    });

    // Form validation
    $('.needs-validation').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
});

// Modal control functions
function showModal() {
    document.getElementById('createLiquidationModal').classList.remove('hidden');
    document.getElementById('createLiquidationModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('createLiquidationModal').classList.remove('flex');
    document.getElementById('createLiquidationModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('createLiquidationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
<?= $this->endSection() ?>