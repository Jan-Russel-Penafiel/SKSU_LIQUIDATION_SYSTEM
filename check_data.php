<?php
$conn = new mysqli('localhost', 'root', '', 'sksu_liquid');

echo "Recent Disbursements:\n";
$result = $conn->query('SELECT id, status, recipient_name, created_at FROM disbursements ORDER BY id DESC LIMIT 5');
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} | Status: {$row['status']} | Recipient: {$row['recipient_name']} | Created: {$row['created_at']}\n";
}

echo "\nRecent Manual Liquidations:\n";
$result2 = $conn->query('SELECT id, type, source_type, disbursement_id, status, created_at FROM manual_liquidations ORDER BY id DESC LIMIT 5');
while ($row = $result2->fetch_assoc()) {
    echo "ID: {$row['id']} | Type: {$row['type']} | Source: {$row['source_type']} | Disb ID: {$row['disbursement_id']} | Status: {$row['status']} | Created: {$row['created_at']}\n";
}

$conn->close();
