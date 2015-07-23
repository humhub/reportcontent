<?php

namespace humhub\modules\reportcontent\models;

use Yii;

/**
 * ReportReasonForm
 *
 * @package humhub.modules.reportcontent.forms
 * @author Marjana Pesic
 */
class ReportReasonForm extends \yii\base\Model
{

    public $object_id;
    public $reason;

    /**
     * Initalization of form, removing * from required field
     */
    public function init()
    {
        return parent::init();
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array(['reason', 'object_id'], 'required')
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'reason' => Yii::t('ReportcontentModule.base', 'Why do you want to report this post?')
        );
    }

}

?>