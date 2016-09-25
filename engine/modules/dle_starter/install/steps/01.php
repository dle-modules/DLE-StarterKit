<?php
return [

	// Заголовок щага
	'header' => 'шаг 1',

	// Текст с описанием шага шага
	'text' => 'Делай раз!',

	// Код, который необходимо найти
	'find' => 'someCode',

	// Код, который необходимо вставить перед найденным
	// 'addBfore' => 'someCode to add before',

	// Код, который необходимо вставить после найденного
	'addAfter' => 'someCode to add after',

	// Код, которым необходимо заменить найденное
	// 'replace' => 'someCode to replace',

	// Запросы, которые будут выполнены на этом шаге
	'queries' => 'CREATE TABLE IF NOT EXISTS `' . PREFIX . '_starter_test` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);'
];
