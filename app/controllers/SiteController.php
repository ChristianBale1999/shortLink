<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Link;
use GuzzleHttp\Client;

class SiteController extends Controller
{
	public $enableCsrfValidation = false;
	private Client $httpClient;

	public function init(): void
	{
		parent::init();
		$this->httpClient = new Client([
			'timeout' => 10,
			'verify' => false,
		]);
	}

	public function actionIndex(): string
	{
		return $this->render('index');
	}

	public function actionCreateShortLink(): array
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$url = Yii::$app->request->post('url');

		if (!$url) {
			return ['success' => false, 'error' => 'URL не указан'];
		}

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$url = 'http://' . $url;
			if (!filter_var($url, FILTER_VALIDATE_URL)) {
				return ['success' => false, 'error' => 'Невалидный URL'];
			}
		}

		// Проверка доступности через Guzzle
		try {
			$response = $this->httpClient->head($url);
			if ($response->getStatusCode() >= 400) {
				return ['success' => false, 'error' => 'URL не доступен'];
			}
		} catch (\Exception $e) {
			return ['success' => false, 'error' => 'URL не доступен'];
		}

		$shortCode = $this->generateShortCode();
		$shortUrl = Yii::$app->request->hostInfo . '/s/' . $shortCode;

		$link = new Link();
		$link->original_url = $url;
		$link->short_code = $shortCode;
		$link->clicks = 0;

		if ($link->save()) {
			$qrCode = Yii::$app->qrCode->generate($shortUrl);

			return [
				'success' => true,
				'short_url' => $shortUrl,
				'qr_code' => $qrCode,
			];
		}

		return ['success' => false, 'error' => 'Ошибка сохранения'];
	}

	private function generateShortCode(int $length = 6): string
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$max = strlen($chars) - 1;

		do {
			$code = '';
			for ($i = 0; $i < $length; $i++) {
				$code .= $chars[random_int(0, $max)];
			}
		} while (Link::findOne(['short_code' => $code]));

		return $code;
	}
}