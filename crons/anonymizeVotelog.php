<?php

namespace fpcm\modules\nkorg\polls\crons;

class anonymizeVotelog extends \fpcm\model\abstracts\cron {

    public function run() {

        return $this->dbcon->update(
            'module_nkorgpolls_vote_log',
            ['ip'],
            ['127.0.0.1', $this->lastExecTime],
            'replytime < ?'
        );
        
    }

}