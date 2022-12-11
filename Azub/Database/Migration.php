<?php 

namespace Azub\Database;

/**
 * Абстрактный класс миграции для создания и удаления таблицы
 * @package geobyazub\Azub\Database
 */
abstract class Migration
{

    /**
     *  Выполняет процессы установки
     * @throws \Exception
     * @return void
     */
    public function up(){

    }

    /**
     *  Выполняет процессы удаления
     * @throws \Exception
     * @return void
     */
    public function down(){
        if(!empty(static::TABLE_NAME)){
            try {               
                Db::query('DROP TABLE IF EXISTS `' . static::TABLE_NAME. '`');
            } catch (\Exception $e) {
                throw new \Exception("geoby ".static::TABLE_NAME." table uninstall failed: ".$e->getMessage().' code: '.$e->getCode());
            }
        }
    }
}

