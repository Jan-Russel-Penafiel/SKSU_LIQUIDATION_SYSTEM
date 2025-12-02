<?php
$db = new mysqli('localhost', 'root', '', 'sksu_liquid');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Clear existing migration records
$db->query("DELETE FROM migrations WHERE version != '2025-11-28-000001'");

$timestamp = time();
$batch = 2;

$migrations = [
    ['2025-11-28-022944', 'App\\Database\\Migrations\\CreateScholarshipTables'],
    ['2025-11-28-023000', 'App\\Database\\Migrations\\AddDisbursementForeignKeys'],
    ['2025-12-01-021559', 'App\\Database\\Migrations\\AddTypeFieldsToManualLiquidations'],
    ['2025-12-01-021645', 'App\\Database\\Migrations\\UpdateExistingManualLiquidationTypes']
];

foreach($migrations as $migration) {
    $version = $migration[0];
    $class = $migration[1];
    $sql = "INSERT INTO migrations (version, class, `group`, namespace, time, batch) VALUES ('$version', '$class', 'default', 'App', $timestamp, $batch)";
    if ($db->query($sql)) {
        echo "Marked migration as completed: $version\n";
        $timestamp++; // Increment timestamp for proper ordering
        $batch++;
    } else {
        echo "Error marking migration: " . $db->error . "\n";
    }
}

$db->close();
echo "\nAll existing tables have been marked as migrated successfully!\n";
echo "Your database is now in sync with the migration system.\n";
?>