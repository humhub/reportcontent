<?php

class uninstall extends humhub\components\Migration
{

    public function up()
    {
        $this->dropTable('report_content');
    }

    public function down()
    {
        echo "m141220_192625_initial does not support migration down.\n";
        return false;
    }

}

?>