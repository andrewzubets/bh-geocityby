<?php

use Azub\View\CityForm;
use Azub\View\CityListTable as CityList;
use Azub\Database\Tables\City;
use Azub\Database\Tables\Region;

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

        $list = new CityList($this->current_module_id());
        // установить запрос списка
        $list->setQuerySql(City::getListSql());
        // указать список регионов для выподающего списка поиска
        $list->setRegionOptions(Region::getOptions(['' => '--']));

        return view_simple_panel($title, $list);
    }

    /**
     * Страница добавления города
     */
    public function create(){
        $title = $this->_("Города РБ, Добавление");

        $form = new CityForm([],$this->current_module_id());
        // указать список регионов для выподающего списка
        $form->setRegionOptions(Region::getOptions(['' => '--']));

        return view_simple_panel($title, $form);
    }

    /**
     * Страница изменения города
     * Отображает форму редактирования с загруженным городом из БД
     *
     * @param string $id идентификатор города
     */
    public function update($id){
        $title = $this->_("Города РБ, Редактирование");

        // извлечение города из БД
        $record = City::find($id);

        if (empty($record)) {
            // вернуть панель с ошибкой
            return view_simple_panel($title,
                \Alert::danger("Город не найден", 'Ошибка'));
        }

        // формирование расширенного заголовка с названием города и региона
        $title .= ': ';
        $title .= '"' . $record->name . '"';
        if (!empty($record->region_id))
            $title .= ' - "' . $record->getRegionName() . '"';

        $form = new CityForm($record->toArray(), $this->current_module_id());
        // указать список регионов для выподающего списка
        $form->setRegionOptions(Region::getOptions(['' => '--']));

        return view_simple_panel($title, $form);
    }

}
