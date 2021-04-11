<?php

namespace Inquid\YiiPassport\migrations;

use yii\db\Migration;

class m210221_041053_03_create_table_oauth_clients extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%oauth_clients}}',
            [
                'id' => $this->primaryKey(10)->unsigned(),
                'user_id' => $this->string(100),
                'name' => $this->string()->notNull(),
                'secret' => $this->string(100)->notNull(),
                'redirect' => $this->text()->notNull(),
                'personal_access_client' => $this->boolean()->notNull(),
                'password_client' => $this->boolean()->notNull(),
                'revoked' => $this->boolean()->notNull(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('oauth_clients_user_id_index', '{{%oauth_clients}}', ['user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%oauth_clients}}');
    }
}
