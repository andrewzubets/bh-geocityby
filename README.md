# Geo City BY
Модуль для core2 городов. Добавляет раздел городов с регионами. В разделе отображается список городов с возможностью поиска. При нажатии на ячейку происходит переход на страницу редактирования города и региона к которому он относится.

Установка
------------
Возможна установка архивом гита в разделе доступных модулей.

Модуль был написан для **PHP 7.4.33**.

При установке создаются две таблицы ``mod_geobyazub_cities`` и ``mod_geobyazub_regions``. После этого они наполняются исходными данными из 
<a href="https://github.com/andrewzubets/bh-geocityby/blob/master/data/cities.json">списка городов с регионами</a>.

Деинсталяция
------------
Деинсталируется в разделе модулей. При удалении выполняется <a href="https://github.com/andrewzubets/bh-geocityby/blob/master/install/v1.0/uninstall.sql">SQL скрипт</a> удаления таблиц.
