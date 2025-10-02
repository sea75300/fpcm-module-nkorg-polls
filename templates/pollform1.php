<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $poll fpcm\modules\nkorg\polls\models\poll */ ?>
<?php /* @var $reply fpcm\modules\nkorg\polls\models\poll_reply */ ?>
<div class="row py-2">
    <?php $theView
            ->textInput("polldata[text]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_TEXT')
            ->setSize(255)
            ->setValue($poll->getText()); ?>
</div>
<div class="row py-2">
    <?php $theView
            ->textInput("polldata[maxaw]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_MAXVOTES')
            ->setType('number')
            ->setValue($poll->getMaxreplies()); ?>
</div>
<div class="row py-2">
    <?php $theView
            ->dateTimeInput("polldata[starttime]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_START')
            ->setWrapper(false)
            ->setValue($theView->dateText($poll->getStarttime(), 'Y-m-d')); ?>
</div>
<div class="row py-2">
    <?php $theView
            ->dateTimeInput("polldata[stoptime]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_STOP')
            ->setValue($poll->getStoptime() ? $theView->dateText($poll->getStoptime(), 'Y-m-d') : ''); ?>
</div>
<div class="row py-2">
    <?php $theView
            ->textInput("polldata[voteexpiration]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_COOKIE')
            ->setValue($poll->getVoteExpiration())
            ->setType('number'); ?>
</div>
<?php if ($poll->getId()) : ?>
<div class="row py-2">
    <?php $theView
            ->textInput("polldata[votessum]")
            ->setText('MODULE_NKORGPOLLS_GUI_POLL_VOTES')
            ->setValue($poll->getVotessum())
            ->setType('number'); ?>
</div>
<div class="row py-2">
    <?php $theView->boolSelect("polldata[closed]")->setText('MODULE_NKORGPOLLS_GUI_POLL_ISCLOSED')->setSelected($poll->getIsclosed()); ?>
</div>
<div class="row py-2">
    <?php $theView->boolSelect("polldata[inarchive]")->setText('MODULE_NKORGPOLLS_GUI_POLL_INARCHIVE')->setSelected($poll->getShowarchive()); ?>
</div>
<?php endif; ?>