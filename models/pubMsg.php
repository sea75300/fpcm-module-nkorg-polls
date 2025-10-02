<?php

namespace fpcm\modules\nkorg\polls\models;

class pubMsg extends \fpcm\model\abstracts\staticModel implements \JsonSerializable {

    use \fpcm\module\tools;
    
    private $code;

    private $msg;

    private $html;

    /**
     * 
     * @param int $code
     * @param string $msg
     */
    function __construct($code = 0, $msg = '', $html = '')
    {
        parent::__construct();
        $this->code = $code;
        $this->msg = ( trim($msg) ? $msg : $this->language->translate($this->addLangVarPrefix('MSG_PUB_ERRCODE_GEN')) );
        $this->html = $html;
    }


    /**
     * JSON data
     * @return array
     * @ignore
     */
    public function jsonSerialize() : array
    {
        return [
            'code' => $this->code,
            'msg' => $this->msg,
            'html' => $this->html
        ];
    }

}
