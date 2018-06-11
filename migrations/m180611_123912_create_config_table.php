<?php

use yii\db\Migration;

/**
 * Handles the creation of table `config`.
 */
class m180611_123912_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropTable('exchange_rates');

        $this->createTable('config', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255),
            'value' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('config');
    }
}
