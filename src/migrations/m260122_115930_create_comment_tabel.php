<?php

use yii\db\Migration;

class m260122_115930_create_comment_tabel extends Migration
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
        $this->createTable('{{%comment}}', [
            'id'            => $this->primaryKey(),
            'ticket_id'     => $this->integer()->notNull(),
            'message'       => $this->text()->notNull(),
            'created_by'    => $this->integer()->notNull(),
            'created_at'    => $this->dateTime()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-comment-ticket_id}}',
            '{{%comment}}',
            'ticket_id'
        );

        // add foreign key for table `{{%ticket}}`
        $this->addForeignKey(
            '{{%fk-comment-ticket_id}}',
            '{{%comment}}',
            'ticket_id',
            '{{%ticket}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%comment}}');
    }
}
