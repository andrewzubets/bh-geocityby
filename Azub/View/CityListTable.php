<?php

namespace Azub\View;

use Core2\View\ListTable;

/**
 * Класс таблицы городов
 * Генерирует форму поиска, кнопок действий и таблицы со списком городов.
 *
 * @package geobyazub\Azub\View
 */
class CityListTable extends ListTable {
    /**
     * Конструктор.
     * Инициализация и установка параметров
     *
     * @param string $module_id Идентификатор модуля
     */
    public function __construct($module_id = null){
        if (empty($module_id)) {
            $module_id = \Azub\Module::MODULE_ID;
        }
        parent::__construct($module_id . '_citylist');


//        В родительском конструкторе передается название
//        и присваевается таблице на фронтенде.
//        То же название используется как идентификатор раздела доступа acl
//        что приводит к отказу доступа всем кроме администратора.
//        Чтобы это исправить нужно снова указать переменной resource это значение...
        $this->resource = $module_id;

        $url = 'index.php#module=' . $module_id;

        // ссылки добавления и редактирования
        $this->editURL = $url . "&update=TCOL_00";
        $this->addURL = $url . "&create";

        // поля поиска
        $this->addSearch($this->_("Название"), "a.name", "TEXT");
        $this->addSearch($this->_("Регион"), "a.region_id", "LIST");

        // столбцы таблицы
        $this->addColumn($this->_("Название"), "100", "TEXT", "name");
        $this->addColumn($this->_("Регион"), "100", "TEXT", "region_name");

        // стили ячеек
        $this->paintColor = "fafafa";
        $this->fontColor = "silver";

        // кнопка удаления
        /* Требует название таблицы и передаёт его на фронтенд
         * */
        $this->deleteKey = \Azub\Database\Tables\City::TABLE_NAME . ".id";
    }

    /**
     * @param $sql
     *
     * @return $this
     */
    public function setQuerySql($sql){
        $this->SQL = $sql;

        return $this;
    }

    /**
     * Установить возможные значения выпадающего списка регионов
     * @param string[] $region_options массив ключ-значение для выподающего списка регионов
     *
     * @return $this
     */
    public function setRegionOptions($region_options){
        $this->sqlSearch[] = $region_options;

        return $this;
    }

    /**
     * Сгенерировать html код со скриптами и стилями.
     * В случае закрытого доступа вернётся сообщение.
     * @return string html код
     */
    public function render(){
        if (!$this->checkAcl($this->resource, 'list_all')
            && !$this->checkAcl($this->resource, 'list_owner')) {
            return \Alert::danger("Доступ запрещён", 'Ошибка');
        }
        ob_start();
        $this->showTable();

        return ob_get_clean();
    }
}


?>