<?php

/**
 *
 */
class dleStarterInstaller {
	public $dle_config = [];
	public $cfg        = [];
	public $tplOptions = [];
	public $tpl;
	public $db;

	function __construct() {
		// Подключаем конфиг DLE
		$this->dle_config = $this->getDleConfig();

		// Подключаем класс для работы с БД
		$this->db = $this->getDb();
	}

	public static function getDleConfig() {
		include ENGINE_DIR . '/data/config.php';

		/** @var array $config */
		return $config;
	}

	/**
	 * Отлавливаем данные о кодировке файла (utf-8 или windows-1251);
	 *
	 * @param  string $string - строка (или массив), в которой требуется определить кодировку.
	 *
	 * @return array          - возвращает массив с определением конфликта кодировки строки и сайта, а так же саму кодировку строки.
	 */
	public function chasetConflict($string) {

		if (is_array($string)) {
			$string = implode(' ', $string);
		}
		$detect = preg_match('%(?:
		[\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
		|\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
		|\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
		|\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
		|[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
		|\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
		)+%xs', $string);
		$stringCharset = ($detect == '1') ? 'utf-8' : 'windows-1251';
		$this->dle_config['charset'] = strtolower($this->dle_config['charset']);
		$return = [];
		$return['conflict'] = ($stringCharset == $this->dle_config['charset']) ? false : true;
		$return['charset'] = $stringCharset;

		return $return;
	}
} ?>