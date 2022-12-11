<?php

/**
 * Класс контроллера модуля.
 * Содержит обработчки запросов списка, создания и редактирования городов.
 *
 * @package geobyazub
 */
class ModGeobyazubController extends \Azub\BaseController {

    /**
     * Список городов
     */
    public function index(){

        $title = $this->_("Города РБ");

        $list = new \Azub\View\CityListTable($this->current_module_id());
        $list->setQuerySql(\Azub\Database\Tables\City::getListSql());
        $list->setRegionOptions(\Azub\Database\Tables\Region::getOptions(['' => '--']));

        return view_simple_panel($title, $list);
    }

    /**
     * Страница добавления города
     */
    public function create(){
        $title = $this->_("Города РБ, Добавление");

        $form = new \Azub\View\CityForm([],$this->current_module_id());
        $form->setRegionOptions(\Azub\Database\Tables\Region::getOptions(['' => '--']));

        return view_simple_panel($title, $form);
    }

    /**
     * Страница изменения города
     * @param $id
     *
     */
    public function update($id){
        $title = $this->_("Города РБ, Редактирование");

        $record = \Azub\Database\Tables\City::find($id);

        if (empty($record)) {
            return view_simple_panel($title,
                \Alert::danger("Город не найден", 'Ошибка'));
        }
        $title .= ': ';
        $title .= '"' . $record->name . '"';
        if (!empty($record->region_id))
            $title .= ' - "' . $record->getRegionName() . '"';

        $form = new \Azub\View\CityForm($record->toArray(), $this->current_module_id());
        $form->setRegionOptions(\Azub\Database\Tables\Region::getOptions(['' => '--']));

        return view_simple_panel($title, $form);
    }

}
