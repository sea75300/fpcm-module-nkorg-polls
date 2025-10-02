<?php

namespace fpcm\modules\nkorg\polls\models;

final class chartdraw {

    public static function draw(\fpcm\components\charts\chart &$chart, poll $poll)
    {
        $labels = [];
        $data = [];
        $colors = [];

        /* @var $reply \fpcm\modules\nkorg\polls\models\poll_reply */
        foreach ($poll->getReplies() as $reply) {
            
            $labels[] = $reply->getText().' ('.$reply->getPercentage($poll->getVotessum()).'%)';
            $data[] = $reply->getVotes();
            $colors[] = $reply->getColor();
        }
        
        $chart->setLabels($labels);
        $chart->setValues((new \fpcm\components\charts\chartItem($data, $colors))->setFill(true));
        return true;
    }

}
