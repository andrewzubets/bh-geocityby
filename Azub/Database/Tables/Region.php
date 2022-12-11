<?php

namespace Azub\Database\Tables;

use Azub\Database\Table;

/**
 * Класс таблицы регионов.
 * @package geobyazub\Azub\Database\Tables
 */
class Region extends Table {


    /**
     * Название таблицы регионов в БД.
     */
    const TABLE_NAME = 'mod_geobyazub_regions';

    /**
     * @var string Название региона
     */
    public $name;

    /**
     * @var string[] Используемые поля таблицы
     */
    public $fields = [
        'name',
    ];


}