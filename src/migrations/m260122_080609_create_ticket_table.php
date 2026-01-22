<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m260122_080609_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'assignee_id' => $this->integer()->defaultValue(null),
            'status_id' => $this->integer()->notNull(),
            'subject' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'betting_relative_user_id' => $this->integer()->defaultValue(null),
            'betting_number' => $this->string(10)->defaultValue(null),
            'betting_time_of_occurrence' => $this->dateTime()->defaultValue(null),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-ticket-category_id}}',
            '{{%ticket}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-ticket-category_id}}',
            '{{%ticket}}',
            'category_id',
            '{{%category}}',
            'id',
        );

        $this->createIndex(
            '{{%idx-ticket-assignee_id}}',
            '{{%ticket}}',
            'assignee_id'
        );

        // add foreign key for table `{{%project}}`
        $this->addForeignKey(
            '{{%fk-ticket-assignee_id}}',
            '{{%ticket}}',
            'assignee_id',
            '{{%user}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket}}');
    }
}
