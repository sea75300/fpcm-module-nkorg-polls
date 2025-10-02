<?php /* @var $theView \fpcm\view\viewVars */ ?>
<script src="<?php print $pollJsFile; ?>"></script>
<?php if (isset($pollJsVars) && is_array($pollJsVars)) : ?><script>fpcm.modules.pollspub.vars = <?php print json_encode($pollJsVars); ?></script><?php endif; ?>

<div id="fpcm-poll-poll<?php print $pollId; ?>" class="fpcm-polls fpcm-polls-wrapper">
    <?php print $content; ?>
</div>