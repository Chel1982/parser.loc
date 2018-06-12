<?php

use yii\db\Migration;

/**
 * Handles the creation of table `currency`.
 */
class m180612_113408_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)
        ]);

        $this->batchInsert('currency', ['name'], [['RUB'], ['EUR'], ['USD']]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('currency');
    }
}
