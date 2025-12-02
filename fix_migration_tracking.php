<?php
$db = new mysqli('localhost', 'root', '', 'sksu_liquid');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Clear all migration records except the first one
$db->query("DELETE FROM migrations WHERE version != '2025-11-28-000001'");

// Get the current batch number and time from the existing migration
$result = $db->query("SELECT batch, time FROM migrations WHERE version = '2025-11-28-000001'");
$row = $result->fetch_assoc();
$batch = $row['batch'] + 1;
$baseTime = $row['time'] + 1;

$migrations = [
    ['2025-11-28-022944', 'App\\Database\\Migrations\\CreateScholarshipTables'],
    ['2025-11-28-023000', 'App\\Database\\Migrations\\AddDisbursementForeignKeys'], 
    ['2025-12-01-021559', 'App\\Database\\Migrations\\AddTypeFieldsToManualLiquidations'],
    ['2025-12-01-021645', 'App\\Database\\Migrations\\UpdateExistingManualLiquidationTypes']
];

foreach($migrations as $i => $migration) {
    $version = $migration[0];
    $class = $migration[1];
    $time = $baseTime + $i;
    $sql = "INSERT INTO migrations (version, class, `group`, namespace, time, batch) VALUES ('$version', '$class', 'default', 'App', $time, $batch)";
    if ($db->query($sql)) {
        echo "Added migration record: $version (batch: $batch, time: $time)\n";
    } else {
        echo "Error adding migration: " . $db->error . "\n";
    }
}

echo "\nChecking migration status...\n";
$result = $db->query("SELECT * FROM migrations ORDER BY batch, time");
while($row = $result->fetch_assoc()) {
    echo "Migration: {$row['version']} - Batch: {$row['batch']} - Time: {$row['time']}\n";
}

$db->close();
echo "\nMigration tracking has been corrected!\n";
?>