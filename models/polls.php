<?php

namespace fpcm\modules\nkorg\polls\models;

class polls extends \fpcm\model\abstracts\tablelist {

    protected $table = 'module_nkorgpolls_polls';

    public function getAllPolls(search $params)
    {
        if (isset($this->data[__FUNCTION__]) && $params->force !== true) {
            return $this->data[__FUNCTION__];
        }

        $cond = 'id > ? ';
        $values = [0];
        if ($params->isClosed !== null && $params->isClosed > -1) {
            $cond .= 'AND isclosed = ? ';
            $values[] = $params->isClosed;
        }

        return $this->getResultFromDB(__FUNCTION__, $cond.$this->dbcon->orderBy(['isclosed ASC', 'starttime DESC']), $values);
    }

    public function getLatestPoll(array $sort = [' starttime DESC'])
    {
        if (isset($this->data[__FUNCTION__])) {
            return $this->data[__FUNCTION__];
        }

        $params = (new \fpcm\model\dbal\selectParams($this->table))
                ->setItem('id')
                ->setWhere( 'isclosed = 0 AND (stoptime = 0 OR stoptime >= ?) '.$this->dbcon->orderBy(['starttime DESC']).' '.$this->dbcon->limitQuery(1, 0) )
                ->setParams([ time() ]);
        
        $result = $this->dbcon->selectFetch($params);
        return (int) ($result->id ?? 0);
    }

    public function getArchivedPolls($force = false, array $sort = ['starttime DESC'])
    {
        if (isset($this->data[__FUNCTION__]) && !$force) {
            return $this->data[__FUNCTION__];
        }

        $where = 'showarchive = 1 AND isclosed = 1 AND (stoptime > 0 OR stoptime <= ?)' . $this->dbcon->orderBy($sort);
        
        return $this->getResultFromDB(__FUNCTION__, $where, [
            time()
        ]);
    }

    private function getResultFromDB($cache, string $where, array $params = [])
    {
        $params = (new \fpcm\model\dbal\selectParams($this->table))
                ->setFetchAll(true)
                ->setWhere($where)
                ->setParams($params);
        
        $result = $this->dbcon->selectFetch($params);
        if (!is_array($result) || !count($result)) {
            return [];
        }

        foreach ($result as $data) {
            
            $obj = new poll();
            $obj->createFromDbObject($data);
            $this->data[$cache][$obj->getId()] = $obj;
        }

        return $this->data[$cache];
    }
    
    

}
