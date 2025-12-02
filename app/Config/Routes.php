<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');

// Authentication routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::createAccount');

// Dashboard routes
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('/dashboard/chart-data', 'Dashboard::getChartData');

// API routes for AJAX calls
$routes->group('api', function($routes) {
    $routes->get('recipients/search', 'ApiController::searchRecipients');
    $routes->get('recipients/(:num)', 'ApiController::getRecipient/$1');
    $routes->get('recipients', 'ApiController::getAllRecipients');
    
    // Manual liquidation API routes
    $routes->get('manual-liquidation/by-recipient', 'ManualLiquidationController::getLiquidationsByRecipientAPI');
    $routes->get('manual-liquidation/by-campus', 'ManualLiquidationController::getLiquidationsByCampusAPI');
    $routes->get('manual-liquidation/by-officer', 'ManualLiquidationController::getLiquidationsByOfficerAPI');
    $routes->get('manual-liquidation/by-coordinator', 'ManualLiquidationController::getLiquidationsByCoordinatorAPI');
});

// Manual liquidation routes
$routes->group('manual-liquidation', function($routes) {
    $routes->get('/', 'ManualLiquidationController::index');
    $routes->get('create', 'ManualLiquidationController::create');
    $routes->post('store', 'ManualLiquidationController::store');
    $routes->post('bulk-store', 'ManualLiquidationController::bulkStore');
    $routes->post('bulk-update-status', 'ManualLiquidationController::bulkUpdateStatus');
    $routes->get('show/(:num)', 'ManualLiquidationController::show/$1');
    $routes->get('edit/(:num)', 'ManualLiquidationController::edit/$1');
    $routes->post('update/(:num)', 'ManualLiquidationController::update/$1');
    $routes->get('delete/(:num)', 'ManualLiquidationController::delete/$1');
    
    // Entry methods
    $routes->get('entry-by-recipient', 'ManualLiquidationController::entryByRecipient');
    $routes->get('entry-by-campus', 'ManualLiquidationController::entryByCampus');
    $routes->get('entry-by-officer', 'ManualLiquidationController::entryByOfficer');
    $routes->get('entry-by-coordinator', 'ManualLiquidationController::entryByCoordinator');
    
    // Print methods
    $routes->get('print-by-voucher', 'ManualLiquidationController::printByVoucher');
    $routes->get('print-by-campus', 'ManualLiquidationController::printByCampus');
    
    // Ajax routes
    $routes->post('search-recipients', 'ManualLiquidationController::searchRecipients');
    $routes->post('update-status', 'ManualLiquidationController::updateLiquidationStatus');
});

// ATM liquidation routes
$routes->group('atm-liquidation', function($routes) {
    $routes->get('/', 'AtmLiquidationController::index');
    $routes->get('create', 'AtmLiquidationController::create');
    $routes->post('store', 'AtmLiquidationController::store');
    $routes->get('batch-upload', 'AtmLiquidationController::batchUploadForm');
    $routes->post('process-batch', 'AtmLiquidationController::processBatch');
    $routes->post('upload', 'AtmLiquidationController::upload');
    $routes->get('show/(:num)', 'AtmLiquidationController::show/$1');
    $routes->post('approve/(:num)', 'AtmLiquidationController::approve/$1');
    $routes->post('reject/(:num)', 'AtmLiquidationController::reject/$1');
    
    // File viewing and download routes
    $routes->get('view-file/(:num)', 'AtmLiquidationController::viewFile/$1');
    $routes->get('download-file/(:num)', 'AtmLiquidationController::downloadFile/$1');
    
    // Batch viewing and download routes
    $routes->get('view-batch/(:num)', 'AtmLiquidationController::viewBatch/$1');
    $routes->get('download-batch-file/(:num)', 'AtmLiquidationController::downloadBatchFile/$1');
    
    // CSV viewing routes
    $routes->get('view-csv/(:num)', 'AtmLiquidationController::viewCsv/$1');
    $routes->get('download-csv/(:num)', 'AtmLiquidationController::downloadCsv/$1');
});

// Chairman routes
$routes->group('chairman', function($routes) {
    $routes->get('/', 'ChairmanController::index');
    $routes->get('pending-atm', 'ChairmanController::pendingAtmLiquidations');
    $routes->get('approved-atm', 'ChairmanController::approvedAtmLiquidations');
    $routes->get('pending-manual', 'ChairmanController::pendingManualLiquidations');
    $routes->get('approved-manual', 'ChairmanController::approvedManualLiquidations');
    $routes->get('atm/(:num)', 'ChairmanController::viewAtmLiquidation/$1');
    $routes->get('atm/batch/(:num)', 'ChairmanController::viewAtmBatch/$1');
    $routes->get('atm-batch/(:num)', 'ChairmanController::viewAtmBatch/$1');
    $routes->get('manual/(:num)', 'ChairmanController::viewManualLiquidation/$1');
    $routes->post('approve-atm', 'ChairmanController::approveAtmLiquidation');
    $routes->post('reject-atm', 'ChairmanController::rejectAtmLiquidation');
    $routes->post('approve-atm-batch', 'ChairmanController::approveAtmBatch');
    $routes->post('reject-atm-batch', 'ChairmanController::rejectAtmBatch');
    $routes->post('approve-manual', 'ChairmanController::approveManualLiquidation');
    $routes->post('reject-manual', 'ChairmanController::rejectManualLiquidation');
    $routes->get('dashboard-data', 'ChairmanController::getDashboardData');
});

// Disbursement routes
$routes->group('disbursement', function($routes) {
    $routes->get('/', 'DisbursementController::index');
    $routes->get('create', 'DisbursementController::create');
    $routes->post('store', 'DisbursementController::store');
    $routes->get('show/(:num)', 'DisbursementController::show/$1');
    $routes->post('verify/(:num)', 'DisbursementController::verify/$1');
    $routes->post('approve/(:num)', 'DisbursementController::approve/$1');
    $routes->get('by-officer', 'DisbursementController::byOfficer');
    $routes->get('by-campus', 'DisbursementController::byCampus');
    $routes->get('officer/(:num)', 'DisbursementController::viewOfficerDisbursements/$1');
    $routes->get('report', 'DisbursementController::generateReport');
    $routes->post('export', 'DisbursementController::exportData');
});

// Account Management routes
$routes->group('accounts', function($routes) {
    $routes->get('/', 'AccountManagementController::index');
    $routes->get('create', 'AccountManagementController::create');
    $routes->post('store', 'AccountManagementController::store');
    $routes->get('edit/(:num)', 'AccountManagementController::edit/$1');
    $routes->post('update/(:num)', 'AccountManagementController::update/$1');
    $routes->post('toggle-status/(:num)', 'AccountManagementController::toggleStatus/$1');
    $routes->get('profile/(:num)', 'AccountManagementController::viewProfile/$1');
    $routes->post('reset-password/(:num)', 'AccountManagementController::resetPassword/$1');
});

// Accounting routes
$routes->group('accounting', function($routes) {
    $routes->get('/', 'AccountingController::index');
    $routes->get('atm-approved', 'AccountingController::approvedAtmLiquidations');
    $routes->get('atm-liquidations', 'AccountingController::approvedAtmLiquidations');
    $routes->get('atm-received', 'AccountingController::receivedAtmLiquidations');
    $routes->get('manual-approved', 'AccountingController::approvedManualLiquidations');
    $routes->get('atm/(:num)', 'AccountingController::viewAtmLiquidation/$1');
    $routes->get('atm-batch/(:num)', 'AccountingController::viewAtmBatch/$1');
    $routes->get('manual/(:num)', 'AccountingController::viewManualLiquidation/$1');
    $routes->post('receive-atm', 'AccountingController::receiveAtmLiquidation');
    $routes->post('receive-atm-batch', 'AccountingController::receiveAtmBatch');
    $routes->post('complete-atm', 'AccountingController::completeAtmLiquidation');
    $routes->post('complete-atm-batch', 'AccountingController::completeAtmBatch');
    $routes->get('dashboard-data', 'AccountingController::getDashboardData');
});

// Officer Management routes
$routes->group('officer', function($routes) {
    $routes->get('/', 'OfficerController::index');
    $routes->get('pending-items', 'OfficerController::pendingItems');
    $routes->post('search-items', 'OfficerController::searchItems');
    $routes->post('process-item', 'OfficerController::processItem');
});

// Scholarship Coordinator routes
$routes->group('scholarship-coordinator', function($routes) {
    $routes->get('/', 'ScholarshipCoordinatorController::index');
    $routes->get('my-liquidations', 'ScholarshipCoordinatorController::myLiquidations');
    $routes->get('campus-overview', 'ScholarshipCoordinatorController::campusOverview');
    $routes->get('manage-liquidations', 'ScholarshipCoordinatorController::manageLiquidations');
    $routes->get('review/(:num)', 'ScholarshipCoordinatorController::reviewLiquidation/$1');
    $routes->post('update-liquidation-status', 'ScholarshipCoordinatorController::updateLiquidationStatus');
    $routes->post('update-status', 'ScholarshipCoordinatorController::updateLiquidationStatus');
    $routes->get('reports', 'ScholarshipCoordinatorController::reports');
});
