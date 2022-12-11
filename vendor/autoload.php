<?php

require_once(__DIR__ . '/helpers.php');

/**
 * Обработчик функции автозагрузки файлов
 * @param $class
 *
 * @package geobyazub
 *
 */
function azub_autoloader($class){

    if (starts_with($class, 'Azub\\')) {
        $file = __DIR__ . '/../' . str_replace('\\\\', '/', $class) . '.php';
        $file = str_replace('\\', '/', $file);
        if (file_exists($file)) {
            require_once($file);
        }
    } else {

        $pathes = [
            'Common' => DOC_ROOT . 'core2/inc/classes/Common.php',
            'Panel' => DOC_ROOT . 'core2/inc/classes/Panel.php',
            'Alert' => DOC_ROOT . 'core2/inc/classes/Alert.php',
            'listTable' => DOC_ROOT . 'core2/inc/classes/class.list.php',
            'editTable' => DOC_ROOT . 'core2/inc/classes/class.edit.php',
            'ajaxFunc' => DOC_ROOT . 'core2/inc/ajax.func.php',
            '\\Core2\\Db' => DOC_ROOT . 'core2/inc/classes/Db.php',
        ];

        if (isset($pathes[$class])) {
            require_once($pathes[$class]);
        }
    }
}

spl_autoload_register('azub_autoloader');

class_alias('Common','Core2\Common');
class_alias('Alert','Core2\View\Alert');
class_alias('listTable','Core2\View\ListTable');
class_alias('editTable','Core2\View\EditTable');
class_alias('ajaxFunc','Core2\Ajax');