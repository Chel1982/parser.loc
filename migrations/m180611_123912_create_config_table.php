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
        $this->createTable('config', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255),
            'value' => $this->string(255)
        ]);

        $this->batchInsert('config', ['alias', 'value'], [
            ['euro', '73.06'],
            ['dollar', '61.81'],
            ['name_user', 'admin'],
            ['password', 'fasfw123'],
            ['authKey', 'test100key'],
            ['accessToken', '100-token'],
            ['id', '100'],
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
