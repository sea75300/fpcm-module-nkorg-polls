<?php

namespace fpcm\modules\nkorg\polls\controller;

final class deleteentry extends \fpcm\controller\abstracts\module\ajaxController {

    public function request()
    {
        $id = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$id) {
            $this->response->setReturnData(0)->fetch();
        }

        $this->response->setReturnData( (new \fpcm\modules\nkorg\polls\models\counter())->deleteLinkEntry($id) ? 1 : 0 )->fetch();
    }

}
