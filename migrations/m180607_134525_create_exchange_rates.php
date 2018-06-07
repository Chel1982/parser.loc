<?php

use yii\db\Migration;

/**
 * Class m180607_134525_create_exchange_rates
 */
class m180607_134525_create_exchange_rates extends Migration
{

    public function up()
    {
        $this->createTable('exchange_rates', [
            'id' => $this->primaryKey(),
            'dollar' => $this->float(),
            'euro' => $this->float()
        ]);
    }

    public function down()
    {
        $this->dropTable('exchange_rates');
    }

}
