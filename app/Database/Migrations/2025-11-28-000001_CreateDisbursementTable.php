<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDisbursementTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'disbursement_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'recipient_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'recipient_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Student ID or Recipient Identifier',
            ],
            'course_program' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => false,
            ],
            'year_level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'semester' => [
                'type' => 'ENUM',
                'constraint' => ['1st Semester', '2nd Semester', 'Summer'],
                'null' => false,
            ],
            'academic_year' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'comment' => 'Format: 2024-2025',
            ],
            'scholarship_type' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
                'comment' => 'Type of scholarship',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'disbursement_method' => [
                'type' => 'ENUM',
                'constraint' => ['Cash', 'Check', 'Bank_Transfer', 'ATM'],
                'null' => false,
                'default' => 'Cash',
            ],
            'campus' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'disbursing_officer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'approved', 'rejected', 'disbursed'],
                'null' => false,
                'default' => 'pending',
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'verification_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verified_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approval_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'disbursed_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('disbursing_officer_id');
        $this->forge->addKey('verified_by');
        $this->forge->addKey('approved_by');
        $this->forge->addKey('recipient_id');
        $this->forge->addKey('campus');
        $this->forge->addKey('status');
        $this->forge->addKey('disbursement_date');

        $this->forge->createTable('disbursements');
    }

    public function down()
    {
        $this->forge->dropTable('disbursements');
    }
}