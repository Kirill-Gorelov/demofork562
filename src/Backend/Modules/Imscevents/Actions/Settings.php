<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DataGridDatabase as BackendDataGridDatabase;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Backend\Core\Language\Language as BL;

/**
 * Class DnppIndex Таблица со списком адресов для получения данных из ДНПП
 * @package Backend\Modules\Imscnews\Actions
 */
class Settings extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();

        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    /**
     * Формирование таблицы со списком команд ДНПП
     */
    private function loadDataGrid(): void
    {
        $this->dataGrid = new BackendDataGridDatabase(BackendImsceventsModel::DNPP_COMMANDS_DATAGRID_QUERY,
            [BL::getWorkingLanguage()]);

        $this->dataGrid->setHeaderLabels(['last_dnpp_id' => 'Последнее загруженное мероприятие']);
        $this->dataGrid->setHeaderLabels(['gate_id' => 'Шлюз']);
        $this->dataGrid->addColumn('edit', null, BL::lbl('Edit'),
            BackendModel::createUrlForAction('EditDnpp') . '&amp;id=[id]', BL::lbl('Edit'));
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('dataGrid', $this->dataGrid->getContent());
    }
}
