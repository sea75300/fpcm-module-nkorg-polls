<?php

namespace fpcm\modules\nkorg\polls\models;

class dashContainerRecentPoll extends \fpcm\model\abstracts\dashcontainer {

    use \fpcm\module\tools;

    /**
     * Container chart
     * @var \fpcm\components\charts\chart
     */
    private $chart;

    /**
     * Poll for chart
     * @var poll
     */
    private $poll = false;

    protected function initObjects()
    {
        $this->chart = new \fpcm\components\charts\chart($this->config->module_nkorgpolls_chart_type, 'fpcm-nkorg-polls-dashchart');

        if (!$this->config->module_nkorgpolls_show_latest_poll) {
            return true;
        }

        $id = (new \fpcm\modules\nkorg\polls\models\polls())->getLatestPoll();
        if (!$id) {
            return true;
        }

        $this->poll = new poll($id);
        if (!$this->poll->exists()) {
            return true;
        }

        $this->chart->addOptions('legend', [
            'position' => 'bottom'
        ]);
        
        \fpcm\modules\nkorg\polls\models\chartdraw::draw($this->chart, $this->poll);
        return true;
    }


    public function getContent() : string
    {
        if ($this->poll === false) {
            return $this->language->translate('GLOBAL_NOTFOUND2');
        }

        return implode(PHP_EOL, [
            '<div class="row no-gutters align-self-center align-content-center justify-content-center">',
            '   <div class="col-12">',
            $this->chart,
            '   </div>',
            '</div>'
        ]);
    }

    public function getHeadline() : string
    {
        if ($this->poll === false) {
            return $this->language->translate($this->addLangVarPrefix('GUI_DASHBOARD_LATEST'), [
                'text' => $this->language->translate('GLOBAL_NOTFOUND2')
            ]);
        }

        return $this->language->translate($this->addLangVarPrefix('GUI_DASHBOARD_LATEST'), [
            'text' => (string) new \fpcm\view\helper\escape($this->poll->getText())
        ]);
    }

    public function getName() : string
    {
        return 'nkorg_polls_recentpoll';
    }
    
    public function getHeight() : string 
    {
        return self::DASHBOARD_HEIGHT_SMALL_MEDIUM;
    }

    public function getPosition()
    {
        return self::DASHBOARD_POS_MAX;
    }

    public function getJavascriptFiles() : array
    {
        $files = $this->chart->getJsFiles();
        $files[1] = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_JS, $files[1]);
        $files[] = \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MODULES, $this->getModuleKey() . '/js/moduleDashboard.js');
        
        return $files;
    }

    public function getJavascriptVars() : array 
    {
        if ($this->poll === false) {
            return [];
        }

        return [
            'pollChartData' => $this->chart
        ];
    }

}
