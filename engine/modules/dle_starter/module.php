<?php
/*
 * DLE-Starter — "Hello Word" модуль для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       https://git.io/vPLpe
 */

if (!defined('DATALIFEENGINE')) {
	die('Hacking attempt');
}

/**
 * Информация из DLE, доступная в модуле
 *
 * @global boolean $is_logged           Является ли посетитель авторизованным пользователем или гостем.
 * @global array   $member_id           Массив с информацией о авторизованном пользователе, включая всю его информацию из профиля.
 * @global object  $db                  Класс DLE для работы с базой данных.
 * @global object  $tpl                 Класс DLE для работы с шаблонами.
 * @global array   $cat_info            Информация обо всех категориях на сайте.
 * @global array   $config              Информация обо всех настройках скрипта.
 * @global array   $user_group          Информация о всех группах пользователей и их настройках.
 * @global integer $category_id         ID категории которую просматривает посетитель.
 * @global integer $_TIME               Содержит текущее время в UNIX формате с учетом настроек смещения в настройках скрипта.
 * @global array   $lang                Массив содержащий текст из языкового пакета.
 * @global boolean $smartphone_detected Если пользователь со смартфона - true.
 * @global string  $dle_module          Информация о просматриваемомразделе сайта, либо информацию переменной do из URL браузера.
 */

// Определям конфиг модуля по умолчанию
$moduleConfig = [
	'cachePrefix' => !empty($cachePrefix) ? $cachePrefix : 'news',
	'cacheSuffixOff' => !empty($cacheSuffixOff) ? $cacheSuffixOff : false
];

// Определяемся с шаблоном сайта
// Проверим куку пользователя и наличие параметра skin в реквесте.
$currentSiteSkin = (isset($_COOKIE['dle_skin'])) ? trim(totranslit($_COOKIE['dle_skin'], false, false))
	: (isset($_REQUEST['skin'])) ? trim(totranslit($_REQUEST['skin'], false, false)) : $config['skin'];

// Если в итоге пусто — назначим опять шаблон из конфига.
if ($currentSiteSkin == '') {
	$currentSiteSkin = $config['skin'];
}

// Если папки с шаблоном нет — дальше не работаем.
if (!is_dir(ROOT_DIR . '/templates/' . $currentSiteSkin)) {
	die('no_skin');
}

// Формируем имя кеша
$cacheName = implode('_', $moduleConfig) . $currentSiteSkin;

// Определяем необходимость создания кеша для разных групп
$cacheSuffix = ($moduleConfig['cacheSuffixOff']) ? false : true;

// Формируем имя кеша
$cacheName = md5(implode('_', $moduleConfig));

// Дефолтное значение модуля
$module = false;

// Пытаеся получить данные из кеша
$module = dle_cache($moduleConfig['cachePrefix'], $cacheName, $cacheSuffix);

// Если ничего не пришло из кеша — раблотаем
if (!$module) {
	// Тут выполняем логику модуля и складываем всё в $module
	$module = 'DLE-StarterKit работает!';

	// Сохраняем данные в кеш
	create_cache($moduleConfig['cachePrefix'], $module, $cacheName, $cacheSuffix);
}

// выводим результат работы модуля
echo $module;
