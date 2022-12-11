<?php

namespace Azub\Database\Migrations;

use Azub\Database\Db;
use Azub\Database\Migration;

/**
 * Класс миграции таблицы города.
 * Используется при установке модуля.
 *
 * @package geobyazub\Azub\Database\Migrations
 */
class City extends Migration {
    /**
     * Название таблицы в БД.
     */
    const TABLE_NAME = 'mod_geobyazub_cities';

    /**
     * Создаёт таблицу городов в БД.
     * Поля name - имя, region_id - id региона.
     *
     * @throws \Exception
     */
    public function up(){

        try {
            Db::query(
                'CREATE TABLE `' . self::TABLE_NAME . '` ( '
                . '`id` int(11) unsigned NOT NULL AUTO_INCREMENT, '
                . '`name` varchar(255) NULL DEFAULT NULL, '
                . '`region_id` int(11) NULL DEFAULT NULL, '
                . 'PRIMARY KEY (`id`), '
                . 'UNIQUE KEY `name` (`name`) '
                . ') ENGINE=InnoDB DEFAULT CHARSET=utf8'
            );
        } catch (\Exception $e) {
            throw new \Exception("geoby '" . self::TABLE_NAME . "' table install failed: " . $e->getMessage() . ' code: ' . $e->getCode());
        }
    }
}

