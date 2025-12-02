<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScholarshipTables extends Migration
{
    public function up()
    {
        // Users table for authentication and role management
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('username varchar(50) NOT NULL UNIQUE');
        $this->forge->addField('email varchar(100) NOT NULL UNIQUE');
        $this->forge->addField('password_hash varchar(255) NOT NULL');
        $this->forge->addField('role ENUM("admin", "scholarship_coordinator", "disbursing_officer", "scholarship_chairman", "accounting_officer") DEFAULT "disbursing_officer"');
        $this->forge->addField('campus varchar(100) NULL');
        $this->forge->addField('is_active tinyint(1) DEFAULT 1');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addField('updated_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');

        // Scholarship recipients table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('recipient_id varchar(50) NOT NULL UNIQUE');
        $this->forge->addField('first_name varchar(100) NOT NULL');
        $this->forge->addField('last_name varchar(100) NOT NULL');
        $this->forge->addField('middle_name varchar(100) NULL');
        $this->forge->addField('email varchar(100) NOT NULL');
        $this->forge->addField('phone varchar(20) NULL');
        $this->forge->addField('campus varchar(100) NOT NULL');
        $this->forge->addField('course varchar(100) NOT NULL');
        $this->forge->addField('year_level varchar(20) NOT NULL');
        $this->forge->addField('scholarship_type varchar(100) NOT NULL');
        $this->forge->addField('bank_account varchar(50) NULL');
        $this->forge->addField('bank_name varchar(100) NULL');
        $this->forge->addField('is_active tinyint(1) DEFAULT 1');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addField('updated_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('scholarship_recipients');

        // Manual liquidations table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('recipient_id int(11) unsigned NOT NULL');
        $this->forge->addField('disbursing_officer_id int(11) unsigned NOT NULL');
        $this->forge->addField('scholarship_coordinator_id int(11) unsigned NULL');
        $this->forge->addField('voucher_number varchar(50) NOT NULL');
        $this->forge->addField('amount decimal(15,2) NOT NULL');
        $this->forge->addField('liquidation_date date NOT NULL');
        $this->forge->addField('semester varchar(20) NOT NULL');
        $this->forge->addField('academic_year varchar(20) NOT NULL');
        $this->forge->addField('campus varchar(100) NOT NULL');
        $this->forge->addField('description text NULL');
        $this->forge->addField('status ENUM("pending", "verified", "approved", "rejected") DEFAULT "pending"');
        $this->forge->addField('remarks text NULL');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addField('updated_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('manual_liquidations');

        // Add foreign key constraints for manual_liquidations
        $this->db->query('ALTER TABLE manual_liquidations ADD CONSTRAINT fk_manual_liquidations_recipient FOREIGN KEY (recipient_id) REFERENCES scholarship_recipients(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE manual_liquidations ADD CONSTRAINT fk_manual_liquidations_disbursing_officer FOREIGN KEY (disbursing_officer_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE manual_liquidations ADD CONSTRAINT fk_manual_liquidations_coordinator FOREIGN KEY (scholarship_coordinator_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');

        // ATM liquidations table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('batch_name varchar(100) NOT NULL');
        $this->forge->addField('file_path varchar(255) NOT NULL');
        $this->forge->addField('file_type ENUM("csv", "excel") NOT NULL');
        $this->forge->addField('uploaded_by int(11) unsigned NOT NULL');
        $this->forge->addField('total_records int(11) DEFAULT 0');
        $this->forge->addField('processed_records int(11) DEFAULT 0');
        $this->forge->addField('semester varchar(20) NOT NULL');
        $this->forge->addField('academic_year varchar(20) NOT NULL');
        $this->forge->addField('status ENUM("uploaded", "processing", "processed", "sent_to_chairman", "approved", "sent_to_accounting", "completed", "rejected") DEFAULT "uploaded"');
        $this->forge->addField('chairman_approval_date datetime NULL');
        $this->forge->addField('accounting_received_date datetime NULL');
        $this->forge->addField('remarks text NULL');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addField('updated_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('atm_liquidations');

        // Add foreign key constraints for atm_liquidations
        $this->db->query('ALTER TABLE atm_liquidations ADD CONSTRAINT fk_atm_liquidations_uploaded_by FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // ATM liquidation details table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('atm_liquidation_id int(11) unsigned NOT NULL');
        $this->forge->addField('recipient_id int(11) unsigned NOT NULL');
        $this->forge->addField('transaction_date date NOT NULL');
        $this->forge->addField('amount decimal(15,2) NOT NULL');
        $this->forge->addField('reference_number varchar(100) NULL');
        $this->forge->addField('bank_certification varchar(255) NULL');
        $this->forge->addField('status ENUM("pending", "verified", "approved", "rejected") DEFAULT "pending"');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addField('updated_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('atm_liquidation_details');

        // Add foreign key constraints for atm_liquidation_details
        $this->db->query('ALTER TABLE atm_liquidation_details ADD CONSTRAINT fk_atm_details_liquidation FOREIGN KEY (atm_liquidation_id) REFERENCES atm_liquidations(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE atm_liquidation_details ADD CONSTRAINT fk_atm_details_recipient FOREIGN KEY (recipient_id) REFERENCES scholarship_recipients(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Workflow approvals table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('liquidation_type ENUM("manual", "atm") NOT NULL');
        $this->forge->addField('liquidation_id int(11) unsigned NOT NULL');
        $this->forge->addField('approver_id int(11) unsigned NOT NULL');
        $this->forge->addField('approval_level ENUM("coordinator", "chairman", "accounting") NOT NULL');
        $this->forge->addField('action ENUM("approved", "rejected", "returned") NOT NULL');
        $this->forge->addField('remarks text NULL');
        $this->forge->addField('approved_at datetime NULL');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('workflow_approvals');

        // Add foreign key constraints for workflow_approvals
        $this->db->query('ALTER TABLE workflow_approvals ADD CONSTRAINT fk_workflow_approvals_approver FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');

        // Audit trail table
        $this->forge->addField('id int(11) unsigned NOT NULL AUTO_INCREMENT');
        $this->forge->addField('user_id int(11) unsigned NOT NULL');
        $this->forge->addField('action varchar(100) NOT NULL');
        $this->forge->addField('table_name varchar(50) NOT NULL');
        $this->forge->addField('record_id int(11) unsigned NOT NULL');
        $this->forge->addField('old_values text NULL');
        $this->forge->addField('new_values text NULL');
        $this->forge->addField('ip_address varchar(45) NULL');
        $this->forge->addField('user_agent text NULL');
        $this->forge->addField('created_at datetime NULL');
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('audit_trail');

        // Add foreign key constraints for audit_trail
        $this->db->query('ALTER TABLE audit_trail ADD CONSTRAINT fk_audit_trail_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('audit_trail');
        $this->forge->dropTable('workflow_approvals');
        $this->forge->dropTable('atm_liquidation_details');
        $this->forge->dropTable('atm_liquidations');
        $this->forge->dropTable('manual_liquidations');
        $this->forge->dropTable('scholarship_recipients');
        $this->forge->dropTable('users');
    }
}
