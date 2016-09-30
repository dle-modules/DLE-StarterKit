<?php
/*
 * DLE-Starter — "Hello Word" модуль для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       https://git.io/vPLpe
 */


/**
 * Этот файл отвеает за выполнение sql запросов во время установки модуля.
 */
return [
	'CREATE TABLE IF NOT EXISTS `' . PREFIX . '_starter_test` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);',
	'CREATE TABLE IF NOT EXISTS `' . PREFIX . '_starter_test_another` (
  `id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);'
];
