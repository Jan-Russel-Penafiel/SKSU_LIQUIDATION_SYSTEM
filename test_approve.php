<?php
// Test the approve method directly using mysqli
$mysqli = new mysqli('localhost', 'root', '', 'sksu_liquid');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Testing approve method...\n\n";

// Check disbursement ID 1
$stmt = $mysqli->prepare("SELECT * FROM disbursements WHERE id = ?");
$id = 1;
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo "Disbursement 1:\n";
print_r($result);
echo "\n";

// Check if there's a voucher number for this disbursement
$voucherNumber = 'DV-' . date('Y') . '-' . str_pad(1, 6, '0', STR_PAD_LEFT);
echo "Expected voucher number: $voucherNumber\n\n";

// Check if manual liquidation with this voucher exists
$stmt = $mysqli->prepare("SELECT * FROM manual_liquidations WHERE voucher_number = ?");
$stmt->bind_param('s', $voucherNumber);
$stmt->execute();
$liquidation = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo "Existing liquidation with voucher $voucherNumber:\n";
print_r($liquidation);
echo "\n";

// Check recipient
$stmt = $mysqli->prepare("SELECT * FROM scholarship_recipients WHERE recipient_id = ?");
$stmt->bind_param('s', $result['recipient_id']);
$stmt->execute();
$recipient = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo "Recipient with ID {$result['recipient_id']}:\n";
print_r($recipient);
echo "\n";

echo "=== Testing insert manually ===\n";

// Try to manually insert
try {
    if (!$recipient) {
        echo "Creating recipient...\n";
        $nameArray = explode(' ', trim($result['recipient_name']));
        $firstName = $nameArray[0] ?? '';
        $lastName = count($nameArray) > 1 ? end($nameArray) : '';
        $middleName = count($nameArray) > 2 ? implode(' ', array_slice($nameArray, 1, -1)) : '';
        
        $stmt = $mysqli->prepare("INSERT INTO scholarship_recipients (recipient_id, first_name, last_name, middle_name, course, year_level, campus, scholarship_type, email, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $email = $result['recipient_id'] . '@temp.edu';
        $is_active = 1;
        $stmt->bind_param('sssssssssi', 
            $result['recipient_id'],
            $firstName,
            $lastName,
            $middleName,
            $result['course_program'],
            $result['year_level'],
            $result['campus'],
            $result['scholarship_type'],
            $email,
            $is_active
        );
        $stmt->execute();
        $recipient_id = $mysqli->insert_id;
        $stmt->close();
        echo "Recipient created with ID: $recipient_id\n";
    } else {
        $recipient_id = $recipient['id'];
        echo "Using existing recipient ID: $recipient_id\n";
    }
    
    $status = 'pending';
    $type = 'disbursement';
    $source_type = 'disbursement';
    $remarks = 'Transformed from approved disbursement - awaiting liquidation';
    $description = $result['scholarship_type'] . ' - ' . $result['recipient_name'] . ' (From Disbursement)';
    
    echo "Attempting to insert manual liquidation...\n";
    echo "Data: disbursement_id=$id, recipient_id=$recipient_id, voucher=$voucherNumber\n";
    
    $stmt = $mysqli->prepare("INSERT INTO manual_liquidations (disbursement_id, recipient_id, disbursing_officer_id, voucher_number, amount, liquidation_date, semester, academic_year, campus, description, status, type, source_type, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('iiisdsssssssss',
        $id,
        $recipient_id,
        $result['disbursing_officer_id'],
        $voucherNumber,
        $result['amount'],
        $result['disbursement_date'],
        $result['semester'],
        $result['academic_year'],
        $result['campus'],
        $description,
        $status,
        $type,
        $source_type,
        $remarks
    );
    
    if ($stmt->execute()) {
        $insertId = $mysqli->insert_id;
        $stmt->close();
        echo "\nSUCCESS! Manual liquidation created with ID: $insertId\n";
        
        // Verify it was inserted
        $stmt = $mysqli->prepare("SELECT * FROM manual_liquidations WHERE id = ?");
        $stmt->bind_param('i', $insertId);
        $stmt->execute();
        $verify = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        echo "\nVerification:\n";
        print_r($verify);
    } else {
        echo "\nFAILED to insert!\n";
        echo "Error: " . $stmt->error . "\n";
        $stmt->close();
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

$mysqli->close();
