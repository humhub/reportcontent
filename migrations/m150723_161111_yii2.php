<?php

use yii\db\Schema;
use humhub\components\Migration;
use humhub\modules\reportcontent\models\ReportContent;
use humhub\modules\post\models\Post;

class m150723_161111_yii2 extends Migration
{

    public function up()
    {
        $this->renameClass('ReportContent', ReportContent::className());

        // Only allow posts during upgrade to namespaced version
        $this->delete('report_content', ['!=', 'object_model', 'Post']);

        // Namespace object_model
        $this->update('report_content', ['object_model' => Post::className()], ['object_model' => 'Post']);

        // Remove all open notifications
        $this->delete('notification', ['class' => 'NewReportAdminNotification']);
        $this->delete('notification', ['class' => 'NewReportNotification']);
    }

    public function down()
    {
        echo "m150723_161111_yii2 cannot be reverted.\n";

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
