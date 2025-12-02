-- Create database
CREATE DATABASE IF NOT EXISTS sksu_liquid;
USE sksu_liquid;

-- Users table for authentication and role management
CREATE TABLE users (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL UNIQUE,
    email varchar(100) NOT NULL UNIQUE,
    password_hash varchar(255) NOT NULL,
    role ENUM('admin', 'scholarship_coordinator', 'disbursing_officer', 'scholarship_chairman', 'accounting_officer') DEFAULT 'disbursing_officer',
    campus varchar(100) NULL,
    is_active tinyint(1) DEFAULT 1,
    created_at datetime NULL,
    updated_at datetime NULL,
    PRIMARY KEY (id)
);

-- Scholarship recipients table
CREATE TABLE scholarship_recipients (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    recipient_id varchar(50) NOT NULL UNIQUE,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    middle_name varchar(100) NULL,
    email varchar(100) NOT NULL,
    phone varchar(20) NULL,
    campus varchar(100) NOT NULL,
    course varchar(100) NOT NULL,
    year_level varchar(20) NOT NULL,
    scholarship_type varchar(100) NOT NULL,
    bank_account varchar(50) NULL,
    bank_name varchar(100) NULL,
    is_active tinyint(1) DEFAULT 1,
    created_at datetime NULL,
    updated_at datetime NULL,
    PRIMARY KEY (id)
);

-- Manual liquidations table
CREATE TABLE manual_liquidations (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    recipient_id int(11) unsigned NOT NULL,
    disbursing_officer_id int(11) unsigned NOT NULL,
    scholarship_coordinator_id int(11) unsigned NULL,
    voucher_number varchar(50) NOT NULL,
    amount decimal(15,2) NOT NULL,
    liquidation_date date NOT NULL,
    semester varchar(20) NOT NULL,
    academic_year varchar(20) NOT NULL,
    campus varchar(100) NOT NULL,
    description text NULL,
    status ENUM('pending', 'verified', 'approved', 'rejected') DEFAULT 'pending',
    remarks text NULL,
    created_at datetime NULL,
    updated_at datetime NULL,
    PRIMARY KEY (id)
);

-- ATM liquidations table
CREATE TABLE atm_liquidations (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    batch_name varchar(100) NOT NULL,
    file_path varchar(255) NOT NULL,
    file_type ENUM('csv', 'excel') NOT NULL,
    uploaded_by int(11) unsigned NOT NULL,
    total_records int(11) DEFAULT 0,
    processed_records int(11) DEFAULT 0,
    semester varchar(20) NOT NULL,
    academic_year varchar(20) NOT NULL,
    status ENUM('uploaded', 'processing', 'processed', 'sent_to_chairman', 'approved', 'sent_to_accounting', 'completed', 'rejected') DEFAULT 'uploaded',
    chairman_approval_date datetime NULL,
    accounting_received_date datetime NULL,
    remarks text NULL,
    created_at datetime NULL,
    updated_at datetime NULL,
    PRIMARY KEY (id)
);

-- ATM liquidation details table (now used as main table for per-recipient liquidations)
CREATE TABLE atm_liquidation_details (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    atm_liquidation_id int(11) unsigned NULL,
    recipient_id int(11) unsigned NOT NULL,
    transaction_date date NOT NULL,
    amount decimal(15,2) NOT NULL,
    reference_number varchar(100) NULL,
    file_path varchar(255) NULL,
    file_type ENUM('csv', 'excel', 'pdf') NULL,
    semester varchar(20) NOT NULL,
    academic_year varchar(20) NOT NULL,
    status ENUM('pending', 'verified', 'approved', 'rejected') DEFAULT 'pending',
    remarks text NULL,
    created_by int(11) unsigned NOT NULL,
    verified_by int(11) unsigned NULL,
    verified_at datetime NULL,
    approved_by int(11) unsigned NULL,
    approved_at datetime NULL,
    created_at datetime NULL,
    updated_at datetime NULL,
    PRIMARY KEY (id)
);

-- Workflow approvals table
CREATE TABLE workflow_approvals (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    liquidation_type ENUM('manual', 'atm') NOT NULL,
    liquidation_id int(11) unsigned NOT NULL,
    approver_id int(11) unsigned NOT NULL,
    approval_level ENUM('coordinator', 'chairman', 'accounting') NOT NULL,
    action ENUM('approved', 'rejected', 'returned') NOT NULL,
    remarks text NULL,
    approved_at datetime NULL,
    created_at datetime NULL,
    PRIMARY KEY (id)
);

-- Audit trail table
CREATE TABLE audit_trail (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    user_id int(11) unsigned NOT NULL,
    action varchar(100) NOT NULL,
    table_name varchar(50) NOT NULL,
    record_id int(11) unsigned NOT NULL,
    old_values text NULL,
    new_values text NULL,
    ip_address varchar(45) NULL,
    user_agent text NULL,
    created_at datetime NULL,
    PRIMARY KEY (id)
);

-- Insert initial data
INSERT INTO users (username, email, password_hash, role, campus, is_active, created_at, updated_at) VALUES
('admin', 'admin@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Main Campus', 1, NOW(), NOW()),
('chairman', 'chairman@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'scholarship_chairman', 'Main Campus', 1, NOW(), NOW()),
('coordinator', 'coordinator@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'scholarship_coordinator', 'Main Campus', 1, NOW(), NOW()),
('officer1', 'officer1@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'disbursing_officer', 'Main Campus', 1, NOW(), NOW()),
('accounting', 'accounting@sksu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'accounting_officer', 'Main Campus', 1, NOW(), NOW());

INSERT INTO scholarship_recipients (recipient_id, first_name, last_name, middle_name, email, phone, campus, course, year_level, scholarship_type, bank_account, bank_name, is_active, created_at, updated_at) VALUES
('SKSU-2024-001', 'Juan', 'Dela Cruz', 'Santos', 'juan.delacruz@student.sksu.edu.ph', '09171234567', 'Main Campus', 'Bachelor of Science in Information Technology', '4th Year', 'Academic Merit Scholarship', '1234567890', 'Land Bank of the Philippines', 1, NOW(), NOW()),
('SKSU-2024-002', 'Maria', 'Santos', 'Garcia', 'maria.santos@student.sksu.edu.ph', '09181234567', 'Main Campus', 'Bachelor of Science in Education', '3rd Year', 'CHED Scholarship', '2345678901', 'Development Bank of the Philippines', 1, NOW(), NOW()),
('SKSU-2024-003', 'Pedro', 'Gonzales', 'Reyes', 'pedro.gonzales@student.sksu.edu.ph', '09191234567', 'Kalamansig Campus', 'Bachelor of Science in Agriculture', '2nd Year', 'DOST Scholarship', '3456789012', 'Philippine National Bank', 1, NOW(), NOW()),
('SKSU-2024-004', 'Ana', 'Rodriguez', 'Cruz', 'ana.rodriguez@student.sksu.edu.ph', '09201234567', 'Palimbang Campus', 'Bachelor of Science in Business Administration', '1st Year', 'TES Scholarship', '4567890123', 'Banco de Oro', 1, NOW(), NOW()),
('SKSU-2024-005', 'Jose', 'Mendoza', 'Lopez', 'jose.mendoza@student.sksu.edu.ph', '09211234567', 'Isulan Campus', 'Bachelor of Science in Engineering', '4th Year', 'Engineering Scholarship', '5678901234', 'Metrobank', 1, NOW(), NOW());

INSERT INTO manual_liquidations (recipient_id, disbursing_officer_id, scholarship_coordinator_id, voucher_number, amount, liquidation_date, semester, academic_year, campus, description, status, created_at, updated_at) VALUES
(1, 4, 3, 'VO-2024-001', 5000.00, '2024-11-01', '1st Semester', '2024-2025', 'Main Campus', 'Academic merit scholarship liquidation for tuition fee', 'approved', NOW(), NOW()),
(2, 4, 3, 'VO-2024-002', 7500.00, '2024-11-05', '1st Semester', '2024-2025', 'Main Campus', 'CHED scholarship allowance', 'pending', NOW(), NOW()),
(3, 4, 3, 'VO-2024-003', 6000.00, '2024-11-10', '1st Semester', '2024-2025', 'Kalamansig Campus', 'DOST scholarship stipend', 'verified', NOW(), NOW());