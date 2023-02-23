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
            'profanityFilterList' => Yii::t('ReportcontentModule.base', 'Profanity Filter List'),
            'blockContributions' => Yii::t('ReportcontentModule.base', 'Block Contributions'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'profanityFilterList' => Yii::t('ReportcontentModule.base', 'Each word is separated by a comma. The list is automatically sorted alphabetically when saved.'),
            'blockContributions' => Yii::t('ReportcontentModule.base', 'For matches in the profanity filter, prevent creation instead of reporting content. '),
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

        return true;
    }

}