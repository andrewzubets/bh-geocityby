<?php

use Azub\Database\Tables\City;
use Core2\Ajax;
/**
 * Класс обработки ajax запросов.
 * Запрос вызывается на фронтенде при нажатии на кнопку "сохранить".
 * Далее он попадает в обработчики ядра и вызывает методы данного класса.
 * Ограничения ядра: Название класса должно быть ModAjax и функции начинаться с "ax"
 *
 * @package geobyazub
 */
class ModAjax extends Ajax {


    /**
     * Инициализация класса с обязательным объектом запроса.
     * @param xajaxResponse $res экземпляр объекта запроса.
     */
    public function __construct(xajaxResponse $res){
        parent::__construct($res);

        // обходной путь чтобы не писать полное название модуля в обработчиках.
        $this->module = 'geoby';
    }

    /**
     * Обработчик ajax события сохранить на странице добавления города.
     * Создаёт новую запись города в бД.
     * При отсутствии названия города вернёт ошибку.
     *
     * @param array $input переданные из формы данные
     * @return mixed Объект ajax ответа
     */
    public function axCreateGeobyCity($input){

        $fields = array(
            'name' => 'req',
        );
        if ($this->ajaxValidate($input, $fields)) {
            return $this->response;
        }

        $data = [
            'name' => $input['control']['name'],
            'region_id' => $input['control']['region_id'],
        ];

        try {
            $m = new City();
            $m->name = $data['name'];
            $m->region_id = empty($data['region_id']) ? null : $data['region_id'];
            $m->save();
            if (empty($m->id)) {
                $this->error[] = 'При сохранении не вернулся идентификатор, возможно запись не сохранилась';
                $this->done($data);

                return $this->response;
            }
        } catch (\Exception $e) {
            $this->error[] = $e->getMessage();
        }

        $this->done($input);

        return $this->response;
    }

    /**
     * Обработчик ajax события сохранить на странице изменения города.
     * Изменяет запись города по id. При отсутствии названия города вернёт ошибку.
     *
     * @param array $input переданные из формы данные     *
     * @return mixed Объект ajax ответа
     */
    public function axUpdateGeobyCity($input){

        $fields = array(
            'name' => 'req',
            // 'region_id' => 'req'
        );
        if ($this->ajaxValidate($input, $fields)) {
            return $this->response;
        }
        $data = [
            'name' => $input['control']['name'],
            'region_id' => $input['control']['region_id'],
        ];

        $id = isset($input['params']['update']) ? (int)$input['params']['update'] : null;
        if (empty($id)) {
            $this->error[] = 'Идентификатор города не указан.';
            $this->done($input);

            return $this->response;
        }

        try {
            $m = City::find($id);

            if (empty($m)) {
                $this->error[] = 'Город не найден';
                $this->done($data);

                return $this->response;
            }
            $m->name = $data['name'];
            $m->region_id = empty($data['region_id']) ? null : $data['region_id'];
            $m->save();
        } catch (\Exception $e) {

            $this->error[] = $e->getMessage();
        }

        $this->done($input);

        return $this->response;
    }
}
