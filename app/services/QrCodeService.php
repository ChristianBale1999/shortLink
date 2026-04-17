<?php

declare(strict_types=1);

namespace app\services;

use yii\base\Component;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Color\Color;

class QrCodeService extends Component
{
	public function generate(string $url, int $size = 250): string
	{
		try {
			// Проверяем наличие GD расширения
			if (!extension_loaded('gd')) {
				return $this->getFallbackQr($url, $size);
			}

			$qrCode = QrCode::create($url)
				->setEncoding(new Encoding('UTF-8'))
				->setSize($size)
				->setMargin(10)
				->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
				->setForegroundColor(new Color(0, 0, 0))
				->setBackgroundColor(new Color(255, 255, 255));

			$writer = new PngWriter();
			$result = $writer->write($qrCode);

			return 'data:image/png;base64,' . base64_encode($result->getString());

		} catch (\Exception $e) {
			// В случае ошибки используем внешний API
			return $this->getFallbackQr($url, $size);
		}
	}

	private function getFallbackQr(string $url, int $size): string
	{
		return 'https://quickchart.io/qr?size=' . $size . '&text=' . urlencode($url);
	}
}