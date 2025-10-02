<?php

namespace fpcm\modules\nkorg\polls\controller;

final class polllist extends \fpcm\controller\abstracts\module\controller {

    use \fpcm\controller\traits\common\dataView;
    
    /**
     * 
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    protected function getViewPath() : string
    {
        return 'dataview';
    }

    public function process()
    {
        $this->deletePoll();
        $this->closePoll();
        
        $key = $this->getModuleKey();
        $filterStatus = $this->request->fromPOST('filterStatus', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('pollAdd'))->setText($this->addLangVarPrefix('GUI_ADD_POLL'))->setIcon('plus')->setUrl(\fpcm\classes\tools::getControllerLink('polls/add')),
            (new \fpcm\view\helper\submitButton('pollClose'))->setText($this->addLangVarPrefix('GUI_CLOSE_POLL'))->setIcon('lock'),
            (new \fpcm\view\helper\select('filterStatus'))
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setSelected($filterStatus >= 0 ? $filterStatus : -1)
                ->setOptions([
                $this->addLangVarPrefix('POLL_STATUS_ALL') => '-1',
                $this->addLangVarPrefix('POLL_STATUS0') => '0',
                $this->addLangVarPrefix('POLL_STATUS1') => '1',
            ]),
            (new \fpcm\view\helper\deleteButton('pollDelete')),
        ]);
        
        $this->view->addJsVars([
            'polls' => [],
            'isPollsList' => true
        ]);

        $this->view->addJsFiles([
            \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $key . '/js/module.js')
        ]);

        $search = new \fpcm\modules\nkorg\polls\models\search();
        $search->force = false;
        $search->isClosed = $filterStatus;

        $this->items = (new \fpcm\modules\nkorg\polls\models\polls())->getAllPolls($search);
        $this->initDataView();

        $this->view->addTabs('polls', [
            (new \fpcm\view\helper\tabItem('main'))
                ->setText($this->addLangVarPrefix('HEADLINE'))
                ->setFile( \fpcm\view\view::PATH_COMPONENTS . 'dataview__inline' )
        ]);

        $this->view->setFormAction('polls/list');
        $this->view->render();
        return true;
    }
    
    private function deletePoll() : bool {
        
        if (!$this->buttonClicked('pollDelete')) {
            return true;
        }

        $id = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        if (!$id) {
            return false;
        }

        $poll = new \fpcm\modules\nkorg\polls\models\poll($id);
        if (!$poll->exists()) {
            $this->view->addErrorMessage($this->addLangVarPrefix('MSG_PUB_NOTFOUND'));
            return false;
        }

        if (!$poll->delete()) {
            $this->view->addErrorMessage($this->addLangVarPrefix('MSG_ERR_DELETEPOLL'));
            return false;
        }

        return true;
    }
    private function closePoll() : bool {
        
        if (!$this->buttonClicked('pollClose')) {
            return true;
        }

        $id = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        if (!$id) {
            return false;
        }

        $poll = new \fpcm\modules\nkorg\polls\models\poll($id);
        if (!$poll->exists()) {
            $this->view->addErrorMessage($this->addLangVarPrefix('MSG_PUB_NOTFOUND'));
            return false;
        }

        $poll->setIsclosed(1);
        $poll->setStoptime(time());
        if (!$poll->update()) {
            $this->view->addErrorMessage($this->addLangVarPrefix('MSG_ERR_CLOSEPOLL'));
            return false;
        }

        $this->view->addNoticeMessage($this->addLangVarPrefix('MSG_SUCCESS_SAVEPOLL'));
        return true;
    }

    protected function getDataViewCols(): array {

        return [
            (new \fpcm\components\dataView\column('select', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('name', $this->addLangVarPrefix('GUI_POLL_TEXT')))->setSize(4),
            (new \fpcm\components\dataView\column('time', $this->addLangVarPrefix('GUI_POLL_TIMESPAN')))->setSize(4)->setAlign('center'),
            (new \fpcm\components\dataView\column('status', $this->addLangVarPrefix('GUI_POLL_STATE')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('votes', $this->addLangVarPrefix('GUI_POLL_VOTES')))->setSize(1)->setAlign('center'),
        ];

    }

    protected function getDataViewName() {
        return 'nkorgpolls';
    }
    
    /**
     * 
     * @param \fpcm\modules\nkorg\polls\models\poll $poll
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($poll)
    {
        $time = new \fpcm\view\helper\dateText($poll->getStarttime(), 'd.m.Y');

        if ($poll->getStoptime()) {
            $time .= ' bis '.new \fpcm\view\helper\dateText($poll->getStoptime(), 'd.m.Y');
        }

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\radiobutton('id', 'chbx' . $poll->getId()))->setValue($poll->getId()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\editButton('edit'))->setUrlbyObject($poll) ),
            new \fpcm\components\dataView\rowCol('name', $poll->getText() ),
            new \fpcm\components\dataView\rowCol('time', $time ),
            new \fpcm\components\dataView\rowCol('status', $this->language->translate($this->addLangVarPrefix('POLL_STATUS'.(int) $poll->getIsclosed()))),
            new \fpcm\components\dataView\rowCol('votes', $poll->getVotessum() ? $poll->getVotessum() : 0 ),
        ]);
    }


}
