<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%incorrect_message}}`.
 */
class m201216_090901_create_incorrect_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $table = '{{%incorrect_message}}';

        $this->createTable($table, [
            'id' => $this->primaryKey()
                ->unsigned(),
            'messageId' => $this->integer(10)
                ->unsigned()
                ->notNull(),
            'createdAt' => $this->dateTime()
                ->notNull(),
        ]);

        $this->createIndex('ix_messageId', $table, 'messageId', true);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%incorrect_message}}');
    }
}
