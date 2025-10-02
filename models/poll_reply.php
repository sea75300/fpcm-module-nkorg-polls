<?php

namespace fpcm\modules\nkorg\polls\models;

class poll_reply extends dbObj {

    protected $table = 'module_nkorgpolls_polls_replies';

    protected $pollid = 0;
    protected $text = '';
    protected $votes = 0;
    protected $color = '';
    protected $createtime = 0;
    protected $createuser = 0;

    public function getPollid() {
        return (int) $this->pollid;
    }

    public function getText() {
        return $this->text;
    }

    public function getVotes() {
        return (int) $this->votes;
    }

    public function getColor(bool $force = false) {
        
        if ($this->color || $force) {
            return (string) $this->color;
        }
        
        return \fpcm\components\charts\chartItem::getRandomColor();
    }

    public function getCreatetime() {
        return (int) $this->createtime;
    }

    public function getCreateuser() {
        return (int) $this->createuser;
    }

    public function setPollid(int $pollid) {
        $this->pollid = $pollid;
        return $this;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function setVotes(int $votes) {
        $this->votes = $votes;
        return $this;
    }

    public function setColor(string $color = '') {
        
        if (!trim($color)) {
            $color = \fpcm\components\charts\chartItem::getRandomColor();
        }

        $this->color = $color;
        return $this;
    }

    public function setCreatetime(int $createtime) {
        $this->createtime = $createtime;
        return $this;
    }

    public function setCreateuser(int $createuser) {
        $this->createuser = $createuser;
        return $this;
    }
    
    public function getPercentage(int $max) {
        
        if (!$max) {
            return 0.00;
        }
        
        return number_format($this->getVotes() * 100 / $max, 2);;
    }

}
