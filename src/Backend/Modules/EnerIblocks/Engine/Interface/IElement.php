<?php 
// namespace Backend\Modules\EnerIblocks\Engine\Interface;

interface IElement {
    public function getList($sort = [], $filter = []);
    public function getById(int $id);
}

?>