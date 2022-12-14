<?php

namespace Azub;

use Core2\Common;
use Core2\View\Alert;

/**
 * Класс основы контроллеров
 *
 * Осуществляет маршрутизацию запросов
 *
 * @package geobyazub\Azub
 */
abstract class BaseController extends Common
{
    /**
     * Получить идентификатор текущего модуля
     * @return string идентификатор
     */
    public function current_module_id(){
        return $this->module;
    }

    /**
     * Главный метод вызываемый ядром core2 при обработке запросов модуля.
     * Генерирует html страницы ниже меню
     * @return void
     */
    public function action_index(){
        try {

            if (isset($_GET['update']))
                return $this->update($_GET['update']);
            if (isset($_GET['create']))
                return $this->create();

            return $this->index();
        }
        catch (\Exception $ex){
            // log or save somewhere ???
            return view_simple_panel('Ошибка', Alert::danger('Ошибка выполнения'));

        }

    }
}