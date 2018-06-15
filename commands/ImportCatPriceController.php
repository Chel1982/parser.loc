<?php

namespace app\commands;

use app\models\Config;
use app\models\Currency;
use app\models\Goods;
use app\models\Groups;
use app\models\Manufacturer;
use app\models\MarkUpGoods;
use Exception;
use app\models\Sites;
use Yii;
use yii\console\Controller;
use yii\db\Expression;


class ImportCatPriceController extends Controller
{
    public function actionInit()
    {
        $sites = Sites::find()->where(['status_cat_price' => 1])->asArray()->all();

        foreach ($sites as $site) {

            Goods::updateAll(
                ['price' => NULL, 'mark_up_price' => NULL, 'availability' => 0], ['sites_id' => $site['id']]
            );

            $path = Yii::getAlias("@app/web/uploads/catalogs_price/21/");

            if (is_dir($path)) {

                $files = array_diff(scandir($path), array('.', '..'));

                foreach ($files as $file) {

                    try {
                        $inputFile = $path . $file;
                        $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFile);

                    } catch (Exception $e) {
                        echo $e;
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    for ($row = 12; $row <= $highestRow; $row++) {

                        $rowData = $sheet->rangeToArray('C' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                        if (Goods::find()->where(['name_goods' => $rowData[0][0]])->exists()) {

                            $goods = Goods::findOne(['name_goods' => $rowData[0][0]]);

                            if ($rowData[0][9] === 'EUR' && is_numeric($rowData[0][10])) {

                                $cur = Currency::findOne(['name' => 'EUR']);

                                $goods->price = $rowData[0][6];
                                $goods->currency_id = $cur->id;
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            } elseif ($rowData[0][9] === 'руб.' && is_numeric($rowData[0][10])) {

                                $cur = Currency::findOne(['name' => 'RUB']);

                                $goods->price_rub = $rowData[0][6];
                                $goods->price = $rowData[0][6];
                                $goods->currency_id = $cur->id;
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            } elseif ($rowData[0][8] === 'EUR' && is_numeric($rowData[0][9])) {

                                $cur = Currency::findOne(['name' => 'EUR']);

                                $goods->price = $rowData[0][5];
                                $goods->currency_id = $cur->id;
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            } elseif ($rowData[0][8] === 'руб.' && is_numeric($rowData[0][9])) {

                                $cur = Currency::findOne(['name' => 'RUB']);

                                $goods->price_rub = $rowData[0][5];
                                $goods->price = $rowData[0][5];
                                $goods->currency_id = $cur->id;
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            }
                        }

                    }
                }
            }
        }

        Goods::updateAll(
            ['mark_up_price' => NULL]
        );

        $curEUR = Config::findOne(['alias' => 'euro'])->value;

        /* Переводим цены из евро в рубли */
        Goods::updateAll(
            ['price_rub' => new Expression('ROUND(price * ' . $curEUR . ')')], ['currency_id' => 2]
        );

        $curUSD = Config::findOne(['alias' => 'dollar'])->value;

        /* Переводим цены из доллара в рубли */
        Goods::updateAll(
            ['price_rub' => new Expression('ROUND(price * ' . $curUSD . ')')], ['currency_id' => 3]
        );

        $markUpPercent = MarkUpGoods::find()->where(['percent' => 1])->asArray()->all();
        $markUpAbsolute = MarkUpGoods::find()->where(['absolute' => 1])->asArray()->all();

        /*
            Делаем процентную наценку
        */
        foreach ($markUpPercent as $markPer) {

            $markPerFrom = $markPer['from_value'];
            $markPerTo = $markPer['to_value'];

            $percent = $markPer['price_value'] / 100;

            /* Наценка на группы товаров Imkih */
            if (isset($markPer['categories_imkuh_id'])) {

                /* Выбираем товары из групп */

                $idGroups = Groups::find()->where(['categories_imkuh_id' => $markPer['categories_imkuh_id']])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub */
                $goodsIdPriceRub = Goods::find()
                    ->where(['groups_id' => $idGroups])
                    ->andWhere(['>=', 'price_rub', $markPerFrom])
                    ->andWhere(['<=', 'price_rub', $markPerTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub * ' . $percent . ' + price_rub)') ], ['id' => $goodsIdPriceRub]
                );
            }

            /* Наценка на группы товаров Holodbar */
            if (isset($markPer['categories_holodbar_id'])) {

                /* Выбираем товары из групп */

                $idGroups = Groups::find()->where(['categories_holodbar_id' => $markPer['categories_holodbar_id']])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub*/
                $goodsIdPriceRub = Goods::find()
                    ->where(['groups_id' => $idGroups])
                    ->andWhere(['>=', 'price_rub', $markPerFrom])
                    ->andWhere(['<=', 'price_rub', $markPerTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub * ' . $percent . ' + price_rub)') ], ['id' => $goodsIdPriceRub]
                );
            }

            /* Наценка на производителя для Imkuh */
            if (isset($markPer['manufacturer_id_imkuh'])) {

                $idCatImkuh = MarkUpGoods::find()->where(['is not', 'manufacturer_id_imkuh', NULL])->select('manufacturer_id_imkuh');

                $urlManuf = Manufacturer::find()->where(['id' => $idCatImkuh])->select('sites_url');

                $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub*/
                $goodsIdPriceRub = Goods::find()
                    ->where(['sites_id' => $idSites])
                    ->andWhere(['>=', 'price_rub', $markPerFrom])
                    ->andWhere(['<=', 'price_rub', $markPerTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub * ' . $percent . ' + price_rub)') ], ['id' => $goodsIdPriceRub]
                );
            }

            /* Наценка на производителя для Holidbar */
            if (isset($markPer['manufacturer_id_holodbar'])) {

                $idGoodsHolod = MarkUpGoods::find()->where(['is not', 'manufacturer_id_holodbar', NULL])->select('manufacturer_id_holodbar');

                $urlManuf = Manufacturer::find()->where(['id' => $idGoodsHolod])->select('sites_url');

                $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub*/
                $goodsIdPriceRub = Goods::find()
                    ->where(['sites_id' => $idSites])
                    ->andWhere(['>=', 'price_rub', $markPerFrom])
                    ->andWhere(['<=', 'price_rub', $markPerTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub * ' . $percent . ' + price_rub)') ], ['id' => $goodsIdPriceRub]
                );
            }

        }

        /*
            Делаем наценку абсолюной величины
        */
        foreach ($markUpAbsolute as $markAbs) {

            $markAbsFrom = $markAbs['from_value'];
            $markAbsTo = $markAbs['to_value'];

            /* Наценка на группы товаров Imkih */
            if (isset($markAbs['categories_imkuh_id'])) {

                /* Выбираем товары из групп */
                $idGroups = Groups::find()->where(['categories_imkuh_id' => $markAbs['categories_imkuh_id']])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub */
                $goodsIdPriceRub = Goods::find()
                    ->where(['groups_id' => $idGroups])
                    ->andWhere(['>=', 'price_rub', $markAbsFrom])
                    ->andWhere(['<=', 'price_rub', $markAbsTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value'] . ')') ], ['id' => $goodsIdPriceRub]
                );
            }

            /* Наценка на группы товаров Holodbar */
            if (isset($markAbs['categories_holodbar_id'])) {

                /* Выбираем товары из групп */
                $idGroups = Groups::find()->where(['categories_holodbar_id' => $markAbs['categories_holodbar_id']])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub */
                $goodsIdPriceRub = Goods::find()
                    ->where(['groups_id' => $idGroups])
                    ->andWhere(['>=', 'price_rub', $markAbsFrom])
                    ->andWhere(['<=', 'price_rub', $markAbsTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value']) . ')'], ['id' => $goodsIdPriceRub]
                );
            }

            /* Наценка на производителя для Imkuh */
            if (isset($markAbs['manufacturer_id_imkuh'])) {

                $idCatImkuh = MarkUpGoods::find()->where(['is not', 'manufacturer_id_imkuh', NULL])->select('manufacturer_id_imkuh');

                $urlManuf = Manufacturer::find()->where(['id' => $idCatImkuh])->select('sites_url');

                $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub */
                $goodsIdPriceRub = Goods::find()
                    ->where(['sites_id' => $idSites])
                    ->andWhere(['>=', 'price_rub', $markAbsFrom])
                    ->andWhere(['<=', 'price_rub', $markAbsTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value']) . ')'], ['id' => $goodsIdPriceRub]
                );

            }

            /* Наценка на производителя для Holodbar */
            if (isset($markAbs['manufacturer_id_holodbar'])) {

                $idCatHolod = MarkUpGoods::find()->where(['is not', 'manufacturer_id_holodbar', NULL])->select('manufacturer_id_holodbar');

                $urlManuf = Manufacturer::find()->where(['id' => $idCatHolod])->select('sites_url');

                $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                /* Ищем товары в диапозоне цен поля price_rub */
                $goodsIdPriceRub = Goods::find()
                    ->where(['sites_id' => $idSites])
                    ->andWhere(['>=', 'price_rub', $markAbsFrom])
                    ->andWhere(['<=', 'price_rub', $markAbsTo])
                    ->select('id')->asArray()->all();

                /* Делаем наценку на товар */
                Goods::updateAll(
                    ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value'] . ')') ], ['id' => $goodsIdPriceRub]
                );
            }

        }


    }

}