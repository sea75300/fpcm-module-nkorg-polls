<?php

namespace fpcm\modules\nkorg\polls\events\modules;

final class configure extends \fpcm\module\event {

    public function run()
    {
        $this->data = [
            'charTypes' => [
                $this->addLangVarPrefix('GUI_CHARTTYPE_PIE') => \fpcm\components\charts\chart::TYPE_PIE,
                $this->addLangVarPrefix('GUI_CHARTTYPE_BAR') => \fpcm\components\charts\chart::TYPE_BAR,
                $this->addLangVarPrefix('GUI_CHARTTYPE_DOUGHNUT') => \fpcm\components\charts\chart::TYPE_DOUGHNUT
            ]
        ];

        return $this->data;
    }

    public function init()
    {
        return true;
    }

}
