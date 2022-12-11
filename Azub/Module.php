<?php

namespace Azub;

use Azub\Database\Db;
use Azub\Database\Tables\City;
use Azub\Database\Tables\Region;

/**
 * Класс Module для управления БД.
 *
 * Производит операции установки, загрузки начальных данных и удаления бд.
 *
 * @package geobyazub\Azub
 */
class Module {
    /**
     * Код модуля в БД, директориях и компонентах core2
     */
    const MODULE_ID = 'geobyazub';

    /**
     * Главный метод процесса установки.
     * В случае создания всех таблиц наполняет их начальными данными
     * @return void
     * @throws \Exception
     */
    public function install(){
        if ($this->migrate()) {
            $this->seed();
        }
    }

    /**
     * Создаёт таблицы из списка миграции при их отсутствии в бд.
     *
     * @return bool true в случае установки всех таблиц
     */
    public function migrate(){
        $migrations = $this->getMigrationClasses();
        $up_count = 0;

        // получение класса подключения
        $db = Db::getInstance();

        foreach ($migrations as $class_name) {

            $migration = new $class_name;
            if ($db->tableExists($migration::TABLE_NAME) == false) {
                $migration->up();
                $up_count++;
            }
        }

        return $up_count == count($migrations);
    }

    /**
     * Возвращает список имен классов миграций с запросами для создания
     * @return string[] Список имен классов миграций с запросами для создания
     */
    public function getMigrationClasses(){
        return [
            \Azub\Database\Migrations\City::class,
            \Azub\Database\Migrations\Region::class,
        ];
    }

    /**
     * Наполняет бд тестовыми данными из файла cities.json
     * @return void
     * @throws \Zend_Db_Statement_Exception
     */
    public function seed(){
        $file = geobyazub_data_path('cities.json');
        if (!file_exists($file)) {
            throw new \Exception("geoby seed file cities not found: " . $file);
        }
        $data = json_decode(file_get_contents($file), true);
        $regions = $data[0]['regions'];

        foreach ($regions as $region) {
            $_region = Region::firstOrCreate(['name' => $region['name']]);

            foreach ($region['cities'] as $city) {
                City::firstOrCreate(['name' => $city['name'], 'region_id' => $_region->id]);
            }
        }
    }

    /**
     * Выполняет процесс удаления созданных таблиц.
     * В основном только для разработки. Core2 не содержит возможности перехвата события удаления модуля.
     * @return void
     */
    public function uninstall(){
        $migrations = $this->getMigrationClasses();
        foreach ($migrations as $class_name) {
            $migration = new $class_name;
            $migration->down();
        }
    }

}