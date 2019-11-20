<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Backend\Core\Language\Language as BL;
use Symfony\Component\HttpFoundation\Response;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;

/**
 * Отбирает список компаний для автодополнения.
 */
class GetCompanyList extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        $term = $this->getRequest()->get('term', '');
        $innoObjectTypeId = $this->getRequest()->get('innoObjectTypeId', '');

        $result = BackendImscoiiModel::getObjectsByFilter($term, $innoObjectTypeId, BL::getWorkingLanguage());

        $this->output(Response::HTTP_OK, $result);
    }
}
