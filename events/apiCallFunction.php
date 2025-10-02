<?php

namespace fpcm\modules\nkorg\polls\events;

use fpcm\classes\loader;

final class apiCallFunction extends \fpcm\module\event {

    private $jsVars = [];

    public function run()
    {
        $fn = $this->data['name'];
        if (!method_exists($this, $fn)) {
            trigger_error('Function '.$fn.' does not exists!');
            return false;
        }

        $pollId = $this->data['args'][0] ?? 0;
        call_user_func([$this, $fn], $pollId);
        return true;
    }

    public function init()
    {
        return true;
    }
    
    private function getViewObj()
    {
        $view = new \fpcm\view\view('publicform', $this->getModuleKey());
        $view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_NONE);
        $view->assign('pollJsFile', \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $this->getModuleKey() . '/js/fpcm-polls-pub.js'));
        $this->jsVars['spinner'] = \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $this->getModuleKey() . '/js/spinner.gif');
        $this->jsVars['actionPath'] = \fpcm\classes\tools::getFullControllerLink('ajax/polls/');
        $view->assign('pollJsVars', $this->jsVars);
        return $view;
    }

    final public function displayPoll($pollId = 0)
    {
        $showLatest = loader::getObject('\fpcm\model\system\config')->module_nkorgpolls_show_latest_poll;
        if (!$pollId && $showLatest) {
            $pollId = (new \fpcm\modules\nkorg\polls\models\polls())->getLatestPoll();
        }

        if (!$pollId) {
            return false;
        }

        $poll = new \fpcm\modules\nkorg\polls\models\poll($pollId);
        if (!$poll->exists()) {
            loader::getObject('\fpcm\classes\language')->write('MODULE_NKORGPOLLS_MSG_PUB_NOTFOUND');
            return false;
        }

        if (!$poll->isOpen() || $poll->hasVoted()) {
            $content = ( new \fpcm\modules\nkorg\polls\models\pollform($poll))->getResultForm();
        }
        else {
            $content = ( new \fpcm\modules\nkorg\polls\models\pollform($poll))->getVoteForm();
        }

        $view = $this->getViewObj();
        $view->assign('pollId', $poll->getId() );
        $view->assign('content', $content);
        $view->render();
        return true;
    }

    final protected function displayArchive(array $params = [])
    {
        $params['sort'] = $params['sort'] ?? ['starttime DESC'];

        $polls  = (new \fpcm\modules\nkorg\polls\models\polls())->getArchivedPolls(false, $params['sort']);
        if (!count($polls)) {
            loader::getObject('\fpcm\classes\language')->write('MODULE_NKORGPOLLS_MSG_PUB_NOARCHIVE');
            return false;
        }
        
        $pf = null;
        $content = '';

        /* @var $poll \fpcm\modules\nkorg\polls\models\poll */
        foreach ($polls as $poll) {
            
            if ($pf === null) {
                $pf = new \fpcm\modules\nkorg\polls\models\pollform($poll);
            }
            else {
                $pf->setPoll($poll);
            }
            
            $content .= '<!-- Archived poll '.$poll->getId().' -->'.PHP_EOL.$pf->getArchiveForm().PHP_EOL;
   
        }

        print $content;
        return true;
    }

}