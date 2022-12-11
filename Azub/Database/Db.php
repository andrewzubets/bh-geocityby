<?php

namespace Azub\Database;

/**
 * Класс запросов к БД
 * Оборачивает класс работы с БД создаваемый core2. Добавляет функции
 * необходимые для удобной работы с таблицами
 *
 * @package geobyazub\Azub\Database
 */
class Db {
    /**
     * Конструктор
     * Может быть вызван только внутри
     */
    private function __construct(){

    }

    /**
     * @var \Core2\Db ссылка на класс ядра подключения к бд
     */
    private static $core2_db;
    /**
     * @var \Azub\Database\Db ссылка на текущий класс
     */
    private static $inst;

    /**
     * @var string[] массив таблиц бд
     */
    private $table_list;

    /**
     * Проверить наличие таблицы в БД
     *
     * @param string $name Название таблицы
     *
     * @return bool true если таблица есть в списке доступных
     */
    public function tableExists($name){
        if ($this->table_list == null) {
            $this->table_list = self::$core2_db->listTables();
        }

        return in_array($name, $this->table_list);
    }

    /**
     * Получить текущее подключение к БД
     *
     * @return \Core2\Db Класс подключения core2
     */
    private static function getConnection(){
        if (self::$core2_db == null) {
            $core_db = new \Core2\Db();
            self::$core2_db = $core_db->db;
        }

        return self::$core2_db;
    }

    /**
     * Получить текущий экземпляр класса БД
     * @return \Azub\Database\Db Экземляр класса БД
     */
    public static function getInstance(){
        if (self::$inst == null) {
            self::getConnection();
            self::$inst = new self();
        }

        return self::$inst;
    }


    /**
     * Определяет присутствует ли запись с id в таблице
     *
     * @param string $table Название таблиццы
     * @param int    $id    идентификатор записи
     *
     * @return boolean
     */
    public static function hasId($table, $id){
        // создание запроса
        $stm = self::$core2_db->query("select 1 from " . $table . " where id = ?", [$id]);

        // выполнение
        $stm->execute();

        // извлечение первого столбца
        $row = $stm->fetchColumn(0);

        return !empty($row);
    }

    /**
     * Подготавливает и выполняет запрос к БД
     *
     * @param mixed $sql    Sql код с возможными местами связанных значений.
     *                      Может быть обычной строкой или экземпляром \Zend_Db_Select.
     * @param mixed $bind   Массив связываемых значений.
     *
     * @return \Zend_Db_Statement_Interface
     */
    public static function query($sql, $bind = array()){
        return self::getConnection()->query($sql, $bind);
    }


    /**
     * Добавляет запись в бд и возвращает её id.
     *
     * @param mixed $table Название таблицы.
     * @param array $bind  Массив ключ-значений.
     *
     * @return int id добавленной записи
     * @throws \Zend_Db_Adapter_Exception
     */
    public static function insertRecord($table, $bind){
        $core2_db = self::getConnection();
        $core2_db->insert($table, $bind);

        return $core2_db->lastInsertId();
    }


    /**
     * Обновляет запись по id.
     *
     * @param mixed $table Название таблицы.
     * @param array $bind  Массив ключ-значений.
     * @param mixed $id Идентификатор записи id.
     *
     * @return int          Количество изменённых записей.
     * @throws \Zend_Db_Adapter_Exception
     */
    public static function updateRecordById($table, array $bind, $id){
        return self::getConnection()->update($table, $bind, [
            ' id = ? ' => $id
        ]);
    }

    /**
     * Удаляет таблицу по id.
     *
     * @param mixed $table Название таблицы.
     * @param int   $id    Идентификатор записи id.
     *
     * @return int          Количество удалённых записей.
     */
    public static function deleteRecordById($table, $id){
        return self::getConnection()->delete($table, [
            ' id = ? ' => $id
        ]);
    }

    /**
     * Создает и возвращет новый экземпляр \Zend_Db_Select для текущего подключения core2.
     *
     * @return \Zend_Db_Select
     */
    public static function select(){
        return self::getConnection()->select();
    }

    /**
     * Выводит сокращённую информацию при отладке.
     * Скрывает вывод важных данных о подключении, кроме названия и опций. Также возращает список таблиц.
     * @return array отображаемые значения
     */
    public function __debugInfo(){
        $db = self::getConnection();
        $config = $db->getConfig();
        unset($config['password']);
        unset($config['username']);
        unset($config['host']);
        unset($config['charset']);
        if (empty($config['options'])) {
            unset($config['options']);
        }
        if (empty($config['driver_options'])) {
            unset($config['driver_options']);
        }

        return [
            'config' => $config,
            'tables' => $db->listTables(),
        ];
    }
}


