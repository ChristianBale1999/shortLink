<?php

declare(strict_types=1);

return [
	'appName' => getenv('APP_NAME') ?: 'Shortlink Service',
	'qrSize' => (int)(getenv('QR_SIZE') ?: 250),
	'qrMargin' => (int)(getenv('QR_MARGIN') ?: 10),
	'shortCodeLength' => (int)(getenv('SHORT_CODE_LENGTH') ?: 6),
];