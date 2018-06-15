<?php

use yii\db\Migration;

/**
 * Class m180615_092129_update_goods_table
 */
class m180615_092129_update_goods_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->execute('Update goods SET price_rub = price WHERE currency_id = 1');
    }

    public function down()
    {
        $this->execute('Update goods SET price_rub = NULL WHERE currency_id = 1');
    }

}
