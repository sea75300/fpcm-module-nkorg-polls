<?php

namespace fpcm\modules\nkorg\polls\models;

/**
 * Search Wrapper class
 * @property bool $force Force db request
 * @property int $isClosed
 *  -1: All polls
 *   0: Active polls
 *   1: Closed polls
 */
class search extends \fpcm\model\abstracts\searchWrapper {

}
