<?php

use yii\db\Migration;

/**
 * Handles the creation for table `access`.
 */
class m160504_062605_create_access extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('access', [
            'id' => $this->primaryKey(),
            'user_owner'=>  $this->integer(),
            'user_guest'=>  $this->integer(),
            'date'=>  $this->date()
            
        ]);
        $this->createIndex('fk_access_user_1_idx', 'access', 'user_owner');
        $this->createIndex('fk_access_user_2_idx', 'access', 'user_guest');
        $this->addForeignKey('fk_access_user_1', 'access', 'user_owner', 'user', 'id');
        $this->addForeignKey('fk_access_user_2', 'access', 'user_guest', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('access');
    }
}
