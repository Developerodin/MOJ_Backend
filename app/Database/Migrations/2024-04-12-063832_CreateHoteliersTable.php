<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHoteliersTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'mobile_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'company_details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'gst_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'field_of_company' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contact_information' => [
                'type' => 'TEXT',
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
            'last_active' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hoteliers');
    }

    public function down()
    {
        $this->forge->dropTable('hoteliers');
    }
}
