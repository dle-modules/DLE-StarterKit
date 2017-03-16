<?php
/*
 * DLE-Starter — "Hello Word" модуль для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       https://git.io/vPLpe
 */



/**
 * Этот файл отвечает за первый шаг установки модуля
 */
return [

	// Заголовок щага
	'header' => 'Добавление стилей и скриптов модуля',

	// Текст с описанием шага шага
	'text' => 'В шаблоне <b>%THEME%/main.tpl</b>',

	// Код, который необходимо вставить
	// 'paste' => 'someCode to paste',

	// Код, который необходимо найти
	'find' => '</head>',

	// Код, который необходимо вставить перед найденным
	'addBefore' => '<link rel="stylesheet" href="{THEME}/dle_starter/css/starter.css">
<script src="{THEME}/dle_starter/js/starter.js"></script>',

	// Код, который необходимо вставить после найденного
	// 'addAfter' => 'someCode to add after',


	// Код, которым необходимо заменить найденное
	// 'replace' => 'someCode to replace'
];
