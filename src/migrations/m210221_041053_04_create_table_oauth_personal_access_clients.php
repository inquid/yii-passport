<?php

use yii\db\Migration;

class m210221_041053_04_create_table_oauth_personal_access_clients extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%oauth_personal_access_clients}}',
            [
                'id' => $this->primaryKey(10)->unsigned(),
                'client_id' => $this->integer(10)->unsigned()->notNull(),
                'created_at' => $this->timestamp(),
                'updated_at' => $this->timestamp(),
            ],
            $tableOptions
        );

        $this->createIndex('oauth_personal_access_clients_client_id_index', '{{%oauth_personal_access_clients}}', ['client_id']);
    }

    public function down()
    {
        $this->dropTable('{{%oauth_personal_access_clients}}');
    }
}
