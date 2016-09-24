<?php

/**
 * Class dleStarterInstaller
 */
class dleStarterInstaller {
	public $dle_config = [];
	public $cfg        = [];

	private $engineDir = '';
	private $moduleDir = '';
	private $db;

	function __construct($moduleName) {
		// Определяем пути к папкам
		$this->engineDir = dirname(__DIR__) . '/engine';
		$this->moduleDir = $this->engineDir . '/modules/' . $moduleName;

		// Определяем конфиги
		$this->dle_config = $this->getDleConfig();
		$this->cfg = $this->getConfig();
		$this->db = $this->getDb();

	}

	/**
	 * @return array
	 */
	private function getDleConfig() {
		include $this->engineDir . '/data/config.php';

		/** @var array $config */
		return $config;
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	private function getConfig() {
		if (!file_exists($this->moduleDir . '/install/config.php')) {
			return [];
		} else {
			return include $this->moduleDir . '/install/config.php';
		}

	}

	/**
	 * @return mixed
	 */
	private function getDb() {
		include_once $this->engineDir . '/classes/mysql.php';
		return include_once $this->engineDir . '/data/dbconfig.php';
	}

	/**
	 * @throws Exception
	 */
	public function checkBeforeInstall() {
		if (isset($this->cfg['minVersion'])) {
			if ($this->dle_config['version_id'] < $this->cfg['minVersion']) {
				throw new Exception('Установленная версия DLE слишком старая. Необходимо установить DLE не ниже ' . $this->cfg['minVersion']);
			}

			if ($this->dle_config['version_id'] > $this->cfg['maxVersion']) {
				throw new Exception('Установленная версия DLE слишком новая. Необходимо установить DLE не выше ' . $this->cfg['maxVersion']);
			}
		} else {
			throw new Exception('Файл с конфигурацией установки не найден, возмжно установочные файлы модуля не скопированы.');
		}
	}

	/**
	 * @return array
	 */
	public function gtSteps() {
		$files = [];

		foreach (glob($this->moduleDir . '/install/steps/*.php') as $file) {
			$files[] = include_once($file);
		}
		return $files;
	}

}
