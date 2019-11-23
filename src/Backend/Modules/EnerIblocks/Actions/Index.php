<?php

namespace Backend\Modules\EnerIblocks\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Locale;
use Backend\Modules\EnerIblocks\Domain\Categorys\CategoryDataGrid;
use Backend\Modules\EnerIblocks\Domain\Categorys\Category;

/**
 * This is the index-action (default), it will display the overview
 */
class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->loadFiles();
        $this->loadIblocks();
        $this->loadData();
        // $this->template->assign('dataGrid', CategoryDataGrid::getHtml(Locale::workingLocale()));
        $this->parse();
        $this->display();
    }

    //TODO:добавить перевод
    private function loadData(){
        if (empty($this->category)) {
            $this->template->assign('tree', 'Пусто, создайте Тип инфоблока');
        }else{
            $this->prepareData();
        }
    }

    private function loadIblocks(): void
    {
        $this->category = $this->get('doctrine')->getRepository(Category::class)->getAllCategory();
        var_dump($this->category);
        // die;
    }

    private function loadFiles(): void
    {
        $this->header->addCSS('treegrid.css', 'EnerIblocks', false);
        $this->header->addJS('myjs.js', 'EnerIblocks', false);
        $this->header->addJS('jquery.cookie.js', 'EnerIblocks', false);
        $this->header->addJS('jquery.treegrid.js', 'EnerIblocks', false);
    }

    private function prepareData(): void
    {
        $html_tree = '';
        $html_tree .= '<table class="tree">';
        foreach ($this->category as $key => $value) {
            $root = $value['id'];
            $parent = $value['parent'] != 0 ? 'treegrid-parent-'.$value['parent'] : '';
            $html_tree .= '<tr class="treegrid-'.$root.' '.$parent.'">
            <td>'.$value['title'].'</td><td>Additional info</td>
          </tr>';
        }
        $html_tree .= '</table>';

      $this->template->assign('tree', $html_tree);
    }


}
