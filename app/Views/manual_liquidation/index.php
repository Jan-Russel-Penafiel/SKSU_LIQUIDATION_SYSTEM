<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
    <div class="mb-4 lg:mb-0">
        <h1 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
            <i class="bi bi-file-text mr-3 text-green-600"></i>
            Manual Liquidations
        </h1>
        <p class="text-gray-600 mt-1 text-sm">Manage manual scholarship liquidation entries</p>
    </div>
    
    <div class="relative">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center" onclick="openNewEntryModal()">
            <i class="bi bi-plus-circle mr-2"></i>
            New Entry
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 mb-6">
    <div class="p-6">
        <form method="GET" action="<?= base_url('manual-liquidation') ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Campus</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" name="campus">
                    <option value="">All Campuses</option>
                    <option value="Main Campus" <?= (isset($filters['campus']) && $filters['campus'] === 'Main Campus') ? 'selected' : '' ?>>Main Campus</option>
                    <option value="Kalamansig Campus" <?= (isset($filters['campus']) && $filters['campus'] === 'Kalamansig Campus') ? 'selected' : '' ?>>Kalamansig Campus</option>
                    <option value="Palimbang Campus" <?= (isset($filters['campus']) && $filters['campus'] === 'Palimbang Campus') ? 'selected' : '' ?>>Palimbang Campus</option>
                    <option value="Isulan Campus" <?= (isset($filters['campus']) && $filters['campus'] === 'Isulan Campus') ? 'selected' : '' ?>>Isulan Campus</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Officer</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" name="disbursing_officer">
                    <option value="">All Officers</option>
                    <?php if (isset($disbursing_officers)): ?>
                        <?php foreach ($disbursing_officers as $officer): ?>
                        <option value="<?= $officer['id'] ?>" <?= (isset($filters['disbursing_officer']) && $filters['disbursing_officer'] == $officer['id']) ? 'selected' : '' ?>>
                            <?= esc($officer['username']) ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Semester</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" name="semester">
                    <option value="">All Semesters</option>
                    <option value="1st Semester" <?= (isset($filters['semester']) && $filters['semester'] === '1st Semester') ? 'selected' : '' ?>>1st Semester</option>
                    <option value="2nd Semester" <?= (isset($filters['semester']) && $filters['semester'] === '2nd Semester') ? 'selected' : '' ?>>2nd Semester</option>
                    <option value="Summer" <?= (isset($filters['semester']) && $filters['semester'] === 'Summer') ? 'selected' : '' ?>>Summer</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Academic Year</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" name="academic_year">
                    <option value="">All Years</option>
                    <option value="2023-2024" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2023-2024') ? 'selected' : '' ?>>2023-2024</option>
                    <option value="2024-2025" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2024-2025') ? 'selected' : '' ?>>2024-2025</option>
                    <option value="2025-2026" <?= (isset($filters['academic_year']) && $filters['academic_year'] === '2025-2026') ? 'selected' : '' ?>>2025-2026</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" name="status">
                    <option value="">All Status</option>
                    <option value="pending" <?= (isset($filters['status']) && $filters['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="verified" <?= (isset($filters['status']) && $filters['status'] === 'verified') ? 'selected' : '' ?>>Verified</option>
                    <option value="approved" <?= (isset($filters['status']) && $filters['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= (isset($filters['status']) && $filters['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 opacity-0">Actions</label>
                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
                        <i class="bi bi-funnel mr-1"></i>Filter
                    </button>
                    <a href="<?= base_url('manual-liquidation') ?>" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center text-sm">
                        <i class="bi bi-arrow-clockwise mr-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liquidations Table -->
<div class="bg-white rounded-2xl shadow-lg border border-green-200/20 overflow-hidden">
    <div class="bg-gradient-to-r from-green-50 to-white border-b border-green-100 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="bi bi-table mr-2 text-green-600"></i>
                    Manual Liquidation Records
                    <?php if (isset($liquidations) && is_array($liquidations)): ?>
                        <span class="ml-3 bg-green-600 text-white text-xs px-2 py-1 rounded-full"><?= count($liquidations) ?> records</span>
                    <?php endif; ?>
                </h3>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Print Buttons -->
                <div class="flex items-center space-x-2">
                    <button onclick="printByVoucher()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center text-sm">
                        <i class="bi bi-receipt mr-2"></i>Print by Voucher
                    </button>
                    
                    <button onclick="printByCampus()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center text-sm">
                        <i class="bi bi-building mr-2"></i>Print by Campus
                    </button>
                    
                    <button onclick="printCurrentView()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center text-sm">
                        <i class="bi bi-list-ul mr-2"></i>Print Current View
                    </button>
                </div>

                <!-- Bulk Actions -->
                <div id="bulk-actions" class="hidden">
                    <div class="flex items-center space-x-3">
                        <span id="selected-count" class="text-sm text-gray-600">0 selected</span>
                        <select id="bulk-status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">Select Action</option>
                            <option value="pending">Set to Pending</option>
                            <option value="verified">Set to Verified</option>
                            <option value="approved">Set to Approved</option>
                            <option value="rejected">Set to Rejected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <button id="apply-bulk-action" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                            Apply
                        </button>
                        <button id="cancel-selection" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-6">
        <?php if (isset($liquidations) && !empty($liquidations)): ?>
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 text-sm font-semibold text-gray-700 w-12">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">ID</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Recipient</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Voucher No.</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Amount</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Campus</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Date</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Officer</th>
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($liquidations as $liquidation): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4">
                            <input type="checkbox" class="row-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500" 
                                   data-id="<?= esc($liquidation['id']) ?>" 
                                   data-status="<?= esc($liquidation['status']) ?>">
                        </td>
                        <td class="py-4 text-sm text-gray-900"><?= esc($liquidation['id']) ?></td>
                        <td class="py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900"><?= esc($liquidation['first_name'] . ' ' . $liquidation['last_name']) ?></div>
                                <div class="text-xs text-gray-500">ID: <?= esc($liquidation['recipient_code']) ?></div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-900"><?= esc($liquidation['voucher_number']) ?></td>
                        <td class="py-4 text-sm font-medium text-gray-900">₱<?= number_format($liquidation['amount'], 2) ?></td>
                        <td class="py-4 text-sm text-gray-600"><?= esc($liquidation['campus']) ?></td>
                        <td class="py-4">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full status-<?= esc($liquidation['status']) ?>">
                                <?= esc(ucwords(str_replace('_', ' ', $liquidation['status']))) ?>
                            </span>
                        </td>
                        <td class="py-4 text-sm text-gray-600"><?= date('M j, Y', strtotime($liquidation['liquidation_date'])) ?></td>
                        <td class="py-4 text-sm text-gray-600"><?= esc($liquidation['disbursing_officer']) ?></td>
                        <td class="py-4">
                            <div class="flex space-x-2">
                                <a href="<?= base_url('manual-liquidation/show/' . $liquidation['id']) ?>" 
                                   class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200 transition-colors" title="View Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                
                                <a href="<?= base_url('manual-liquidation/edit/' . $liquidation['id']) ?>" 
                                   class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition-colors" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                
                                <?php if ($user['role'] === 'admin' || (isset($liquidation['disbursing_officer_id']) && $liquidation['disbursing_officer_id'] == $user['id'])): ?>
                                <a href="/manual-liquidation/delete/<?= $liquidation['id'] ?>" 
                                   class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition-colors" title="Delete"
                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                    <i class="bi bi-trash text-sm"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-inbox text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No manual liquidations found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Start by creating a new liquidation entry using the button below.</p>
            <a href="<?= base_url('manual-liquidation/create') ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                <i class="bi bi-plus-circle mr-2"></i>Create New Entry
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- New Entry Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="newEntryModal">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="bi bi-plus-circle mr-2 text-green-600"></i>
                Choose Entry Method
            </h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" onclick="closeNewEntryModal()">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <p class="text-gray-600 mb-6 text-sm text-center">Select the type of liquidation entry you want to create:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Standard Entry -->
                <a href="<?= base_url('manual-liquidation/create') ?>" class="group block p-6 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors mb-3">
                            <i class="bi bi-file-plus text-green-600 text-xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-700 mb-2">Standard Entry</h4>
                        <p class="text-xs text-gray-500">Create a single liquidation entry</p>
                    </div>
                </a>
                
                <!-- By Recipient ID -->
                <a href="<?= base_url('manual-liquidation/entry-by-recipient') ?>" class="group block p-6 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors mb-3">
                            <i class="bi bi-person text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-700 mb-2">By Recipient ID</h4>
                        <p class="text-xs text-gray-500">Search for specific recipients</p>
                    </div>
                </a>
                
                <!-- By Campus -->
                <a href="<?= base_url('manual-liquidation/entry-by-campus') ?>" class="group block p-6 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors mb-3">
                            <i class="bi bi-building text-purple-600 text-xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-700 mb-2">By Campus</h4>
                        <p class="text-xs text-gray-500">Bulk entry by campus</p>
                    </div>
                </a>
                
                <!-- By Officer -->
                <a href="<?= base_url('manual-liquidation/entry-by-officer') ?>" class="group block p-6 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-all duration-200 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors mb-3">
                            <i class="bi bi-people text-orange-600 text-xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-700 mb-2">By Officer</h4>
                        <p class="text-xs text-gray-500">Manage by disbursing officer</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Add jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
// Print functionality using jsPDF - Define these first
function getTableData() {
    const tableData = [];
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            tableData.push([
                cells[1]?.textContent.trim() || '', // ID
                cells[2]?.textContent.trim() || '', // Recipient
                cells[3]?.textContent.trim() || '', // Voucher No
                cells[4]?.textContent.trim() || '', // Amount
                cells[5]?.textContent.trim() || '', // Campus
                cells[6]?.textContent.trim() || '', // Date
                cells[7]?.textContent.trim() || ''  // Officer
            ]);
        }
    });
    
    return tableData;
}

function printByVoucher() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
    
    // Add header
    doc.setFontSize(16);
    doc.setFont(undefined, 'bold');
    doc.text('SKSU Liquidation Monitor - Print by Voucher Number', 20, 20);
    
    doc.setFontSize(12);
    doc.setFont(undefined, 'normal');
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 20, 30);
    
    // Get table data
    const tableData = getTableData();
    
    if (tableData.length === 0) {
        doc.text('No data available to print.', 20, 50);
    } else {
        // Add table
        doc.autoTable({
            head: [['ID', 'Recipient', 'Voucher No.', 'Amount', 'Campus', 'Date', 'Officer']],
            body: tableData,
            startY: 40,
            styles: {
                fontSize: 9,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [34, 197, 94], // green color
                textColor: 255,
                fontStyle: 'bold'
            },
            columnStyles: {
                0: { cellWidth: 15 }, // ID
                1: { cellWidth: 45 }, // Recipient
                2: { cellWidth: 35 }, // Voucher
                3: { cellWidth: 25 }, // Amount
                4: { cellWidth: 35 }, // Campus
                5: { cellWidth: 25 }, // Date
                6: { cellWidth: 30 }  // Officer
            }
        });
    }
    
    // Save the PDF
    doc.save('liquidation-by-voucher-' + new Date().toISOString().slice(0, 10) + '.pdf');
}

function printByCampus() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
    
    // Add header
    doc.setFontSize(16);
    doc.setFont(undefined, 'bold');
    doc.text('SKSU Liquidation Monitor - Print by Campus', 20, 20);
    
    doc.setFontSize(12);
    doc.setFont(undefined, 'normal');
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 20, 30);
    
    // Get table data grouped by campus
    const tableData = getTableData();
    
    if (tableData.length === 0) {
        doc.text('No data available to print.', 20, 50);
    } else {
        // Group data by campus
        const campusGroups = {};
        tableData.forEach(row => {
            const campus = row[4]; // Campus column
            if (!campusGroups[campus]) {
                campusGroups[campus] = [];
            }
            campusGroups[campus].push(row);
        });
        
        let currentY = 40;
        
        // Print data for each campus
        Object.keys(campusGroups).forEach((campus, index) => {
            if (index > 0) {
                doc.addPage();
                currentY = 20;
            }
            
            doc.setFontSize(14);
            doc.setFont(undefined, 'bold');
            doc.text(`Campus: ${campus}`, 20, currentY);
            currentY += 10;
            
            doc.autoTable({
                head: [['ID', 'Recipient', 'Voucher No.', 'Amount', 'Date', 'Officer']],
                body: campusGroups[campus].map(row => [row[0], row[1], row[2], row[3], row[5], row[6]]),
                startY: currentY,
                styles: {
                    fontSize: 9,
                    cellPadding: 2
                },
                headStyles: {
                    fillColor: [34, 197, 94],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                columnStyles: {
                    0: { cellWidth: 15 },
                    1: { cellWidth: 50 },
                    2: { cellWidth: 40 },
                    3: { cellWidth: 30 },
                    4: { cellWidth: 30 },
                    5: { cellWidth: 40 }
                }
            });
            
            currentY = doc.lastAutoTable.finalY + 10;
        });
    }
    
    // Save the PDF
    doc.save('liquidation-by-campus-' + new Date().toISOString().slice(0, 10) + '.pdf');
}

function printCurrentView() {
    // Hide elements that shouldn't be printed
    const elementsToHide = [
        '.no-print',
        '#bulk-actions',
        'nav',
        '.pagination',
        '.btn',
        'button'
    ];
    
    elementsToHide.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            el.style.display = 'none';
        });
    });
    
    // Add print styles
    const printStyles = document.createElement('style');
    printStyles.innerHTML = `
        @media print {
            body { font-size: 12px; }
            table { font-size: 10px; }
            .bg-white { background: white !important; }
            .text-white { color: black !important; }
            .rounded-2xl { border-radius: 0 !important; }
            .shadow-lg { box-shadow: none !important; }
        }
    `;
    document.head.appendChild(printStyles);
    
    // Print the page
    window.print();
    
    // Restore hidden elements after printing
    setTimeout(() => {
        elementsToHide.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                el.style.display = '';
            });
        });
        document.head.removeChild(printStyles);
    }, 500);
}

// Bulk operations functionality
$(document).ready(function() {
    let selectedItems = [];
    
    // Select all checkbox
    $('#select-all').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
            selectedItems = [];
            $('.row-checkbox').each(function() {
                selectedItems.push($(this).data('id'));
            });
        } else {
            selectedItems = [];
        }
        updateBulkActions();
    });
    
    // Individual row checkboxes
    $(document).on('change', '.row-checkbox', function() {
        const id = $(this).data('id');
        const isChecked = $(this).prop('checked');
        
        if (isChecked) {
            if (!selectedItems.includes(id)) {
                selectedItems.push(id);
            }
        } else {
            selectedItems = selectedItems.filter(item => item !== id);
            $('#select-all').prop('checked', false);
        }
        
        // Update select all checkbox
        const totalRows = $('.row-checkbox').length;
        const checkedRows = $('.row-checkbox:checked').length;
        $('#select-all').prop('checked', totalRows > 0 && checkedRows === totalRows);
        
        updateBulkActions();
    });
    
    // Update bulk actions visibility
    function updateBulkActions() {
        const count = selectedItems.length;
        $('#selected-count').text(`${count} selected`);
        
        if (count > 0) {
            $('#bulk-actions').removeClass('hidden');
        } else {
            $('#bulk-actions').addClass('hidden');
            $('#bulk-status').val('');
        }
    }
    
    // Apply bulk action
    $('#apply-bulk-action').on('click', function() {
        const action = $('#bulk-status').val();
        
        if (!action) {
            alert('Please select an action to apply.');
            return;
        }
        
        if (selectedItems.length === 0) {
            alert('No items selected.');
            return;
        }
        
        // Handle delete action separately
        if (action === 'delete') {
            const confirmation = confirm(`Are you sure you want to delete ${selectedItems.length} item(s)? This action cannot be undone.`);
            
            if (confirmation) {
                // Handle delete logic here
                alert('Delete functionality not yet implemented.');
            }
            return;
        }
        
        // Handle status update
        const statusLabels = {
            'pending': 'Pending',
            'verified': 'Verified',
            'approved': 'Approved',
            'rejected': 'Rejected'
        };
        
        const confirmation = confirm(`Are you sure you want to update ${selectedItems.length} item(s) to "${statusLabels[action]}" status?`);
        
        if (confirmation) {
            // Show loading
            $('#apply-bulk-action').prop('disabled', true).text('Applying...');
            
            $.ajax({
                url: '<?= base_url('manual-liquidation/bulk-update-status') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    liquidation_ids: selectedItems,
                    status: action
                }),
                success: function(response) {
                    if (response.success) {
                        alert(response.message || 'Status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Unknown error occurred'));
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    alert('An error occurred: ' + (response?.message || 'Unknown error'));
                },
                complete: function() {
                    $('#apply-bulk-action').prop('disabled', false).text('Apply');
                }
            });
        }
    });
    
    // Cancel selection
    $('#cancel-selection').on('click', function() {
        $('.row-checkbox, #select-all').prop('checked', false);
        selectedItems = [];
        updateBulkActions();
    });
});

// Modal functions
function openNewEntryModal() {
    const modal = document.getElementById('newEntryModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    } else {
        console.error('Modal element not found');
    }
}

function closeNewEntryModal() {
    const modal = document.getElementById('newEntryModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('newEntryModal');
    if (modal && event.target === modal) {
        closeNewEntryModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeNewEntryModal();
    }
});

// Initialize DataTable
$(document).ready(function() {
    if ($.fn.DataTable && $('.data-table').length) {
        $('.data-table').DataTable({
            order: [[1, 'desc']], // Sort by ID column (index 1, not 0 which is checkbox)
            pageLength: 25,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, -1] } // Disable sorting on checkbox and Actions columns
            ]
        });
    }
});
</script>
<?= $this->endSection() ?>