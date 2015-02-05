<?php

class uninstall extends ZDbMigration
{

    public function up()
    {
        $this->dropTable('report_content');
        $this->delete('notification', 'source_object_model=:reportContent', array(':reportContent' => 'ReportContent'));
    }

    public function down()
    {
        echo "m141220_192625_initial does not support migration down.\n";
        return false;
    }
}
?>