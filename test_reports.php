<?php

/**
 * Test file to verify the Reports system is working correctly
 * 
 * This file tests:
 * 1. Database connection
 * 2. User roles and access
 * 3. Basic reporting functionality
 * 
 * Run this by accessing: http://localhost/sksu_liquid/test_reports.php
 */

// Include CodeIgniter bootstrap
require_once 'vendor/autoload.php';

// Initialize the application
$app = \Config\Services::codeigniter();

try {
    // Test database connection
    $db = \Config\Database::connect();
    echo "<h2>Database Connection: OK</h2>";
    
    // Test user model
    $userModel = new \App\Models\UserModel();
    $adminUsers = $userModel->getUsersByRole('admin');
    echo "<h3>Admin Users Found: " . count($adminUsers) . "</h3>";
    
    if (!empty($adminUsers)) {
        echo "<p>First admin user: " . $adminUsers[0]['username'] . "</p>";
    }
    
    // Test recipient model
    $recipientModel = new \App\Models\ScholarshipRecipientModel();
    $campuses = $recipientModel->select('campus')->distinct()->where('campus IS NOT NULL')->findAll();
    echo "<h3>Campuses Found: " . count($campuses) . "</h3>";
    
    // Test manual liquidation model
    $manualModel = new \App\Models\ManualLiquidationModel();
    $manualCount = $manualModel->countAllResults();
    echo "<h3>Manual Liquidations Count: " . $manualCount . "</h3>";
    
    // Test ATM liquidation model
    $atmModel = new \App\Models\AtmLiquidationDetailModel();
    $atmCount = $atmModel->countAllResults();
    echo "<h3>ATM Liquidations Count: " . $atmCount . "</h3>";
    
    echo "<h2 style='color: green;'>All models loaded successfully!</h2>";
    echo "<p><a href='reports'>Go to Admin Reports</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>