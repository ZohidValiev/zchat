<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m201213_083347_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey()
                ->unsigned(),
            'content' => $this->string(255)
                ->notNull(),
            'isCorrect' => $this->boolean()
                ->unsigned()
                ->defaultValue(true)
                ->notNull(),
            'userId' => $this->integer(10)
                ->unsigned()
                ->notNull(),
            'userRole' => $this->tinyInteger(1)
                ->unsigned()
                ->notNull(),
            'createdAt' => $this->dateTime()
                ->notNull(),
            'marker' => $this->char(23)
                ->notNull(),
        ], 'engine=innodb');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%message}}');
    }
}
