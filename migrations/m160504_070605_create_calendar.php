<?php

use yii\db\Migration;

/**
 * Handles the creation for table `calendar`.
 */
class m160504_070605_create_calendar extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('calendar', [
            'id' => $this->primaryKey(),
            'text'=>  $this->text(),
            'creator'=>  $this->integer(),
            'date_event'=>$this->dateTime()
        ]);
        $this->createIndex('fk_calendar_user', 'calendar', 'creator');
        $this->addForeignKey('fk_calendar_1', 'calendar', 'creator', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('calendar');
    }
}
