<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Accounting Dashboard</h1>
        <p class="text-gray-600">Monitor approved liquidations and receipts</p>
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
            <a href="<?= base_url('accounting/atm-approved') ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                View approved →
            </a>
        </div>
    </div>

    <!-- ATM Liquidations Received -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">ATM Received</p>
                <p class="text-3xl font-bold text-blue-600" id="atm-received">0</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <i class="bi bi-inbox text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('accounting/atm-received') ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View received →
            </a>
        </div>
    </div>

    <!-- ATM Liquidations Completed -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">ATM Completed</p>
                <p class="text-3xl font-bold text-emerald-600" id="atm-completed">0</p>
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-emerald-600 text-sm font-medium">Total processed</span>
        </div>
    </div>

    <!-- Manual Liquidations Approved -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Manual Approved</p>
                <p class="text-3xl font-bold text-purple-600" id="manual-approved">0</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <i class="bi bi-file-text text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= base_url('accounting/manual-approved') ?>" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                View approved →
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Approved ATM Liquidations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Recent Approved ATM</h3>
            <p class="text-green-100 text-sm">Ready for accounting processing</p>
        </div>
        <div class="p-6">
            <div id="recent-approved-list" class="space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                    <p>Loading...</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="<?= base_url('accounting/atm-approved') ?>" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                    <i class="bi bi-eye mr-2"></i>
                    View All Approved
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Received ATM Liquidations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Recent Received ATM</h3>
            <p class="text-blue-100 text-sm">In accounting processing</p>
        </div>
        <div class="p-6">
            <div id="recent-received-list" class="space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                    <p>Loading...</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="<?= base_url('accounting/atm-received') ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                    <i class="bi bi-eye mr-2"></i>
                    View All Received
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Manual Liquidations Section -->
<div class="mt-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Recent Approved Manual Liquidations</h3>
            <p class="text-purple-100 text-sm">Individual entries for accounting records</p>
        </div>
        <div class="p-6">
            <div id="recent-manual-list" class="space-y-4">
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-hourglass-split text-3xl mb-2"></i>
                    <p>Loading...</p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="<?= base_url('accounting/manual-approved') ?>" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition-colors">
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

function loadDashboardData() {
    $.ajax({
        url: '<?= base_url('accounting/dashboard-data') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                // Update statistics
                $('#atm-approved').text(data.atmStats.approved);
                $('#atm-received').text(data.atmStats.received);
                $('#atm-completed').text(data.atmStats.completed);
                $('#manual-approved').text(data.manualStats.approved);
                
                // Update recent lists
                updateRecentApprovedList(data.recentApproved);
                updateRecentReceivedList(data.recentReceived);
                updateRecentManualList(data.recentManual);
            }
        },
        error: function() {
            console.log('Error loading dashboard data');
        }
    });
}

function updateRecentApprovedList(data) {
    const container = $('#recent-approved-list');
    
    if (data.length === 0) {
        container.html(`
            <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p>No approved ATM liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.batch_name}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-person mr-1"></i>${item.uploader_name}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(item.chairman_approval_date)}</span>
                        <span><i class="bi bi-files mr-1"></i>${item.total_records} records</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-approved">Approved</span>
                    <button onclick="receiveAtm(${item.id}, '${item.batch_name}')" class="text-blue-600 hover:text-blue-700 px-2 py-1 rounded" title="Receive for Processing">
                        <i class="bi bi-inbox"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function updateRecentReceivedList(data) {
    const container = $('#recent-received-list');
    
    if (data.length === 0) {
        container.html(`
            <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-3xl mb-2"></i>
                <p>No received ATM liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.batch_name}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-person mr-1"></i>${item.uploader_name}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(item.accounting_received_date)}</span>
                        <span><i class="bi bi-files mr-1"></i>${item.total_records} records</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-sent-to-accounting">In Processing</span>
                    <a href="<?= base_url('accounting/atm/') ?>${item.id}" class="text-blue-600 hover:text-blue-700">
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
                <p>No approved manual liquidations</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    data.forEach(function(item) {
        html += `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.first_name} ${item.last_name}</h4>
                    <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                        <span><i class="bi bi-building mr-1"></i>${item.campus}</span>
                        <span><i class="bi bi-cash mr-1"></i>PHP ${parseFloat(item.amount).toLocaleString()}</span>
                        <span><i class="bi bi-calendar mr-1"></i>${formatDate(item.updated_at)}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="status-approved">Approved</span>
                    <a href="<?= base_url('accounting/manual/') ?>${item.id}" class="text-purple-600 hover:text-purple-700">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        `;
    });
    
    container.html(html);
}

function receiveAtm(id, batchName) {
    if (confirm(`Receive batch "${batchName}" for accounting processing?`)) {
        $.ajax({
            url: '<?= base_url('accounting/receive-atm') ?>',
            method: 'POST',
            data: {
                id: id,
                remarks: 'Received for accounting processing'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadDashboardData();
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while receiving the liquidation.');
            }
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const iconClass = type === 'success' ? 'bi-check-circle-fill text-green-600' : 'bi-exclamation-triangle-fill text-red-600';
    
    const alertHtml = `
        <div class="${alertClass} border rounded-xl px-4 py-3 mb-6 flex items-center">
            <i class="bi ${iconClass} mr-3 text-lg"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    const container = document.querySelector('.flex.items-center.justify-between.mb-8').nextElementSibling;
    container.insertAdjacentHTML('beforebegin', alertHtml);
    
    setTimeout(() => {
        document.querySelector('.bg-green-50, .bg-red-50')?.remove();
    }, 5000);
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}
</script>
<?= $this->endSection() ?>