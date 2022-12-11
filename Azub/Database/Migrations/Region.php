<?php

namespace Azub\Database\Migrations;

use Azub\Database\Db;
use Azub\Database\Migration;

/**
 * Класс миграции таблицы регионов.
 * Используется при установке модуля.
 * @package geobyazub\Azub\Database\Migrations
 */
class Region extends Migration {

    /**
     * Название таблицы в БД.
     */
    const TABLE_NAME = 'mod_geobyazub_regions';

    /**
     * Создаёт таблицу регионов в БД.
     * Поля name - имя.
     *
     * @throws \Exception
     */
    public function up(){

        try {

            Db::query(
                'CREATE TABLE `' . self::TABLE_NAME . '` ( '
                . '`id` int(11) unsigned NOT NULL AUTO_INCREMENT, '
                . '`name` varchar(255) NULL DEFAULT NULL, '
                . 'PRIMARY KEY (`id`), '
                . 'UNIQUE KEY `name` (`name`) '
                . ') ENGINE=InnoDB DEFAULT CHARSET=utf8'
            );
        } catch (\Exception $e) {
            throw new \Exception("geoby '" . self::TABLE_NAME . "' table install failed: " . $e->getMessage() . ' code: ' . $e->getCode());
        }
    }
}

