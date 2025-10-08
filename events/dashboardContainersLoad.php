<?php

namespace fpcm\modules\nkorg\polls\events;

final class dashboardContainersLoad extends \fpcm\module\event {

    public function run() : \fpcm\module\eventResult
    {
        if (version_compare(\fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_version, '4.3.0-rc9', '<')) {
            return $this->data;
        }

        $this->data[] = '\fpcm\modules\nkorg\polls\models\dashContainerRecentPoll';

        return (new \fpcm\module\eventResult())->setData($this->data);
    }

    public function init() : bool
    {
        return true;
    }

}
