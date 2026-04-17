<?php
namespace app\components;

use Yii;
use yii\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleComponent extends Component
{
	public $baseUrl = '';
	public $timeout = 30;
	public $verifySsl = false;

	private $client;

	public function init()
	{
		parent::init();

		$this->client = new Client([
			'base_uri' => $this->baseUrl,
			'timeout' => $this->timeout,
			'verify' => $this->verifySsl,
			'headers' => [
				'User-Agent' => 'Shortlink-Service/1.0',
			],
		]);
	}

	public function get($url, $params = [])
	{
		try {
			$response = $this->client->get($url, ['query' => $params]);
			return [
				'success' => true,
				'data' => json_decode($response->getBody(), true),
				'statusCode' => $response->getStatusCode(),
			];
		} catch (GuzzleException $e) {
			Yii::error("Guzzle GET error: " . $e->getMessage());
			return [
				'success' => false,
				'error' => $e->getMessage(),
			];
		}
	}

	public function post($url, $data = [])
	{
		try {
			$response = $this->client->post($url, ['json' => $data]);
			return [
				'success' => true,
				'data' => json_decode($response->getBody(), true),
				'statusCode' => $response->getStatusCode(),
			];
		} catch (GuzzleException $e) {
			Yii::error("Guzzle POST error: " . $e->getMessage());
			return [
				'success' => false,
				'error' => $e->getMessage(),
			];
		}
	}
}