<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

class Link extends ActiveRecord
{
	public static function tableName(): string
	{
		return 'links';
	}

	public function rules(): array
	{
		return [
			[['original_url', 'short_code'], 'required'],
			[['original_url'], 'string', 'max' => 2048],
			[['short_code'], 'string', 'max' => 10],
			[['clicks'], 'integer'],
			[['short_code'], 'unique'],
		];
	}

	public function beforeSave($insert): bool
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				$this->created_at = time();
			}
			$this->updated_at = time();
			return true;
		}
		return false;
	}
}