<?php

use yii\db\Migration;

class m210221_041053_02_create_table_oauth_auth_codes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%oauth_auth_codes}}',
            [
                'id' => $this->string(100)->notNull()->append('PRIMARY KEY'),
                'user_id' => $this->string(100)->notNull(),
                'client_id' => $this->integer(10)->unsigned()->notNull(),
                'scopes' => $this->text(),
                'revoked' => $this->boolean()->notNull(),
                'expires_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('oauth_auth_codes_user_id_foreign', '{{%oauth_auth_codes}}', ['user_id']);
        $this->createIndex('oauth_auth_codes_client_id_foreign', '{{%oauth_auth_codes}}', ['client_id']);
    }

    public function down()
    {
        $this->dropTable('{{%oauth_auth_codes}}');
    }
}
