<?php

namespace fpcm\modules\nkorg\polls\controller;

final class polladd extends pollbase {

    public function request()
    {
        $this->poll = new \fpcm\modules\nkorg\polls\models\poll();
        if ($this->buttonClicked('save') && $this->save()) {
            $this->redirect('polls/edit', [
                'id' => $this->poll->getId()
            ]);
        }

        return true;
    }

    public function process()
    {
        $this->poll->setVoteExpiration($this->config->module_nkorgpolls_vote_expiration_default);
        $this->poll->setStarttime(time());   
        $this->view->setFormAction('polls/add');

        $replies = $this->poll->getReplies(true);
        $this->view->assign('replies', $replies);
        $this->view->addJsVars([
            'replyOptionsStart' => count($replies)
        ]);        
        
        parent::process();
    }

}
