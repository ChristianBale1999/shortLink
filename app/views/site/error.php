<?php

/** @var yii\web\View $this */
/** @var string $message */
/** @var int $code */

$this->title = 'Ошибка ' . $code;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $this->title ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
	<div class="alert alert-danger">
		<h1>Ошибка <?= $code ?></h1>
		<p><?= nl2br($message) ?></p>
	</div>
</div>
</body>
</html>