<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Chairman Dashboard</h1>
        <p class="text-gray-600">Review and approve liquidations</p>
    </div>
    <div class="flex items-center space-x-4">
        <div class="text-sm text-gray-500">
            <i class="bi bi-calendar mr-1"></i>
            <?= date('F j, Y') ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- ATM Liquidations Pending -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">ATM Pending</p>
                <p class="text-3xl font-bold text-orange-600" id="atm-pending">0</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <i class="bi bi-clock text-orange-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('chairman/pending-atm') ?>" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                Review pending →
            </a>
        </div>
    </div>

    <!-- ATM Liquidations Approved -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">ATM Approved</p>
                <p class="text-3xl font-bold text-green-600" id="atm-approved">0</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <i class="bi bi-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('chairman/approved-atm') ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                View approved →
            </a>
        </div>
    </div>

    <!-- Manual Liquidations Pending -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Manual Pending</p>
                <p class="text-3xl font-bold text-blue-600" id="manual-pending">0</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="bi bi-file-text text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('chairman/pending-manual') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Review pending →
            </a>
        </div>
    </div>

    <!-- Manual Liquidations Approved -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Manual Approved</p>
                <p class="text-3xl font-bold text-emerald-600" id="manual-approved">0</p>
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('chairman/approved-manual') ?>" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                View approved →
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent ATM Liquidations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Recent ATM Liquidations</h3>
            <p class="text-orange-100 text-sm">Pending your approval</p>
        </div>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="switchAtmTab('batch')" id="tab-batch" class="atm-tab flex-1 py-3 px-4 text-center border-b-2 border-orange-500 text-orange-600 font-medium text-sm hover:text-orange-700 hover:border-orange-600 transition-colors">
                    <i class="bi bi-collection mr-1"></i>
                    Per Batch
                </button>
                <button onclick="switchAtmTab('recipient')" id="tab-recipient" class="atm-tab flex-1 py-3 px-4 text-center border-b-2 border-transparent text-gray-500 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors">
                    <i class="bi bi-person mr-1"></i>
                    Per Recipient
                </button>
            </nav>
        </div>
        
        <div class="p-6">
            <!-- Per Batch Tab Content -->
            <div id="atm-batch-content" class="atm-tab-content">
                <div id="recent-atm-batch-list" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
            
            <!-- Per Recipient Tab Content -->
            <div id="atm-recipient-content" class="atm-tab-content hidden">
                <div id="recent-atm-recipient-list" class="space-y-4">
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="<?= base_url('chairman/pending-atm') ?>" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors">
                    <i class="bi bi-eye mr-2"></i>
                    View All ATM Liquidations
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Manual Liquidations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Recent Manual Liquidations</h3>
            <p class="text-blue-100 text-sm">Pending your approval</p>
        </div>
        <div class="p-6">
            <div id="recent-manual-list" class="space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                    <p>Loading...</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="<?= base_url('chairman/pending-manual') ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                    <i class="bi bi-eye mr-2"></i>
                    View All Manual Liquidations
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    loadDashboardData();
});

function switchAtmTab(tabName) {
    // Update tab buttons
    $('.atm-tab').removeClass('border-orange-500 text-orange-600').addClass('border-transparent text-gray-500');
    $('#tab-' + tabName).removeClass('border-transparent text-gray-500').addClass('border-orange-500 text-orange-600');
    
    // Update tab content
    $('.atm-tab-content').addClass('hidden');
    $('#atm-' + tabName + '-content').removeClass('hidden');
}

function loadDashboardData() {
    $.ajax({
        url: '<?= base_url('chairman/dashboard-data') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                // Update statistics
                $('#atm-pending').text(data.atmStats.pending);
                $('#atm-approved').text(data.atmStats.approved);
                $('#manual-pending').text(data.manualStats.pending);
                $('#manual-approved').text(data.manualStats.approved);
                
                // Update recent ATM liquidations (batch and per recipient)
                updateRecentAtmBatchList(data.recentAtmBatch);
                updateRecentAtmPerRecipientList(data.recentAtmPerRecipient);
                
                // Update recent manual liquidations
                updateRecentManualList(data.recentManual);
            }
        },
        error: function() {
            console.log('Error loading dashboard data');
        }
    });
}

function updateRecentAtmBatchList(data) {
    const container = $('#recent-atm-batch-list');
    
    if (data.length === 0) {
        container.html(`
            <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p>No pending batch ATM liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        const batchName = item.batch_name || 'Unnamed Batch';
        const uploaderName = item.uploader_name || 'Unknown';
        const createdAt = item.created_at || '';
        const totalRecords = item.total_records || item.recipient_count || 0;
        
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${batchName}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-person mr-1"></i>${uploaderName}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(createdAt)}</span>
                        <span><i class="bi bi-files mr-1"></i>${totalRecords} records</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-sent-to-chairman">Pending Review</span>
                    <a href="<?= base_url('chairman/atm/batch/') ?>${item.id}" class="text-orange-600 hover:text-orange-700">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function updateRecentAtmPerRecipientList(data) {
    const container = $('#recent-atm-recipient-list');
    
    if (data.length === 0) {
        container.html(`
            <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p>No pending per-recipient ATM liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        const recipientName = item.recipient_name || 'Unknown Recipient';
        const recipientCode = item.recipient_code || 'N/A';
        const campus = item.campus || 'Unknown Campus';
        const amount = item.amount || 0;
        const createdAt = item.created_at || '';
        
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${recipientName}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-person-badge mr-1"></i>${recipientCode}</span>
                        <span><i class="bi bi-building mr-1"></i>${campus}</span>
                        <span><i class="bi bi-cash mr-1"></i>₱${parseFloat(amount).toLocaleString()}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(createdAt)}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-sent-to-chairman">Pending Review</span>
                    <a href="<?= base_url('chairman/atm/') ?>${item.id}" class="text-orange-600 hover:text-orange-700">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function updateRecentManualList(data) {
    const container = $('#recent-manual-list');
    
    if (data.length === 0) {
        container.html(`
            <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p>No pending manual liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        const firstName = item.first_name || '';
        const lastName = item.last_name || '';
        const campus = item.campus || 'Unknown Campus';
        const amount = item.amount || 0;
        const createdAt = item.created_at || '';
        
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${firstName} ${lastName}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-building mr-1"></i>${campus}</span>
                        <span><i class="bi bi-cash mr-1"></i>₱${parseFloat(amount).toLocaleString()}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(createdAt)}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-verified">For Approval</span>
                    <a href="<?= base_url('chairman/manual/') ?>${item.id}" class="text-blue-600 hover:text-blue-700">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}
</script>
<?= $this->endSection() ?>