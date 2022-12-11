<?php

namespace Azub\View;

use Core2\View\EditTable;

/**
 * Класс формы редактирования, обновления и просмотра записи Города
 *
 * @package geobyazub\Azub\View
 */
class CityForm extends EditTable {
    /**
     * Конструктор.
     * Инициализация и установка параметров
     *
     * @param string[] $data Данные формы по умолчанию
     * @param string $module_id Идентификатор модуля
     */
    public function __construct($data = null, $module_id = null){
        if (empty($module_id)) {
            $module_id = \Azub\Module::MODULE_ID;
        }

        /*
            модуль не позволяет создавать произвольное имя. перегрузка невозможна изза private переменных.
            зачем???
            если указать другое значение то вшитая в конструктор проверка acl будет искать несуществующие права.
        */
//		parent::__construct($module_id.'_cityform');
        parent::__construct($module_id);

        // Необходимое поле название
        $this->addControl($this->_("Название"), "TEXT", " style=\"min-width:385px\"", "", "", true);
        // Выподающий список регионов
        $this->addControl($this->_("Регион"), "LIST", " style=\"min-width:385px\"", "", "");

        $url = 'index.php#module=' . $module_id;
        $this->back = $url;

        $this->addButton($this->_("Вернуться к списку"), "load('" . $url . "')");

        if (empty($data)) {
            $data = [
                'id' => null,
                'name' => null,
                'region_id' => null
            ];
            // добавляет ссылку на действие "сохранить"
            $this->save("xajax_createGeobyCity(xajax.getFormValues(this.id))");
        } else {
            $this->save("xajax_updateGeobyCity(xajax.getFormValues(this.id))");
        }
        $this->SQL = [$data];
    }

    /**
     * Установить возможные значения выпадающего списка регионов
     * @param string[] $region_options массив ключ-значение для выподающего списка регионов
     *
     * @return $this
     */
    public function setRegionOptions($region_options){
        $this->selectSQL[] = $region_options;

        return $this;
    }
}

