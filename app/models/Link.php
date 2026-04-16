<?php
namespace app\models;

use yii\db\ActiveRecord;

class Link extends ActiveRecord
{
	public static function tableName()
	{
		return 'links';
	}

	public function rules()
	{
		return [
			[['original_url', 'short_code'], 'required'],
			[['original_url'], 'string', 'max' => 2048],
			[['short_code'], 'string', 'max' => 10],
			[['clicks'], 'integer'],
			[['short_code'], 'unique'],
		];
	}
}