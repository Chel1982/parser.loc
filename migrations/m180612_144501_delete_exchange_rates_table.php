<?php

use yii\db\Migration;

/**
 * Class m180612_144501_delete_exchange_rates_table
 */
class m180612_144501_delete_exchange_rates_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropTable('exchange_rates');
    }

    public function down()
    {
        $this->createTable('exchange_rates', [
            'id' => $this->primaryKey(),
            'dollar' => $this->float(),
            'euro' => $this->float()
        ]);
    }

}
