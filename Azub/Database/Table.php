<?php

namespace Azub\Database;

/**
 * Класс таблицы модели базы данных
 *
 * Используется для работы с записями в бд.
 *
 * @package geobyazub\Azub\Database
 */
abstract class Table {

    /**
     * Инициализация
     *
     * @param mixed[] $values значения для присваивания переменных класса
     */
    public function __construct($values = array()){
        foreach ($values as $field => $value) {
            $this->{$field} = $value;
        }
    }

    /**
     * @var int $id ключ для таблиц, всегда есть.
     */
    public $id;


    /**
     * Извлечение класса записи таблицы
     *
     * @param int|string $id идентификатор записи таблицы
     *
     * @return false|mixed дочерний класс модели
     * @throws \Zend_Db_Statement_Exception
     */
    public static function find($id){

        // создание запроса
        $stm = Db::query('select * from ' . static::TABLE_NAME . ' where id = ?', [$id]);

        // выполнение
        $stm->execute();

        // возврат класса
        $record = $stm->fetchObject(get_called_class());

        return empty($record) ? null : $record;
    }

    /**
     * Извлечение класса записи таблицы и её создание при отсутствии
     *
     * @param array $values массив полей и их значений для поиска
     *
     * @return false|mixed дочерний класс модели
     * @throws \Zend_Db_Statement_Exception
     */
    public static function firstOrCreate($values){

        // создание запроса
        $query = Db::select()
            ->from(static::TABLE_NAME);

        // добавление создаваемых значений на наличие в бд
        foreach ($values as $field => $value) {
            $query->where($field . ' = ?', $value);
        }
        // выполнение запроса
        $stm = Db::query($query);

        // возврат записи из бд
        $record = $stm->fetchObject(get_called_class());

        // записи нет в бд, создается новая и cохраняется
        if (empty($record)) {
            // создание нового экземпляра класса таблицы
            $record = new static($values);

            // сохранение экземпляра
            $record->save();

            return $record;
        }

        return $record;
    }

    /**
     * Получает опции ключ-значение из записей таблицы
     *
     * Используется в выподающих списках
     *
     * @param string[] $initial начальные ключ-значения,
     * @param string $id название ключа из таблицы, по умочанию id
     * @param string $value название значения из таблицы, по умолчанию name
     *
     * @return string[] массив ключ-значение
     */
    public static function getOptions($initial = null, $id = 'id', $value = 'name'){
        $opts = empty($initial) ? [] : $initial;
        $select = Db::select()
            ->from(static::TABLE_NAME)
            ->order('name');
        $data = $select->query()->fetchAll();

        foreach ($data as $row) {
            $opts[$row[$id]] = $row[$value];
        }

        return $opts;
    }

    /**
     * Созданяет или обновленяет значения строки таблицы
     */
    public function save(){

        if (empty($this->id)) {
            $this->id = Db::insertRecord(static::TABLE_NAME, $this->toArrayData());

            return null;
        }

        if (Db::hasId(static::TABLE_NAME, $this->id) == false) {
            $this->id = Db::insertRecord(static::TABLE_NAME, $this->toArrayData());

            return null;
        }

        return Db::updateRecordById(static::TABLE_NAME, $this->toArrayData(), $this->id);
    }

    /**
     * Удаляет запись из бд при указаном id.
     */
    public function delete(){
        if (!empty($this->id)) {
            Db::deleteRecordById(static::TABLE_NAME, $this->id);
        }
    }

    /**
     * Получить массив значений полей, кроме id
     * @return array
     */
    public function toArrayData(){
        $r = [];
        foreach ($this->fields as $f) {
            $r[$f] = $this->{$f};
        }

        return $r;
    }

    /**
     * Получить все значения полей
     * @return array
     */
    public function toArray(){
        $data = $this->toArrayData();

        return array_merge(['id' => $this->id], $data);
    }

    /**
     * Выводит сокращённую информацию при отладке
     * @return array отображаемые значения
     */
    public function __debugInfo(){
        return $this->toArray();
    }
}