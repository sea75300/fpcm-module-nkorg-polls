<?php

namespace fpcm\modules\nkorg\polls\events\navigation;

final class add extends \fpcm\module\event {

    public function run()
    {
        $item = (new \fpcm\model\theme\navigationItem())
                ->setDescription('MODULE_NKORGPOLLS_HEADLINE')
                ->setIcon('poll')
                ->setUrl('polls/list');
        
        $this->data->add(\fpcm\model\theme\navigationItem::AREA_AFTER, $item);
        return $this->data;
    }

    public function init()
    {
        return true;
    }

}
