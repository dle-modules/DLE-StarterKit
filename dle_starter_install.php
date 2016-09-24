<?php
// Первым делом подключаем DLE_API как это ни странно, но в данном случаи это упрощает жизнь разработчика.
include('engine/api/api.class.php');


// Определяем кодировку.
$fileCharset = chasetConflict($cfg);


// Шаги установки модуля
/** @var array $config */
$steps = <<<HTML
<h2 class="mt0">Редактирование файлов</h2>
<ol>
	<li>
		Открыть файл <b>/templates/{$config['skin']}/main.tpl</b> 
	</li>
	<li>
		Добавить перед <b>&lt;/head&gt;</b>:
		<textarea readonly class="code" rows="1"><link href="{THEME}/blockpro/css/blockpro.css" rel="stylesheet" /></textarea>
	</li>
	<li>
		Добавить перед <b>&lt;/head&gt;</b>:
		<textarea readonly class="code" rows="1"><script src="{THEME}/blockpro/js/blockpro.js"></script></textarea>
		или
		<textarea readonly class="code" rows="1"><script src="{THEME}/blockpro/js/blockpro_new.js"></script></textarea>
		если хотите использовать возможность навигации по стрелкам браузера при ajax-переключении страниц модуля.
	</li>
	<li>Открыть файл <b>/engine/data/blockpro.key</b> и вставить в него <a href="http://store.pafnuty.name/purchase/" target="_blank">полученный ключ</a>.</li>
	<li>Выполнить установку админчасти и таблиц модуля (кнопка ниже).</li>
</ol>
HTML;


function installer() {
	global $config, $dle_api, $cfg, $steps, $fileCharset, $licenseText;

	$output = $queriesTxt = '';

	$queries = (count($cfg['queries'])) ? true : false;
	$adminInstalled = false;
	if ($cfg['installAdmin']) {
		$aq = $dle_api->db->super_query("SELECT name FROM " . PREFIX . "_admin_sections WHERE name = '{$cfg['moduleName']}'");

		$adminInstalled = ($aq['name'] == $cfg['moduleName']) ? true : false;

	}
	if (isset($_POST['notaccept']) && $cfg['showLicense'] && !$adminInstalled) {
		$output = <<<HTML
		<div class="content">
			<div class="col col-mb-12">
				<div class="alert">
					Вы отказались от установки модуля. <br>Не забудьте удалить загруженные файлы.
				</div>
			</div>
		</div>
HTML;
	} elseif (empty($_POST['accept']) && $cfg['showLicense'] && !$adminInstalled) {
		$output = <<<HTML
		<form method="post">
			<div class="content">
				<div class="col col-mb-12">
					$licenseText
				</div>
				<div class="col col-mb-12 mt30">
					<button type="submit" name="notaccept" value="y" class="btn btn-red">Не согласен</button>
					<button type="submit" name="accept" value="y" class="btn">Согласен, продолжить установку</button>
				</div>
			</div>
		</form>
HTML;
	} else {
		if ($queries) {
			foreach ($cfg['queries'] as $qq) {
				$queriesTxt .= '<textarea readonly class="code" rows="10">' . $qq . '</textarea>';
			}
		}


		// Если через $_POST передаётся параметр install, производим инсталляцию, согласно параметрам
		if (!empty($_POST['install'])) {
			// Выводим результаты  установки модуля
			$output .= '<div class="descr"><ul>';

			if ($queries) {
				// Выполняем запросы из массива.
				foreach ($cfg['queries'] as $q) {
					$query[] = $dle_api->db->query($q);
				}

				$output .= '<li><b>Запросы выполнены!</b></li>';
			}

			// Установка админки (http://dle-news.ru/extras/online/include_admin.html)
			if ($cfg['installAdmin']) {

				$install_admin = $dle_api->install_admin_module($cfg['moduleName'], $cfg['moduleTitle'], $cfg['moduleDescr'], $cfg['moduleName'] . '.png', $cfg['allowGroups']);

				if ($install_admin) {
					$output .= '<li><b>Админчасть модуля установлена</b></li>';
				}
			}

			$output .= '<li><b>Установка завершена!</b></li></ul></div>';
			$output .= '<div class="alert">Не забудьте удалить файлы установщика (blockpro_install.php и blockpro_upgrade.php)!</div>';
			/** @var bool $install_admin */
			if ($cfg['installAdmin'] && $install_admin) {
				$output .= '<p><a class="btn" href="/' . $config['admin_path'] . '?mod=' . $cfg['moduleName'] . '" target="_blank" title="Перейти к управлению модулем">Настройка модуля</a></p> <hr>';
			}

		} // Если через $_POST передаётся параметр remove, производим удаление админчасти модуля
		elseif (!empty($_POST['remove'])) {
			$dle_api->uninstall_admin_module($cfg['moduleName']);
			$output .= '<div class="descr"><p><b>Админчасть модуля удалена</b></p></div>';
			$output .= '<div class="alert">Не забудьте удалить файл установщика!</div>';
		} // Если через $_POST ничего не передаётся, выводим форму для установки модуля
		else {
			// Выводим кнопку удаления  модуля
			if ($cfg['installAdmin'] && $adminInstalled) {
				$uninstallForm = <<<HTML
			<hr>
			<div class="form-field clearfix">
				<div class="h2">Удаление админчасти модуля</div>
				<form method="POST">
					<input type="hidden" name="remove" value="1">
					<input type="hidden" name="accept" value="y">
					<button class="btn btn-red" type="submit">Удалить админчасть модуля</button>
				</form>
			</div>
HTML;
			}
			// Выводим кнопку установки модуля с допзпросами
			if ($queries) {
				$installForm = <<<HTML
			<div class="form-field clearfix">
				<form method="POST">
					<input type="hidden" name="install" value="1">
					<input type="hidden" name="accept" value="y">
					<button class="btn btn-blue" type="submit">Установить модуль</button>
					<span id="wtq" class="btn btn-normal btn-border btn-gray">Какие запросы будут выполнены?</span>
				</form>
			</div>
			<div class="queries clearfix hide">
				$queriesTxt
			</div>
HTML;
			} // Выводим кнопку установки админчасти модуля
			else {
				if (!$adminInstalled) {
					$installForm = <<<HTML
				<div class="form-field clearfix">
					<div class="label">Установка админчасти</div>
					<div class="control">
						<form method="POST">
							<input type="hidden" name="install" value="1">
							<button class="btn" type="submit">Установить админчасть модуля</button>
						</form>
					</div>
				</div>
HTML;
				}
			}

			// Вывод
			if ($cfg['steps']) {
				$output .= $steps;
			}
			/** @var string $installForm */
			/** @var string $uninstallForm */
			$output .= <<<HTML
			<p class="alert">Перед установкой модуля обязательно <a href="/{$config['admin_path']}?mod=dboption" target="_blank" title="Открыть инструменты работы с БД DLE в новом окне">сделайте бэкап БД</a>!</p>
			<div class="descr">
				<h2>Установка таблиц модуля и админчасти</h2>

				$installForm
				$uninstallForm
			</div>
HTML;


		}

	}


	// Если руки пользователя кривые, или он просто забыл перекодировать файлы - скажем ему об этом.
	if ($fileCharset['conflict']) {
		$output = '<h2 class="red ta-center">Ошибка!</h2><p class="alert">Кодировка файла установщика (<b>' . $fileCharset['charset'] . '</b>) не совпадает с кодировкой сайта (<b>' . $config['charset'] . '</b>). <br />Установка не возможна. <br />Перекодируйте все php файлы модуля и запустите установщик ещё раз.</p> <hr />';
	}

	// Функция возвращает то, что должно быть выведено
	return $output;
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $fileCharset['charset'] ?>">
	<title><?php echo $cfg['moduleTitle'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/dle_starter_installer/assets/css/normalize.css">
	<link rel="stylesheet" href="/dle_starter_installer/assets/css/legrid.min.css">
	<link rel="stylesheet" href="/dle_starter_installer/assets/css/dle_starter.css">
</head>

<body>
<div class="body-wrapper clearfix">
	<header class="container top_nav-container container-blue">
		<div class="content">
			<div class="col col-mb-12 ta-center">
				<a href="/" class="logo" title="<?php echo $cfg['moduleTitle'] ?>">
					<img src="/dle_starter_installer/assets/images/logo.png" alt="<?php echo $cfg['moduleTitle'] ?>"/>
				</a>
			</div>
		</div>
	</header>
	<div class="container pb0">
		<div class="content">
			<div class="col col-mb-12 ta-center">
				<h1><?php echo $cfg['moduleTitle'] ?> v.<?php echo $cfg['moduleVersion'] ?>
					от <?php echo $cfg['moduleDate'] ?></h1>
				<div class="text-muted">Установка модуля</div>
				<hr>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="content">
			<div class="col col-mb-12">
				<?php
				$output = installer();
				echo $output;
				?>
			</div>
		</div>
	</div>

	<div class="container pt0">
		<div class="content">
			<div class="col col-mb-12">
				<hr class="mt0">
				Контакты для связи и техподдержки:<br>
				<a href="https://pafnuty.omnidesk.ru/" target="_blank" title="Сайт поддержки">pafnuty.omnidesk.ru</a> —
				техподдержка <br>
				<a href="http://bp.pafnuty.name/" target="_blank" title="Официальный сайт модуля">bp.pafnuty.name</a> —
				документация <br>
			</div>
		</div>
	</div>
	<script src="/dle_starter_installer/assets/js/jquery.min.js"></script>
	<script>
		$(document)
			.on('click', '.code', function () {
				$(this).select();
			})
			.on('click', '#wtq', function () {
				$('.queries').slideToggle(400);
				$(this).toggleClass('active');
			});
	</script>
</div><!-- .body-wrapper clearfix -->
</body>
</html>
