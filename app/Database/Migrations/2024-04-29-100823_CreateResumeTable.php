<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResumeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'Resume' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
           
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('resumes');
    }

    public function down()
    {
        $this->forge->dropTable('resumes');
    }
}
