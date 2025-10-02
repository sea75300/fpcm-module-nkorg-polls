<?php

namespace fpcm\modules\nkorg\polls\events\cron;

final class includeDumpTables extends \fpcm\module\event {

    public function run()
    {
        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        
        $this->data[] = $db->getTablePrefixed('module_nkorgpolls_polls');
        $this->data[] = $db->getTablePrefixed('module_nkorgpolls_polls_replies');
        $this->data[] = $db->getTablePrefixed('module_nkorgpolls_vote_log');
        return $this->data;
    }

    public function init(): bool
    {
        return false;
    }

}