<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $poll fpcm\modules\nkorg\polls\models\poll */ ?>
<?php /* @var $reply fpcm\modules\nkorg\polls\models\poll_reply */ ?>            
<?php foreach ($replies as $idx => $reply) : ?>
<div class="row py-2 mx-1 fpcm-ui-nkorgpolls-replyline row-cols-3" id="fpcm-nkorgpolls-reply-<?php print $idx; ?>">
    <?php $theView->hiddenInput("polldata[ids][]", "polldataids{$idx}")->setValue($reply->getId()); ?>

    <?php if ($poll->getId()) : ?>
    <div class="col-auto align-self-center mb-3">
        <span class="badge fs-1 shadow border border-light border-opacity-50"style="background-color: <?php print $reply->getColor(); ?>">
            <span class="visually-hidden"><?php print $reply->getText(); ?></span>
        </span>            
    </div>
    <?php endif; ?>        

    <div class="col align-self-center">
        <?php $theView
                ->textInput("polldata[replies][]", "polldatareplies{$idx}")
                ->setText('MODULE_NKORGPOLLS_GUI_POLL_REPLY_TXT', [
                    'id' => ($idx + 1)
                ])
                ->setValue($reply->getText()); ?>

    </div>

    <?php if ($poll->getId()) : ?>
    <div class="col align-self-center">
        <?php $theView
                ->textInput("polldata[sums][]", "polldatareplies{$idx}")
                ->setText('')
                ->setValue($reply->getVotes())
                ->setType('number'); ?>
    </div>        
    <?php endif; ?>

    <div class="col-auto align-self-center">
        <?php $theView->deleteButton('removeReply1')->setClass('fpcm-ui-nkorgpolls-removereply')->setData(['idx' => $idx ]); ?>
    </div>

</div>
<?php endforeach;  ?>