<?php

declare(strict_types=1);

defined('YII_DEBUG') or define('YII_DEBUG', getenv('APP_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('APP_ENV') ?: 'dev');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'aliases' => [
		'@npm' => '@vendor/npm-asset',
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'secret-key-' . md5((string)time()),
			'enableCsrfValidation' => false,
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => require __DIR__ . '/db.php',
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				's/<code>' => 'short/index',
				'' => 'site/index',
			],
		],
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'sourcePath' => '@npm/jquery/dist',
					'js' => ['jquery.min.js'],
				],
				'yii\bootstrap4\BootstrapAsset' => [
					'sourcePath' => '@npm/bootstrap/dist',
					'css' => ['css/bootstrap.min.css'],
				],
				'yii\bootstrap4\BootstrapPluginAsset' => [
					'sourcePath' => '@npm/bootstrap/dist',
					'js' => ['js/bootstrap.bundle.min.js'],
				],
			],
		],
		'qrCode' => [
			'class' => 'app\services\QrCodeService',
		],
	],
	'params' => require __DIR__ . '/params.php',
];

return $config;