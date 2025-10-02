<?php

namespace fpcm\modules\nkorg\polls\models;

class pollform {
    
    use \fpcm\module\tools;

    private $templateConfigPaths;

    /**
     *
     * @var poll
     */
    private $poll;
    
    private $tplCache = [];

    public function __construct(poll $poll) {
        
        $this->poll = $poll;
        $this->templateConfigPaths = dirname(__DIR__).'/config/templates/';
    }
    
    public function setPoll(poll $poll) {
        $this->poll = $poll;
        return $this;
    }
    
    private function getTemplates(string $name) {

        if (isset($this->tplCache[$name])) {
            return $this->tplCache[$name];
        }

        $customTpls = glob(\fpcm\classes\dirs::getDataDirPath('nkorg_polls', $name.'_*.html'));
        foreach ($customTpls as $tpl) {
            $this->tplCache[$name][basename($tpl)] = $tpl;
        }
        
        unset($tpl);

        $defaultTpls = glob($this->templateConfigPaths.$name.'_*.html');
        foreach ($defaultTpls as $tpl) {

            if (isset($this->tplCache[$name][basename($tpl)])) {
                continue;
            }

            $this->tplCache[$name][basename($tpl)] = $tpl;
        }

        unset($tpl);

        $this->tplCache[$name] = array_map('file_get_contents', $this->tplCache[$name]);
        return $this->tplCache[$name];
    }

    private function replaceTags(string &$str, array $data) {

        $str = str_replace(array_keys($data), array_values($data), $str);
        return true;
    }

    private function parseCondition(string &$str, string $replace, string $condStr = '') {

        if (!trim($condStr)) {
            $condStr = '([\$a-zA-Z0-9]+)';
        }

        $str = preg_replace('/\{if(\s{1})'.$condStr.'\}{1}(.+)\{\/if\}/', $replace, $str);
        return true;
    }

    public function getVoteForm() {
        
        $tpl = $this->getTemplates('voteform');

        $this->replaceTags($tpl['voteform_header.html'], [
            '{{poll_text}}' => $this->poll->getText()
        ]);

        $this->replaceTags($tpl['voteform_footer.html'], [
            '{{poll_button_sumit}}' => ( new \fpcm\view\helper\button('vote'.$this->poll->getId()) )
                ->setClass('fpcm-polls-poll-submit')
                ->setData(['pollid' => $this->poll->getId()])
                ->setText($this->addLangVarPrefix('GUI_PUB_SUBMITVOTE')),

            '{{poll_button_reset}}' => ( new \fpcm\view\helper\button('reset'.$this->poll->getId()) )
                ->setClass('fpcm-polls-poll-reset')
                ->setData(['pollid' => $this->poll->getId()])
                ->setText('GLOBAL_RESET'),
            '{{poll_button_results}}' => ( new \fpcm\view\helper\button('result'.$this->poll->getId()) )
                ->setClass('fpcm-polls-poll-result')
                ->setData(['pollid' => $this->poll->getId()])
                ->setText($this->addLangVarPrefix('GUI_PUB_SHOWRESULTS'))
        ]);

        if ($this->poll->getMaxreplies() > 1) {
            $replyClass = '\fpcm\view\helper\checkbox';
            $optionName = 'reply{{$pollid}}_{{$replyId}}';
        }
        else {
            $replyClass = '\fpcm\view\helper\radiobutton';
            $optionName =  'reply{{$pollid}}';
        }

        $options = [];

        /* @var $reply poll_reply */
        foreach ($this->poll->getReplies() as $reply) {
            $options[$reply->getId()] = $tpl['voteform_line.html'];
            
            $optObj = ( new $replyClass(str_replace(
                    ['{{$pollid}}', '{{$replyId}}'],
                    [$reply->getPollid(), $reply->getId()],
                    $optionName)
            ) )
            ->setValue($reply->getId())
            ->setClass('fpcm-polls-poll-options fpcm-polls-poll'.$reply->getPollid().'-option');
            
            $this->replaceTags($options[$reply->getId()], [
                '{{poll_reply_option}}' => $optObj,
                '{{poll_reply_text}}' => $reply->getText()
            ]);
        }

        return $tpl['voteform_header.html'].PHP_EOL.implode(PHP_EOL, $options).$tpl['voteform_footer.html'];
    }

    public function getResultForm($pollBtn = false) {
        
        $tpl = $this->getTemplates('result');

        $this->replaceTags($tpl['result_header.html'], [
            '{{poll_text}}' => $this->poll->getText()
        ]);

        $pollBtnObj = (string) ( new \fpcm\view\helper\button('pollform'.$this->poll->getId()) )
                        ->setClass('fpcm-polls-poll-form')
                        ->setData(['pollid' => $this->poll->getId()])
                        ->setText($this->addLangVarPrefix('GUI_PUB_SHOWPOLL'));

        $this->parseCondition($tpl['result_footer.html'], $pollBtn ? '$2' : '', '\$hasVoted');

        $this->replaceTags($tpl['result_footer.html'], [
            '{{poll_result_votesum}}' => $this->poll->getVotessum(),
            '{{poll_button_tovote}}' => $pollBtn ? $pollBtnObj : '',
        ]);

        $options = [];

        /* @var $reply poll_reply */
        foreach ($this->poll->getReplies(false, ['votes DESC']) as $reply) {
            $options[$reply->getId()] = $tpl['result_line.html'];

            $percent = $reply->getPercentage($this->poll->getVotessum());

            $this->replaceTags($options[$reply->getId()], [
                '{{poll_reply_text}}' => $reply->getText(),
                '{{poll_result_count}}' => $reply->getVotes(),
                '{{poll_result_percent}}' => $percent,
                '{{poll_result_percent_div}}' => "<div class=\"fpcm-polls-poll-bar\" style=\"width:{$percent}%;\"></div>",
            ]);
        }

        return $tpl['result_header.html'].PHP_EOL.implode(PHP_EOL, $options).$tpl['result_footer.html'];
    }

    public function getArchiveForm() {

        $tpl = $this->getTemplates('archive');

        $this->replaceTags($tpl['archive_header.html'], [
            '{{poll_text}}' => $this->poll->getText()
        ]);

        $this->replaceTags($tpl['archive_footer.html'], [
            '{{poll_archive_votesum}}' => $this->poll->getVotessum(),
            '{{poll_start}}' => new \fpcm\view\helper\dateText($this->poll->getStarttime(), 'd.m.Y'),
            '{{poll_stop}}' => new \fpcm\view\helper\dateText($this->poll->getStoptime(), 'd.m.Y')
        ]);

        $options = [];

        /* @var $reply poll_reply */
        foreach ($this->poll->getReplies() as $reply) {

            $options[$reply->getId()] = $tpl['archive_line.html'];

            $percent = $reply->getPercentage($this->poll->getVotessum());

            $this->replaceTags($options[$reply->getId()], [
                '{{poll_reply_text}}' => $reply->getText(),
                '{{poll_archive_count}}' => $reply->getVotes(),
                '{{poll_archive_percent}}' => $percent,
                '{{poll_archive_percent_div}}' => "<div class=\"fpcm-polls-poll-bar\" style=\"width:{$percent}%;\"></div>",
            ]);
        }

        return $tpl['archive_header.html'].PHP_EOL.implode(PHP_EOL, $options).$tpl['archive_footer.html'];
    }

}
