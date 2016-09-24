<?php 
return [
	
	// Заголовок щага
	'header' => 'шаг 1',

	// Текст шага
	'text' => 'Делай раз!',

	// Запросы, которые будут выполнены на этом шаге
	'queries' => 'CREATE TABLE IF NOT EXISTS `' . PREFIX . '_starter_test` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);'
];