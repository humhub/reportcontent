<?php

class m141220_192625_initial extends humhub\components\Migration
{
    public function up()
    {
        $this->createTable('report_content', [
            'id' => 'pk',
            'object_model' => 'varchar(100) NOT NULL',
            'object_id' => 'int(11) NOT NULL',
            'reason' => 'int(11) NOT NULL',
            'created_at' => 'datetime DEFAULT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
        ], '');
    }

    public function down()
    {
        echo "m141220_192625_initial does not support migration down.\n";
        return false;
    }

}
