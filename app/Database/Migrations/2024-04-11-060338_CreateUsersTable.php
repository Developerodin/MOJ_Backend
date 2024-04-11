<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'mobile_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'otp' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'role' => [
                'type' => 'ENUM("Candidate", "Hotelier", "Admin")',
                'default' => 'Candidate'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}