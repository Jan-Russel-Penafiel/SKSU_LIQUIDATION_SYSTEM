<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeFieldsToManualLiquidations extends Migration
{
    public function up()
    {
        $this->forge->addColumn('manual_liquidations', [
            'disbursement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('manual_liquidations', 'disbursement_id');
    }
}
