<?php

use yii\db\Migration;

/**
 * Class m230219_213026_content_id
 */
class m230219_213026_content_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('report_content', 'content_id', $this->integer()->after('id'));

        // Assign Content ID
        $rows = (new \yii\db\Query())->select("*")->from('report_content')->all();
        foreach ($rows as $row) {
            $content = (new \yii\db\Query())->select("*")->from('content')
                ->where(['object_model' => $row['object_model'], 'object_id' => $row['object_id']])
                ->one();

            if ($content === null) {
                $this->delete('report_content', ['id' => $row['id']]);
            } else {
                $this->update('report_content', ['content_id' => $content['id']], ['id' => $row['id']]);
            }
        }

        $this->alterColumn('report_content', 'content_id', $this->integer()->after('id')->notNull());
        $this->addColumn('report_content', 'comment_id', $this->integer()->after('content_id')->null());

        $this->addForeignKey('fk_report_content_content', 'report_content', 'content_id', 'content', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_report_content_comment', 'report_content', 'comment_id', 'comment', 'id', 'CASCADE', 'CASCADE');

        $this->dropColumn('report_content', 'updated_at');
        $this->dropColumn('report_content', 'updated_by');

        $this->dropColumn('report_content', 'object_model');
        $this->dropColumn('report_content', 'object_id');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230219_213026_content_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230219_213026_content_id cannot be reverted.\n";

        return false;
    }
    */
}
