<?php

namespace Azub\Database\Tables;

use Azub\Database\Db;
use Azub\Database\Table;

/**
 * Класс таблицы городов.
 * @package geobyazub\Azub\Database\Tables
 */
class City extends Table {

    /**
     * Название таблицы городов в БД.
     */
    const TABLE_NAME = 'mod_geobyazub_cities';

    /**
     * @var string Название города
     */
    public $name;
    /**
     * @var mixed Внешний ключ записи регионов
     */
    public $region_id;

    /**
     * @var string[] Используемые поля таблицы
     */
    public $fields = [
        'name',
        'region_id',
    ];

    /**
     * Получает название свазаного региона
     * @return string Название региона
     */
    public function getRegionName(){
        if (empty($this->region_id))
            return '';

        $r = Region::find($this->region_id);
        if (empty($r))
            return '';

        return $r->name;
    }

    /**
     * Возвращает sql код для отображения списка городов
     * @return string sql код без подставляемых значений
     */
    public static function getListSql(){
        $select = Db::select()
            ->from(
                ['a' => City::TABLE_NAME],
                ['id', 'name']
            )
            ->joinLeft(
                ['b' => Region::TABLE_NAME],
                'a.region_id = b.id',
                ['name as region_name']
            )
            ->where('a.id is not null /*ADD_SEARCH*/')  // костыль для поиска
            ->order(['b.name', 'a.name']);

        $sql = $select->__toString();

        return $sql;
    }
}
