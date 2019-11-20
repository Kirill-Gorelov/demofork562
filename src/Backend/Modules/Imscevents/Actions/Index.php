<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\DataGridDatabase as BackendDataGridDatabase;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Imscoii\Engine\Model as BackendImscoiiModel;
use \Datetime;

class Index extends BackendBaseActionIndex
{
    /**
     * Filter variables.
     *
     * @var array
     */
    private $filter;

    /**
     * Form.
     *
     * @var BackendForm
     */
    private $form;

    /** @var array Типы объектов */
    private $innoObjectTypes;

    public function execute(): void
    {

        parent::execute();
        $this->setFilter();
        $this->loadForm();        
        $this->loadDataGrid();
        $this->parse();
        $this->display();

    }



    private function buildQuery($lang = 'ru'): array
    {
        $parameters = [];

        /*
         * Start query, as you can see this query is build in the wrong place, because of the
         * filter it is a special case where in we allow the query to be in the action file itself
         */
        $query =
            'SELECT id, title, date, enddate, dnpp_id, ict_id, active, inno_object_type_id, oii_id, oii_name
             FROM imscevents 
             WHERE lang = ?';
        $parameters[] = $lang;

        if (!empty($this->filter['active'])){
          if($this->filter['active'] == 1){
            $query .= ' AND active = 1';
          }elseif($this->filter['active'] == 2){
            $query .= ' AND active = 0';  
          }
        }
        if (!empty($this->filter['main'])) {
          if($this->filter['main'] == 1){
            $query .= ' AND main = 1';
          }elseif($this->filter['main'] == 2){
            $query .= ' AND main = 0';
          } elseif ($this->filter['main'] == 3) {
              $query .= ' AND COALESCE(dnpp_id, 0) <> 0';
          } elseif ($this->filter['main'] == 4) {
              $query .= " AND COALESCE(ict_id, '') <> ''";
          }
        }

        $hidden = !empty($this->filter['hidden']) ? intval($this->filter['hidden']) : 0;
        if ($hidden == 0) {
            $query .= ' AND hidden = 0';
        }

        if (!empty($this->filter['in_all'])) {
          if($this->filter['in_all'] == 1){  
            $query .= ' AND in_all = 1';
          }elseif($this->filter['in_all'] == 2){
            $query .= ' AND in_all = 0';            
          }
        }

        if (!empty($this->filter['innoObjectTypeId']) && $this->filter['innoObjectTypeId'] != 0) {
            $query .= ' AND inno_object_type_id = ' . $this->filter['innoObjectTypeId'];
        }

        if ($this->filter['oii_id'] == '0' || !empty($this->filter['oii_id'])) {
          $oii_id = intval($this->filter['oii_id']);
          $query .= ' AND oii_id = ?';
          $parameters[] = $oii_id;
        }

        if ($this->filter['dnpp_id'] == '0' || !empty($this->filter['dnpp_id'])) {
          $dnpp_id = intval($this->filter['dnpp_id']);          
          $query .= ' AND dnpp_id = ?';
          $parameters[] = $dnpp_id;          
        }

        if (!empty($this->filter['ict_id'])) {
            $query .= ' AND ict_id = ?';
            $parameters[] = $this->filter['ict_id'];
        }

        if (!empty($this->filter['date_from'])) {
            $query .= ' AND date >= ?';
            $parameters[] = $this->filter['date_from']->format('Y-m-d H:i:s');
        }

        if (!empty($this->filter['date_to'])) {
            $query .= ' AND date <= ?';
            $parameters[] = $this->filter['date_to']->format('Y-m-d H:i:s');
        }

        if (!empty($this->filter['title_filter'])) {
            $query .= " AND title LIKE '%" . $this->filter['title_filter'] . "%'";
        }
        // query

        //$query .= ' ORDER by date DESC';

        return [$query, $parameters];
    }

    private function loadForm(): void
    {
        // create form
        $this->form = new BackendForm('filter', BackendModel::createUrlForAction(), 'get');

        // add fields
        $this->form->addDate('date_from', $this->filter['date_from'] != '' ? $this->filter['date_from']->getTimestamp () : '', null, null, null,'form-control danger date_from', null, true);

        $this->form->addDate('date_to', $this->filter['date_to'] != '' ? $this->filter['date_to']->getTimestamp () : '', null, null, null,'form-control danger date_to', null, true);

        // Активность
        $active_arr = ['0' => 'все','1' => 'активные', '2' => 'неактивные'];
        $this->form->addDropdown('active', $active_arr, $this->filter['active']);

        // Тип мероприятия
        $main_arr = [
            '0' => 'все',
            '1' => 'главные',
            '2' => 'неглавные',
            '3' => 'мероприятие ИАС',
            '4' => 'мероприятие ICT'
        ];
        $this->form->addDropdown('main', $main_arr, $this->filter['main']);

        // Видимость
        $hiddenValues = ['0' => 'не скрытые', '1' => 'все'];
        $this->form->addDropdown('hidden', $hiddenValues, $this->filter['hidden']);

        // В общих списках
        $in_all_arr = ['0' => 'все','1' => 'активные', '2' => 'неактивные'];
        $this->form->addDropdown('in_all', $in_all_arr, $this->filter['in_all']);

        // Тип инфраструктуры
        $this->innoObjectTypes = BackendImscoiiModel::getInnoObjectTypesFilter(BL::getWorkingLanguage());
        $this->form->addDropdown('innoObjectTypeId', $this->innoObjectTypes, $this->filter['innoObjectTypeId']);

        // Привязанный объект
        $this->form->addText('oii_id', $this->filter['oii_id']);

        // Название компании
        $this->form->addText('oii_name', $this->filter['oii_name']);

        // id в ДНПП
        $this->form->addText('dnpp_id', $this->filter['dnpp_id']);

        // id в ДИТ
        $this->form->addText('ict_id', $this->filter['ict_id']);

        // Заголовок новости
        $this->form->addText('title_filter', $this->filter['title_filter']);

        // manually parse fields
        $this->form->parse($this->template);
    }

   /**
     * Sets the filter based on the $_GET array.
     */
    private function setFilter(): void
    {
        $this->filter['active'] = $this->getRequest()->query->get('active');
        $this->filter['main'] = $this->getRequest()->query->getInt('main');
        $this->filter['hidden'] = $this->getRequest()->query->getInt('hidden');
        $this->filter['in_all'] = $this->getRequest()->query->get('in_all');
        $this->filter['innoObjectTypeId'] = $this->getRequest()->query->get('innoObjectTypeId');
        $this->filter['oii_id'] = $this->getRequest()->query->get('oii_id');
        $this->filter['oii_name'] = $this->getRequest()->query->get('oii_name');
        $this->filter['dnpp_id'] = $this->getRequest()->query->get('dnpp_id');
        $this->filter['ict_id'] = $this->getRequest()->query->get('ict_id');
        $this->filter['title_filter'] = $this->getRequest()->query->get('title_filter');
        $this->filter['show_filters'] = $this->getRequest()->query->get('show_filters', 0);

        $dateFrom = !empty($this->getRequest()->query->get('date_from')) ? DateTime::createFromFormat('d/m/Y H:i:s',$this->getRequest()->query->get('date_from').' 00:00:00') : '';

        $dateTo = !empty($this->getRequest()->query->get('date_to')) ? DateTime::createFromFormat('d/m/Y H:i:s',$this->getRequest()->query->get('date_to').' 23:59:59') : '';

        if($dateFrom != '' && $dateTo != '' && $dateTo < $dateFrom) {
            [$dateFrom, $dateTo ] = [$dateTo,$dateFrom];
        }

        $this->filter['date_from'] = $dateFrom;
        $this->filter['date_to'] = $dateTo;

    }

    /*
     * Датагридим список мероприятий
     */
    private function loadDataGrid(): void
    {
        /*
        if(BL::getWorkingLanguage() == 'ru'){
            $this->dataGrid = new BackendDataGridDatabase(
                BackendImscneventsDatabase::EVENTS_DATAGRID_QUERY
            );
        } elseif(BL::getWorkingLanguage() == 'en') {
            $this->dataGrid = new BackendDataGridDatabase(
                BackendImscneventsDatabase::EVENTS_DATAGRID_QUERY_EN
            );
        }
        */
        if(BL::getWorkingLanguage() == 'ru'){
          $lang = 'ru';
        } elseif(BL::getWorkingLanguage() == 'en') { 
          $lang = 'en';
        } 

        // fetch query and parameters
        list($query, $parameters) = $this->buildQuery($lang);        
        
        $this->dataGrid = new BackendDataGridDatabase($query, $parameters);

        /*
         * Пагинация
         */
        $this->dataGrid->setPaging(true);
        $this->dataGrid->setPagingLimit(20);

        /*
         * Добавляем колонку с кнопкой редактирования
         */
        $this->dataGrid->addColumn(
            'edit',
            null,
            BL::lbl('Edit'),
            BackendModel::createUrlForAction('Editevent') . '&amp;id=[id]',
            BL::lbl('Edit')
        );

        $this->dataGrid->setColumnFunction([$this, 'getInnoObjectTypeNameById'],
            '[inno_object_type_id]', 'inno_object_type_id', true);

        /*
         * Свои названия колонок
         */
        $this->dataGrid->setHeaderLabels(array(
            'title' => 'Заголовок',
            'date' => 'Дата начала',
            'enddate' => 'Дата окончания',
            'dnpp_id' => 'id в ДНПП',
            'ict_id' => 'id в ДИТ',
            'inno_object_type_id' => 'Тип инфраструктуры',
            'oii_id' => 'ID привязанного объекта',
            'oii_name' => 'Название компании'
        ));

        // Сортировка колонок
        $this->dataGrid->setSortingColumns(['title', 'date', 'enddate'], 'date');
        $this->dataGrid->setSortParameter('desc');
    }

    /**
     * Вовзвращает название типа объекта по идентификатору типа.
     * @param int $innoObjectTypeId Идентификатор типа объекта.
     * @return string Название типа объекта.
     */
    public function getInnoObjectTypeNameById($innoObjectTypeId): string
    {
        return ($innoObjectTypeId != '' && $innoObjectTypeId != 0 && isset($this->innoObjectTypes[$innoObjectTypeId]))
            ? $this->innoObjectTypes[$innoObjectTypeId] : '';
    }

    protected function parse(): void
    {
        parent::parse();
        $this->template->assign('dataGrid', $this->dataGrid->getContent());
        $this->template->assign('lang', BL::getWorkingLanguage());
        $this->template->assign('showFilters', $this->filter['show_filters'] == 1 ? 'true' : 'false');

        // parse filter
        $this->template->assignArray($this->filter);        
    }
}
