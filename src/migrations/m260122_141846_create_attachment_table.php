<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachment}}`.
 */
class m260122_141846_create_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%attachment}}', [
            'id'            => $this->primaryKey(),
            'model_type'    => $this->string(100)->notNull(), // ticket, comment
            'model_id'      => $this->integer()->notNull(),
            'file_name'     => $this->string()->notNull(),
            'file_path'     => $this->string()->notNull(),
            'mimie_type'    => $this->string(100)->notNull(),
            'file_size'     => $this->integer()->notNull(),
            'created_by'    => $this->integer()->notNull(),
            'created_at'    => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%attachment}}');
    }
}
