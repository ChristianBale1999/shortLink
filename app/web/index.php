<?php

declare(strict_types=1);

require __DIR__ . '/../config/env.php';

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');

defined('YII_DEBUG') or define('YII_DEBUG', getenv('APP_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('APP_ENV') ?: 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();