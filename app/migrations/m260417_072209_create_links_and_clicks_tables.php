<?php

declare(strict_types=1);

use yii\db\Migration;

final class m000000_000001_create_links_and_clicks_tables extends Migration
{
	public function up(): void
	{
		// Таблица ссылок
		$this->createTable('links', [
			'id' => $this->primaryKey(),
			'original_url' => $this->string(2048)->notNull(),
			'short_code' => $this->string(10)->notNull()->unique(),
			'clicks' => $this->integer()->defaultValue(0),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);

		$this->createIndex('idx_short_code', 'links', 'short_code');

		// Таблица логов переходов
		$this->createTable('clicks_log', [
			'id' => $this->primaryKey(),
			'link_id' => $this->integer()->notNull(),
			'ip_address' => $this->string(45)->notNull(),
			'user_agent' => $this->string(255),
			'clicked_at' => $this->integer()->notNull(),
		]);

		$this->addForeignKey(
			'fk_clicks_log_link_id',
			'clicks_log',
			'link_id',
			'links',
			'id',
			'CASCADE'
		);

		$this->createIndex('idx_link_id', 'clicks_log', 'link_id');
	}

	public function down(): void
	{
		$this->dropForeignKey('fk_clicks_log_link_id', 'clicks_log');
		$this->dropTable('clicks_log');
		$this->dropTable('links');
	}
}