<?php

namespace Inquid\YiiPassport\migrations;

use yii\db\Migration;

class m210221_041053_01_create_table_oauth_access_tokens extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%oauth_access_tokens}}',
            [
                'id' => $this->string(100)->notNull()->append('PRIMARY KEY'),
                'user_id' => $this->string(100),
                'client_id' => $this->integer(10)->unsigned()->notNull(),
                'name' => $this->string(),
                'scopes' => $this->text(),
                'revoked' => $this->boolean()->notNull(),
                'created_at' => $this->timestamp(),
                'updated_at' => $this->timestamp(),
                'expires_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('oauth_access_tokens_client_id_foreign', '{{%oauth_access_tokens}}', ['client_id']);
        $this->createIndex('oauth_access_tokens_user_id_index', '{{%oauth_access_tokens}}', ['user_id']);
    }

    public function down()
    {
        $this->dropTable('{{%oauth_access_tokens}}');
    }
}
