<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAjaxAction;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;

class DeleteIctEvents extends BackendBaseAjaxAction
{
    public function execute(): void
    {
        parent::execute();

        BackendImsceventsModel::deleteIctEvents();

        $this->output(Response::HTTP_OK, null);
    }
}
