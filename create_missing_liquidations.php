<?php
// Create liquidation records for approved disbursements that don't have them
$mysqli = new mysqli('localhost', 'root', '', 'sksu_liquid');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== Creating Missing Liquidation Records ===\n\n";

// Find approved disbursements
$stmt = $mysqli->prepare("SELECT * FROM disbursements WHERE status = 'approved'");
$stmt->execute();
$disbursements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo "Found " . count($disbursements) . " approved disbursements\n\n";

foreach ($disbursements as $disbursement) {
    $id = $disbursement['id'];
    $voucherNumber = 'DV-' . date('Y') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
    
    // Check if liquidation already exists for this disbursement
    $stmt = $mysqli->prepare("SELECT id FROM manual_liquidations WHERE disbursement_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($existing) {
        echo "Disbursement ID $id: Liquidation already exists (ID {$existing['id']}), skipping...\n";
        continue;
    }
    
    echo "Disbursement ID $id: Creating liquidation record...\n";
    
    // Check/create recipient
    $stmt = $mysqli->prepare("SELECT * FROM scholarship_recipients WHERE recipient_id = ?");
    $stmt->bind_param('s', $disbursement['recipient_id']);
    $stmt->execute();
    $recipient = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$recipient) {
        echo "  - Creating recipient {$disbursement['recipient_id']}...\n";
        $nameArray = explode(' ', trim($disbursement['recipient_name']));
        $firstName = $nameArray[0] ?? '';
        $lastName = count($nameArray) > 1 ? end($nameArray) : '';
        $middleName = count($nameArray) > 2 ? implode(' ', array_slice($nameArray, 1, -1)) : '';
        
        $stmt = $mysqli->prepare("INSERT INTO scholarship_recipients (recipient_id, first_name, last_name, middle_name, course, year_level, campus, scholarship_type, email, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $email = $disbursement['recipient_id'] . '@temp.edu';
        $is_active = 1;
        $stmt->bind_param('sssssssssi', 
            $disbursement['recipient_id'],
            $firstName,
            $lastName,
            $middleName,
            $disbursement['course_program'],
            $disbursement['year_level'],
            $disbursement['campus'],
            $disbursement['scholarship_type'],
            $email,
            $is_active
        );
        $stmt->execute();
        $recipient_id = $mysqli->insert_id;
        $stmt->close();
        echo "  - Recipient created with ID: $recipient_id\n";
    } else {
        $recipient_id = $recipient['id'];
        echo "  - Using existing recipient ID: $recipient_id\n";
    }
    
    // Create manual liquidation
    $status = 'pending';
    $type = 'disbursement';
    $source_type = 'disbursement';
    $remarks = 'Transformed from approved disbursement - awaiting liquidation';
    $description = $disbursement['scholarship_type'] . ' - ' . $disbursement['recipient_name'] . ' (From Disbursement)';
    
    $stmt = $mysqli->prepare("INSERT INTO manual_liquidations (disbursement_id, recipient_id, disbursing_officer_id, voucher_number, amount, liquidation_date, semester, academic_year, campus, description, status, type, source_type, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('iiisdsssssssss',
        $id,
        $recipient_id,
        $disbursement['disbursing_officer_id'],
        $voucherNumber,
        $disbursement['amount'],
        $disbursement['disbursement_date'],
        $disbursement['semester'],
        $disbursement['academic_year'],
        $disbursement['campus'],
        $description,
        $status,
        $type,
        $source_type,
        $remarks
    );
    
    if ($stmt->execute()) {
        $insertId = $mysqli->insert_id;
        echo "  - SUCCESS! Created liquidation ID: $insertId with voucher: $voucherNumber\n";
    } else {
        echo "  - ERROR: " . $stmt->error . "\n";
    }
    $stmt->close();
    echo "\n";
}

echo "=== Verification ===\n\n";

// Show final state
$result = $mysqli->query("SELECT 
    d.id as disb_id, 
    d.status as disb_status,
    d.recipient_name,
    ml.id as liq_id,
    ml.voucher_number,
    ml.status as liq_status,
    ml.type,
    ml.source_type
FROM disbursements d
LEFT JOIN manual_liquidations ml ON d.id = ml.disbursement_id
WHERE d.status = 'approved'
ORDER BY d.id");

$rows = $result->fetch_all(MYSQLI_ASSOC);

echo "Approved Disbursements and their Liquidations:\n";
echo str_repeat("-", 100) . "\n";
printf("%-10s %-15s %-25s %-10s %-20s %-15s\n", 
    "Disb ID", "Status", "Recipient", "Liq ID", "Voucher", "Liq Status");
echo str_repeat("-", 100) . "\n";

foreach ($rows as $row) {
    printf("%-10s %-15s %-25s %-10s %-20s %-15s\n",
        $row['disb_id'],
        $row['disb_status'],
        substr($row['recipient_name'], 0, 25),
        $row['liq_id'] ?? 'NONE',
        $row['voucher_number'] ?? 'N/A',
        $row['liq_status'] ?? 'N/A'
    );
}

$mysqli->close();
echo "\nDone!\n";
