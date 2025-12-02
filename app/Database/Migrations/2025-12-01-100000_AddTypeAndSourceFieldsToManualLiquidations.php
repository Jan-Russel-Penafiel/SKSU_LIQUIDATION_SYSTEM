<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeAndSourceFieldsToManualLiquidations extends Migration
{
    public function up()
    {
        // Add type field
        $this->forge->addColumn('manual_liquidations', [
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'manual',
                'after' => 'status'
            ],
            'source_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null,
                'after' => 'type'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('manual_liquidations', 'type');
        $this->forge->dropColumn('manual_liquidations', 'source_type');
    }
}
