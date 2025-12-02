<?php
// Test disbursement flow
$mysqli = new mysqli('localhost', 'root', '', 'sksu_liquid');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Recent Disbursements ===\n";
$result = $mysqli->query("SELECT id, status, recipient_name, created_at FROM disbursements ORDER BY id DESC LIMIT 5");
while($row = $result->fetch_assoc()) {
    echo "Disb ID: {$row['id']} | Status: {$row['status']} | Recipient: {$row['recipient_name']} | Created: {$row['created_at']}\n";
}

echo "\n=== Manual Liquidations from Disbursements ===\n";
$result = $mysqli->query("SELECT id, disbursement_id, voucher_number, recipient_id, status, type, source_type FROM manual_liquidations WHERE source_type = 'disbursement' ORDER BY id DESC LIMIT 5");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Liq ID: {$row['id']} | Disb ID: {$row['disbursement_id']} | Voucher: {$row['voucher_number']} | Type: {$row['type']} | Status: {$row['status']}\n";
    }
} else {
    echo "No liquidations from disbursements found.\n";
}

$mysqli->close();
