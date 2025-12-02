<?php
/**
 * Script to add missing columns to manual_liquidations table
 */

// Direct database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sksu_liquid';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected to database successfully!\n\n";

try {
    // Get current columns
    $result = $conn->query("SHOW COLUMNS FROM manual_liquidations");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    echo "Existing columns: " . implode(', ', $columns) . "\n\n";
    
    // Add type column if not exists
    if (!in_array('type', $columns)) {
        $conn->query("ALTER TABLE manual_liquidations ADD COLUMN type VARCHAR(50) DEFAULT 'manual' AFTER status");
        echo "Added 'type' column\n";
    } else {
        echo "'type' column already exists\n";
    }
    
    // Add source_type column if not exists
    if (!in_array('source_type', $columns)) {
        $conn->query("ALTER TABLE manual_liquidations ADD COLUMN source_type VARCHAR(50) NULL AFTER type");
        echo "Added 'source_type' column\n";
    } else {
        echo "'source_type' column already exists\n";
    }
    
    // Add disbursement_id column if not exists
    if (!in_array('disbursement_id', $columns)) {
        $conn->query("ALTER TABLE manual_liquidations ADD COLUMN disbursement_id INT(11) UNSIGNED NULL AFTER source_type");
        echo "Added 'disbursement_id' column\n";
    } else {
        echo "'disbursement_id' column already exists\n";
    }
    
    // Add approved_date column if not exists
    if (!in_array('approved_date', $columns)) {
        $conn->query("ALTER TABLE manual_liquidations ADD COLUMN approved_date DATETIME NULL AFTER disbursement_id");
        echo "Added 'approved_date' column\n";
    } else {
        echo "'approved_date' column already exists\n";
    }
    
    // Add approved_by column if not exists
    if (!in_array('approved_by', $columns)) {
        $conn->query("ALTER TABLE manual_liquidations ADD COLUMN approved_by INT(11) UNSIGNED NULL AFTER approved_date");
        echo "Added 'approved_by' column\n";
    } else {
        echo "'approved_by' column already exists\n";
    }
    
    echo "\nAll columns have been processed successfully!\n";
    
    // Show updated structure
    echo "\nUpdated table structure:\n";
    $result = $conn->query("SHOW COLUMNS FROM manual_liquidations");
    while ($row = $result->fetch_assoc()) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$conn->close();
