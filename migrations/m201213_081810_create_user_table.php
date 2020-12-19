<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m201213_081810_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $table = '{{%user}}';

        $this->createTable($table, [
            'id' => $this->primaryKey()
                ->unsigned(),
            'username' => $this->string(25)
                ->notNull(),
            'password' => $this->string()
                ->notNull(),
            'role' => $this->tinyInteger(1)
                ->unsigned()
                ->notNull(),
            'accessToken' => $this->char(32)
                ->null(),
            'marker' => $this->char(23)
                ->null(),
        ], 'engine=innodb');

        $this->createIndex('ix_username', $table, 'username', true);
        $this->createIndex('ix_accessToken', $table, 'accessToken');
        $this->createIndex('ix_marker', $table, 'marker');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
