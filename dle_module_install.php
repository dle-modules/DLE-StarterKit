<?php

if (!defined('DATALIFEENGINE')) {
	define('DATALIFEENGINE', true);
}

include('dle_starter_installer/install.class.php');

/**
 * Название модуля, кторый необходимо установить
 */
$moduleName = (isset($_REQUEST['module'])) ? trim($_REQUEST['module']) : 'dle_starter';


$installer = new dleStarterInstaller($moduleName);

$stepsHeadings = [
	'find' => 'Найти код',
	'addBfore' => 'Выше добавить',
	'addAfter' => 'Ниже добавить',
	'replace' => 'Заменить на',
];

$contacts = '';

try {
	$installer->checkBeforeInstall();
	$checkInstall = true;

	$licence = $installer->getTextFile('licence');

	$contacts = $installer->getTextFile('contacts');

	if ($licence !== '') {
		if (isset($_POST['notaccept'])) {
			$output = <<<HTML
		<div class="content">
			<div class="col col-mb-12">
				<div class="alert">
					Вы отказались от установки модуля. <br>Не забудьте удалить загруженные файлы.
				</div>
			</div>
		</div>
HTML;
		} elseif (empty($_POST['accept'])) {
			$output = <<<HTML
		<form method="post">
			<div class="content">
				<div class="col col-mb-12">
					<h2 class="mt0 ta-center">
						Лицензионное соглашение
					</h2>
					<div class="licence-text">
						{$licence}
					</div>
				</div>
				<div class="col col-mb-12 col-6 col-dt-5 col-dt-left-1 col-margin-top">
					<button type="submit" name="notaccept" value="y" class="btn btn-block btn-red">Не принимаю</button>
				</div>
				<div class="col col-mb-12 col-6 col-dt-5 col-margin-top">
					<button type="submit" name="accept" value="y" class="btn btn-block">Приимаю, продолжить установку</button>
				</div>
			</div>
		</form>
HTML;
		}
	}

	if (isset($_POST['accept'])) {
		$steps = $installer->getSteps();
		if (count($steps)) {
			$output .= '<div class="steps"><ol>';

			foreach ($steps as $key => $step) {
				$stepElement = [];

				foreach ($step as $i => $stepItem) {
					switch ($i) {
						case 'header':
							$stepElement[] = '<div class="step-element step-header">' . $stepItem . '</div>';
							break;

						case 'text':
							$stepElement[] = '<div class="step-element step-text">' . $stepItem . '</div>';
							break;

						case 'find':
						case 'addBfore':
						case 'addAfter':
						case 'replace':
							$stepElement[] = <<<HTML
						<div class="step-element step-{$i}">
							<div class="content">
								<div class="col col-mb-12">
									<div class="step-subheading">
										{$stepsHeadings[$i]}
									</div>
								</div>
								<div class="col col-mb-10">
									<textarea id="clpbrd-{$key}-{$i}" readonly class="code" rows="1">{$stepItem}</textarea>

								</div>
								<div class="col col-mb-2">
									<span class="btn btn-block btn-border btn-clipboard" title="Копировать код" data-clipboard-target="#clpbrd-{$key}-{$i}">
										<svg class="icon icon-copy"><use xlink:href="#icon-copy"></use></svg>
									</span>
								</div>
							</div>
						</div>
HTML;
							break;


						case 'queries':
							// @TODO
							// $stepElement[] = $stepItem;
							break;
					}
				}

				$stepElementString = '<li>' . implode($stepElement) . '</li>';

				$output .= $stepElementString;
			}

			$output .= '</ol></div>';

		}


	}


} catch (Exception $e) {
	$checkInstall = false;
	$output = <<<HTML
	<div class="content col-padding-top">
		<div class="col col-mb-12">
			<div class="alert">
				{$e->getMessage()}
			</div>
		</div>
	</div>
HTML;
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $installer->dle_config['charset'] ?>">
	<title><?php echo $installer->cfg['moduleTitle'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/normalize.css">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/legrid.min.css">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/dle_starter.css">
</head>

<body>
<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink">
	<defs>
		<symbol id="icon-copy" viewBox="0 0 32 32"><title>copy</title>
			<path class="path1"
			      d="M20.594 5.597h-14.876c-1.396 0-2.53 1.134-2.53 2.53v21.344c0 1.396 1.134 2.53 2.53 2.53h14.876c1.396 0 2.53-1.134 2.53-2.53v-21.344c-0.007-1.396-1.14-2.53-2.53-2.53zM21.348 29.464c0 0.419-0.341 0.76-0.76 0.76h-14.876c-0.419 0-0.76-0.341-0.76-0.76v-21.338c0-0.419 0.341-0.76 0.76-0.76h14.876c0.419 0 0.76 0.341 0.76 0.76v21.338z"></path>
			<path class="path2"
			      d="M26.282 0h-14.876c-1.396 0-2.53 1.134-2.53 2.53 0 0.491 0.393 0.885 0.885 0.885s0.885-0.393 0.885-0.885c0-0.419 0.341-0.76 0.76-0.76h14.876c0.419 0 0.76 0.341 0.76 0.76v21.344c0 0.419-0.341 0.76-0.76 0.76-0.491 0-0.885 0.393-0.885 0.885s0.393 0.885 0.885 0.885c1.396 0 2.53-1.134 2.53-2.53v-21.344c0-1.396-1.134-2.53-2.53-2.53z"></path>
		</symbol>
		<symbol id="icon-man-sprinting" viewBox="0 0 32 32"><title>man-sprinting</title>
			<path class="path1"
			      d="M1.319 22.765c-0.881 0.169-1.458 1.019-1.289 1.9 0.148 0.776 0.83 1.316 1.593 1.316 0.101 0 0.206-0.010 0.307-0.030l6.683-1.283c0.381-0.074 0.722-0.28 0.965-0.584l2.869-3.618-0.959-0.489c-0.709-0.358-1.202-1.006-1.37-1.792l-2.707 3.412-6.092 1.168z"></path>
			<path class="path2"
			      d="M28.677 5.1c0 1.965-1.593 3.557-3.557 3.557s-3.557-1.593-3.557-3.557c0-1.965 1.593-3.557 3.557-3.557s3.557 1.593 3.557 3.557z"></path>
			<path class="path3"
			      d="M17.357 4.209c-0.375-0.385-0.722-0.537-1.104-0.537-0.138 0-0.277 0.020-0.425 0.054l-6.106 1.448c-0.844 0.199-1.364 1.046-1.164 1.887 0.172 0.722 0.813 1.208 1.526 1.208 0.122 0 0.243-0.014 0.365-0.044l5.201-1.232c0.338 0.375 2.028 2.207 2.336 2.531-2.15 2.302-4.3 4.6-6.45 6.902-0.034 0.037-0.064 0.074-0.095 0.111-0.628 0.8-0.435 2.055 0.51 2.531l6.592 3.365-3.422 5.545c-0.469 0.763-0.233 1.762 0.53 2.234 0.267 0.165 0.56 0.243 0.851 0.243 0.543 0 1.077-0.273 1.384-0.77l4.347-7.044c0.24-0.388 0.304-0.857 0.179-1.293-0.125-0.439-0.425-0.803-0.834-1.009l-4.465-2.271 4.695-5.022 3.544 3.004c0.294 0.25 0.655 0.371 1.013 0.371 0.385 0 0.77-0.142 1.070-0.419l4.067-3.79c0.635-0.591 0.672-1.583 0.081-2.217-0.311-0.331-0.729-0.5-1.148-0.5-0.381 0-0.766 0.138-1.067 0.419l-3.044 2.832c-0.003 0.003-7.631-7.172-8.964-8.539z"></path>
		</symbol>
	</defs>
</svg>

<div class="body-wrapper clearfix">
	<?php if ($checkInstall): ?>

		<header class="container top_nav-container container-blue">
			<div class="content">
				<div class="col col-mb-12 ta-center">
				<span class="logo" title="<?php echo $installer->cfg['moduleTitle'] ?>">
					<svg class="icon icon-man-sprinting">
						<use xlink:href="#icon-man-sprinting"></use>
					</svg>
					<?php echo $installer->cfg['moduleTitle'] ?>
				</span>
				</div>
			</div>
		</header>
		<div class="container pb0">
			<div class="content">
				<div class="col col-mb-12 ta-center">
					<h1><?php echo $installer->cfg['moduleTitle'] ?> v.<?php echo $installer->cfg['moduleVersion'] ?>
						от <?php echo $installer->cfg['moduleDate'] ?></h1>
					<div class="text-muted">Установка модуля</div>
					<hr>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="content">
				<div class="col col-mb-12">
					<?php echo $output; ?>
				</div>
			</div>
		</div>
		<?php if ($contacts !== ''): ?>
			<div class="container pt0">
				<div class="content">
					<div class="col col-mb-12">
						<hr class="mt0">
						Контакты для связи и техподдержки:<br>
						<?php echo $contacts ?>
					</div>
				</div>
			</div>
		<?php endif ?>
	<?php else: ?>
		<?php echo $output; ?>
	<?php endif ?>

	<script
		src="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/js/jquery.min.js"></script>
	<script
		src="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/js/clipboard.min.js"></script>
	<script>
		$(document)
			.on('click', '.code', function () {
				$(this).select();
			})
			.on('click', '#wtq', function () {
				$('.queries').slideToggle(400);
				$(this).toggleClass('active');
			});
		new Clipboard('.btn-clipboard');
	</script>
</div><!-- .body-wrapper clearfix -->
</body>
</html>
