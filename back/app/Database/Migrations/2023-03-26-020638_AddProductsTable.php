<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idProduct' => [
                'type' => 'INT',
                'constraint' => 255,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'text' => [
                'type' => 'TEXT',
            ],
            'price' => [
                'type' => 'INT',
                'null' => false
            ],
            'imageProduct' => [
                'type' => 'TEXT'
            ],
            'formRelease' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => false
            ],
            'idCategory' => [
                'type' => 'INT',
                'null' => false
            ]
        ]);
        $this->forge->addPrimaryKey('idProduct');
        $this->forge->addForeignKey('idCategory', 'category', 'idCategory', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product');
    }

    public function down()
    {
        $this->forge->dropTable('product');
    }
}
