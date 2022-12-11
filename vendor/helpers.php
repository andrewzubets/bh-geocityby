<?php

if (!function_exists('geobyazub_data_path')) {
    /**
     * Получает полный путь к каталогу data.
     * Используется при установке.
     *
     * @param string $file_name название файла     *
     * @return string полный путь
     */
    function geobyazub_data_path($file_name = ''){
        $dir = dirname(__DIR__); // /vendor

        return $dir . '/data/' . $file_name;
    }
}

if (!function_exists('view_simple_panel')) {
    /**
     * Генерирует HTML панель с заголовком и содержимом.
     *
     * @param string $title заголовок панели
     * @param string|object $content HTML содержимое панели.
     *                               В случае передачи объекта пытается вызвать метод render для преобразования в строку.
     *                               Если render не указан вызывает метод __toString()
     *
     * @return mixed
     */
    function view_simple_panel($title, $content){
        if (is_object($content))
            if (method_exists($content, 'render'))
                $content = $content->render();
            else
                $content = $content->__toString();

        $panel = new \Panel();
        $panel->setTitle($title);
        $panel->setContent($content);

        return $panel->render();
    }
}

if (!function_exists('starts_with')) {
    /**
     * Проверяет начинается ли строка с указанным значением.
     *
     * @param string $str Проверяемая строка
     * @param string $test Значение
     *
     * @return bool
     */
    function starts_with($str, $test){
        return substr($str, 0, strlen($test)) == $test;
    }
}

if (!function_exists('ends_with')) {
    /**
     * Проверяет заканчивается ли строка указанным значением
     *
     * @param string $str Проверяемая строка
     * @param string $test Значение
     *
     * @return bool
     */
    function ends_with($str, $test){
        return substr($str, strlen($str) - strlen($test)) == $test;
    }
}

if (!function_exists('str_contains')) {
    /**
     * Проверяет содержит ли строка значение
     *
     * @param string $str Проверяемая строка
     * @param string $test Значение
     *
     * @return bool
     */
    function str_contains($str, $test){
        return str_pos($str, $test) !== false;
    }
}
if (!function_exists('dd')) {
    /**
     * Функция отладки и завершения скрипта
     *
     * @param mixed $d
     */
    function dd($d){
        var_dump($d);
        exit();
    }
}

?>