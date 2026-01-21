<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m260119_105219_create_user_table extends Migration
{
    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                        => $this->primaryKey(),
            'name'                      => $this->string()->notNull(),
            'username'                  => $this->string()->notNull()->unique(),
            'role'                      => $this->smallInteger()->notNull(),
            'site_key'                  => $this->string(32)->defaultValue(null),
            'site_user_id'              => $this->integer()->defaultValue(null),
            'domain_id'                 => $this->integer()->notNull(),
            'status'                    => $this->smallInteger()->notNull()->defaultValue(10),
            'auth_key'                  => $this->string(32)->notNull(),
            'password_hash'             => $this->string()->notNull(),
            'verification_token'        => $this->string()->defaultValue(null),
            'expires_at'                => $this->dateTime()->defaultValue(null),
            'created_at'                => $this->dateTime()->notNull(),
            'updated_at'                => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%user}}');
    }
}
