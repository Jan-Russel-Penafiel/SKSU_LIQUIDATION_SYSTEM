<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDisbursementForeignKeys extends Migration
{
    public function up()
    {
        // Add foreign key constraints to disbursements table
        $this->db->query('ALTER TABLE disbursements ADD CONSTRAINT fk_disbursements_disbursing_officer FOREIGN KEY (disbursing_officer_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE disbursements ADD CONSTRAINT fk_disbursements_verified_by FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE disbursements ADD CONSTRAINT fk_disbursements_approved_by FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key constraints
        $this->db->query('ALTER TABLE disbursements DROP FOREIGN KEY fk_disbursements_disbursing_officer');
        $this->db->query('ALTER TABLE disbursements DROP FOREIGN KEY fk_disbursements_verified_by');
        $this->db->query('ALTER TABLE disbursements DROP FOREIGN KEY fk_disbursements_approved_by');
    }
}