<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Backend\Core\Language\Language as BL;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;

/**
 * Выполняет получение сведений о компании с указанным идентификатором.
 */
class GetCompany extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        $oii_id = $this->getRequest()->get('oii_id', '');
        $company = BackendImscoiiModel::getObjectById((int) $oii_id, BL::getWorkingLanguage());
        $result = [
            'name' => (count($company) > 0 && isset($company['name'])) ? $company['name'] : '',
            'innoObjectTypeId' => (count($company) > 0 && isset($company['innoObjectTypeID'])) ? $company['innoObjectTypeID'] : '0'
        ];

        $this->output(Response::HTTP_OK, $result);
    }
}
