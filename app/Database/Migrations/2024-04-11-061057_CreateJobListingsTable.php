<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJobListingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'hotelier_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'job_description' => [
                'type' => 'TEXT'
            ],
            'job_type' => [
                'type' => 'ENUM("Full-time", "Part-time")'
            ],
            'skill_requirements' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'experience_requirements' => [
                'type' => 'TEXT',
                'null' => true
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
        $this->forge->addForeignKey('hotelier_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('job_listings');
    }

    public function down()
    {
        $this->forge->dropTable('job_listings');
    }
}



