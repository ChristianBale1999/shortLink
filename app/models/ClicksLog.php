<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

class ClicksLog extends ActiveRecord
{
	public static function tableName(): string
	{
		return 'clicks_log';
	}

	public function rules(): array
	{
		return [
			[['link_id', 'ip_address', 'clicked_at'], 'required'],
			[['link_id', 'clicked_at'], 'integer'],
			[['ip_address'], 'string', 'max' => 45],
			[['user_agent'], 'string', 'max' => 255],
		];
	}
}