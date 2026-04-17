<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Link;
use app\models\ClicksLog;

class ShortController extends Controller
{
	public function actionIndex(string $code)
	{
		$link = Link::findOne(['short_code' => $code]);

		if (!$link) {
			throw new NotFoundHttpException('Ссылка не найдена');
		}

		$log = new ClicksLog();
		$log->link_id = $link->id;
		$log->ip_address = Yii::$app->request->userIP ?? '0.0.0.0';
		$log->user_agent = Yii::$app->request->userAgent ?? '';
		$log->clicked_at = time();
		$log->save();

		$link->clicks++;
		$link->save();

		return $this->redirect($link->original_url);
	}
}