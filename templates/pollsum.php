<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $poll fpcm\modules\nkorg\polls\models\poll */ ?>
<?php /* @var $reply fpcm\modules\nkorg\polls\models\poll_reply */ ?>
<div class="row py-2 align-self-center align-content-center justify-content-center">
    <div class="col-12 col-md-9 col-lg-6">
        <h3 class="mb-3"><?php print $theView->escapeVal($poll->getText()); ?></h3>

        <canvas id="fpcm-nkorg-polls-chart"></canvas>
    </div>
</div>