<?php

use yii\db\Migration;

class m210221_041053_05_create_table_oauth_refresh_tokens extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%oauth_refresh_tokens}}',
            [
                'id' => $this->string(100)->notNull()->append('PRIMARY KEY'),
                'access_token_id' => $this->string(100)->notNull(),
                'revoked' => $this->boolean()->notNull(),
                'expires_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('oauth_refresh_tokens_access_token_id_index', '{{%oauth_refresh_tokens}}', ['access_token_id']);
    }

    public function down()
    {
        $this->dropTable('{{%oauth_refresh_tokens}}');
    }
}
