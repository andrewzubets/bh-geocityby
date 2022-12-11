<?php

namespace Azub;

use Core2\Common;

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
        if(isset($_GET['update']))
            return $this->update($_GET['update']);
        if(isset($_GET['create']))
            return $this->create();

        return $this->index();
    }
}