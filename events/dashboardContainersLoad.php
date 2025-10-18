<?php

namespace fpcm\modules\nkorg\polls\events;

final class dashboardContainersLoad extends \fpcm\module\event {

    public function run() : \fpcm\module\eventResult
    {
        $this->data->addContainer('models\dashContainerRecentPoll');
        return (new \fpcm\module\eventResult())->setData($this->data);
    }

    public function init() : bool
    {
        return true;
    }

}
