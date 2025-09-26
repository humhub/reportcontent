<?php

namespace humhub\modules\reportcontent\models;

use humhub\components\SettingsManager;
use Yii;
use yii\base\Model;

class Configuration extends Model
{
    public SettingsManager $settingsManager;
    public string $profanityFilterList = '';
    public array $profanityFilter = [];
    public bool $blockContributions = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profanityFilterList'], 'safe'],
            [['blockContributions'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profanityFilterList' => Yii::t('ReportcontentModule.base', 'Profanity Filter'),
            'blockContributions' => Yii::t('ReportcontentModule.base', 'Automatically block content creation'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'profanityFilterList' => Yii::t('ReportcontentModule.base', 'Add prohibited terms separated by commas. Upon saving, the list will automatically be sorted in alphabetical order.'),
            'blockContributions' => Yii::t('ReportcontentModule.base', 'If checked, users will not be able to publish content containing terms from your profanity filter. Leave this unchecked if you want the content to be automatically reported to you instead.'),
        ];
    }

    public function loadBySettings(): void
    {
        $filterList = $this->settingsManager->getSerialized('profanityFilterList');
        $this->profanityFilter = (is_array($filterList)) ? $filterList : [];
        $this->profanityFilterList = implode(', ', $this->profanityFilter);

        $this->blockContributions = (bool)$this->settingsManager->get('blockContributions');
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $words = [];
        foreach (explode(',', $this->profanityFilterList) as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $words[] = mb_strtolower(trim($word));
            }
        }
        sort($words);

        $this->settingsManager->setSerialized('profanityFilterList', $words);
        $this->settingsManager->set('blockContributions', $this->blockContributions);

        // To rebuild array
        $this->loadBySettings();

        return true;
    }

}
