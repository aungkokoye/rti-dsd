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
