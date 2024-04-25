<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Resume extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'Resume' => [
                'type' => 'MAX',
                'null' => true,
            ],
           
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('Resume');
    }

    public function down()
    {
        $this->forge->dropTable('Resume');
    }
}