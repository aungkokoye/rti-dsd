<?php

use yii\db\Migration;

class m260123_064837_create_audit_log_table extends Migration
{
    /**
     *
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'action' => $this->string(50)->notNull(),
            'model' => $this->string(100)->null(),
            'model_id' => $this->integer()->null(),
            'ip_address' => $this->string(45)->null(),
            'user_agent' => $this->string(255)->null(),
            'data' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex('idx_audit_log_user_id', '{{%audit_log}}', 'user_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%audit_log}}');
    }
}
