<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Status Возвращает текуший статус обновления мероприятий из ДНПП шлюза
 * @package Backend\Modules\Imscnews\Ajax
 */
class Status extends BackendBaseAJAXAction
{
    public function execute(): void
    {
        parent::execute();

        // FIXME: Не использовать напрямую PHP функции управления сессиями
        session_write_close();

        $status = BackendImsceventsModel::getDnppSessionStatus();
        $this->output(Response::HTTP_OK, $status);
    }
}
