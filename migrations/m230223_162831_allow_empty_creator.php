<?php

use yii\db\Migration;

/**
 * Class m230223_162831_allow_empty_creator
 */
class m230223_162831_allow_empty_creator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('report_content', 'created_by', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230223_162831_allow_empty_creator cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230223_162831_allow_empty_creator cannot be reverted.\n";

        return false;
    }
    */
}
