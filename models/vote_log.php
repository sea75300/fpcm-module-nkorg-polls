<?php

namespace fpcm\modules\nkorg\polls\models;

class vote_log extends dbObj implements \JsonSerializable {

    protected $table = 'module_nkorgpolls_vote_log';

    protected $pollid;
    protected $replyid;
    protected $replytime;
    protected $ip;

    public function getPollid() {
        return (int) $this->pollid;
    }

    public function getReplyid() {
        return (int) $this->replyid;
    }

    public function getReplytime() {
        return (int) $this->replytime;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setPollid(int $pollid) {
        $this->pollid = $pollid;
        return $this;
    }

    public function setReplyid(int $replyid) {
        $this->replyid = $replyid;
        return $this;
    }

    public function setReplytime(int $replytime) {
        $this->replytime = $replytime;
        return $this;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    public function jsonSerialize(): array {
        return [
            'replyid' => $this->getId(),
            'replytime' => (string) new \fpcm\view\helper\dateText($this->getReplytime()),
            'ip' => $this->getIp()
        ];
    }

}
