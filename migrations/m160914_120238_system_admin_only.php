<?php

use yii\db\Migration;

class m160914_120238_system_admin_only extends Migration
{
    public function up()
    {
        $this->addColumn('report_content', 'system_admin_only', 'boolean DEFAULT 0');
    }

    public function down()
    {
        echo "m160914_120238_system_admin_only cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
