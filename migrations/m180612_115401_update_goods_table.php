<?php

use yii\db\Migration;

/**
 * Class m180612_115401_update_goods_table
 */
class m180612_115401_update_goods_table extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

        $this->dropColumn('goods','currency');

        $this->addColumn('goods','price_rub','FLOAT NULL DEFAULT NULL AFTER mark_up_price');

        $this->addCommentOnColumn('goods','price_rub','Цена в рублях по курсу');

        $this->addCommentOnColumn('goods','price','Цена в валюте без наценки');

        $this->addCommentOnColumn('goods','mark_up_price','Наценка');

        $this->addColumn('goods','currency_id','INTEGER DEFAULT 1 AFTER availability');

        $this->createIndex('fk_goods_currency1_idx','goods','currency_id');

        $this->addForeignKey('fk_goods_currency1','goods','currency_id', 'currency','id','CASCADE','CASCADE');

    }

    public function down()
    {
        $this->dropColumn('goods','price_rub');

        $this->addColumn('goods','currency', 'VARCHAR(255) AFTER mark_up_price');

        $this->dropForeignKey('fk_goods_currency1','goods');

        $this->dropIndex('fk_goods_currency1_idx','goods');

        $this->dropColumn('goods','currency_id');
    }

}
