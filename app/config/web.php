<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'your-secret-key-' . md5(time()),
			'enableCsrfValidation' => false,
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			],
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
					'levels' => ['error', 'warning', 'info'],
				],
			],
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=mysql;dbname=shortlink_db',
			'username' => 'shortlink_user',
			'password' => 'shortlink_password',
			'charset' => 'utf8',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				's/<code>' => 'short/index',
				'' => 'site/index',
				'site/create-short-link' => 'site/create-short-link',
			],
		],
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'sourcePath' => null,
					'js' => ['https://code.jquery.com/jquery-3.6.0.min.js'],
				],
				'yii\bootstrap4\BootstrapAsset' => [
					'sourcePath' => null,
					'css' => ['https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css'],
				],
				'yii\bootstrap4\BootstrapPluginAsset' => [
					'sourcePath' => null,
					'js' => ['https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js'],
				],
			],
		],
	],
	'params' => [],
];

if (YII_ENV_DEV) {
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		'allowedIPs' => ['*'],
	];
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
		'allowedIPs' => ['*'],
	];
}

return $config;