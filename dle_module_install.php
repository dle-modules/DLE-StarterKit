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

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $installer->dle_config['charset'] ?>">
	<title><?php echo $installer->cfg['moduleTitle'] ?></title>
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
				<a href="/" class="logo" title="<?php echo $installer->cfg['moduleTitle'] ?>">
					<img src="/dle_starter_installer/assets/images/logo.png"
					     alt="<?php echo $installer->cfg['moduleTitle'] ?>"/>
				</a>
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
				<?php
				try {
					$installer->checkBeforeInstall();

					foreach ($installer->gtSteps() as $gtStep) {
						echo "<pre class='dle-pre'>";
						print_r($gtStep);
						echo "</pre>";
					}


				} catch (Exception $e) {
					echo "<pre class='dle-pre'>";
					print_r($e->getMessage());
					echo "</pre>";
				}
				?>
			</div>
		</div>
	</div>

	<div class="container pt0">
		<div class="content">
			<div class="col col-mb-12">
				<hr class="mt0">
				Контакты для связи и техподдержки:<br>

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
