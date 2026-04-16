<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Link;

class SiteController extends Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionError()
	{
		$exception = Yii::$app->errorHandler->exception;
		return $this->render('error', [
			'message' => $exception ? $exception->getMessage() : 'Unknown error',
			'code' => $exception ? $exception->getCode() : 500,
		]);
	}

	public function actionCreateShortLink()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$url = Yii::$app->request->post('url');
		if (!$url) {
			$url = Yii::$app->request->get('url');
		}

		if (!$url) {
			return ['success' => false, 'error' => 'URL не указан'];
		}

		if (!$this->validateUrl($url)) {
			return ['success' => false, 'error' => 'Невалидный URL. Пример: https://google.com'];
		}

		if (!$this->checkUrlAvailability($url)) {
			return ['success' => false, 'error' => 'Данный URL не доступен'];
		}

		$shortCode = $this->generateShortCode();

		$link = new Link();
		$link->original_url = $url;
		$link->short_code = $shortCode;
		$link->created_at = time();
		$link->updated_at = time();

		if ($link->save()) {
			$shortUrl = Yii::$app->request->hostInfo . '/s/' . $shortCode;
			$qrCodeUrl = $this->generateQrCode($shortUrl);

			return [
				'success' => true,
				'short_url' => $shortUrl,
				'qr_code' => $qrCodeUrl
			];
		}

		return ['success' => false, 'error' => 'Ошибка сохранения ссылки'];
	}

	private function validateUrl($url)
	{
		if (!preg_match('/^https?:\/\//', $url)) {
			$url = 'http://' . $url;
		}
		return filter_var($url, FILTER_VALIDATE_URL) !== false;
	}

	private function checkUrlAvailability($url)
	{
		if (!preg_match('/^https?:\/\//', $url)) {
			$url = 'http://' . $url;
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$ch = null;

		return $httpCode >= 200 && $httpCode < 400;
	}

	private function generateShortCode($length = 6)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$shortCode = '';
		for ($i = 0; $i < $length; $i++) {
			$shortCode .= $characters[rand(0, strlen($characters) - 1)];
		}

		if (Link::findOne(['short_code' => $shortCode])) {
			return $this->generateShortCode($length);
		}

		return $shortCode;
	}

	private function generateQrCode($url)
	{
		// Используем бесплатный QR API
		return 'https://quickchart.io/qr?size=200&text=' . urlencode($url);
	}
}