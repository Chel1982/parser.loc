<?php

namespace app\commands;

use app\models\Goods;
use app\models\Groups;
use app\models\ManufacturerHasGoods;
use app\models\MarkUpGoods;
use Exception;
use app\models\Sites;
use Yii;
use yii\console\Controller;


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

                    try{
                        $inputFile = $path . $file;
                        $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFile);

                    }catch (Exception $e){
                        echo $e;
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    for($row = 12; $row <= $highestRow; $row++){

                        $rowData = $sheet->rangeToArray('C'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);

                        if (Goods::find()->where(['name_goods' => $rowData[0][0]])->exists()){

                            $goods = Goods::findOne(['name_goods' => $rowData[0][0]]);

                            if ($rowData[0][9] === 'EUR' && is_numeric($rowData[0][10])){

                                $goods->price = $rowData[0][6];
                                $goods->currency = 'EUR';
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            }elseif($rowData[0][9] === 'руб.' && is_numeric($rowData[0][10])){

                                $goods->price = $rowData[0][6];
                                $goods->currency = 'RUB';
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            }elseif($rowData[0][8] === 'EUR' && is_numeric($rowData[0][9])){

                                $goods->price = $rowData[0][5];
                                $goods->currency = 'EUR';
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            }elseif($rowData[0][8] === 'руб.' && is_numeric($rowData[0][9])){

                                $goods->price = $rowData[0][5];
                                $goods->currency = 'RUB';
                                $goods->availability = 1;
                                $goods->updated_at = date('Y-m-d H:i:s');
                                $goods->save();

                            }
                        }

                    }
                }
            }
        }

        /* Наценки на все товары */
        $markUpPercent = MarkUpGoods::find()->where(['percent' => 1])->asArray()->all();
        $markUpAbsolute = MarkUpGoods::find()->where(['absolute' => 1])->asArray()->all();

        if (empty($markUpPercent) && empty($markUpAbsolute)){
            $resMarkUp = 'Задайте хотя бы 1 наценку на товар';

            return $this->render('index',[
                'resMarkUp' => $resMarkUp
            ]);
        }

        /* Делаем процентную наценку */
        foreach ($markUpPercent as $markPer) {

            $markPerFrom = $markPer['from_value'];
            $markPerTo = $markPer['to_value'];

            $percent = $markPer['price_value'] / 100;

            /* Наценка на группы товаров Imkih */
            if (isset($markPer['categories_imkuh_id'])) {

                /* Выбираем товары из групп */

                $idGroups = Groups::find()->where(['categories_imkuh_id' => $markPer['categories_imkuh_id']])->select('id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markPerFrom])->andWhere(['<=', 'price', $markPerTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = round($goodsP->price * $percent + $goodsP->price );
                    $goodsP->save();

                }
            }

            /* Наценка на группы товаров Holodbar */
            if (isset($markPer['categories_holodbar_id'])) {

                /* Выбираем товары из групп */

                $idGroups = Groups::find()->where(['categories_holodbar_id' => $markPer['categories_holodbar_id']])->select('id');
                $idGoods = Goods::find()->where(['groups_id' => $idGroups])->select('id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markPerFrom])->andWhere(['<=', 'price', $markPerTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = round($goodsP->price * $percent + $goodsP->price );
                    $goodsP->save();

                }
            }

            /* Наценка на производителя для Imkuh */
            if (isset($markPer['manufacturer_id_imkuh'])) {

                $idGoodsMan = ManufacturerHasGoods::find()->where(['manufacturer_id' => $markPer['manufacturer_id_imkuh']])->select('goods_id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['id' => $idGoodsMan])->andWhere(['>=', 'price', $markPerFrom])->andWhere(['<=', 'price', $markPerTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = round($goodsP->price * $percent + $goodsP->price );
                    $goodsP->save();

                }

            }

            /* Наценка на производителя для Holidbar */
            if (isset($markPer['manufacturer_id_holodbar'])) {

                $idGoodsMan = ManufacturerHasGoods::find()->where(['manufacturer_id' => $markPer['manufacturer_id_holodbar']])->select('goods_id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['id' => $idGoodsMan])->andWhere(['>=', 'price', $markPerFrom])->andWhere(['<=', 'price', $markPerTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = round($goodsP->price * $percent + $goodsP->price );
                    $goodsP->save();

                }
            }
        }

        /* Делаем наценку абсолюной величины */
        foreach ($markUpAbsolute as $markAbs) {

            $markAbsFrom = $markAbs['from_value'];
            $markAbsTo = $markAbs['to_value'];

            /* Наценка на группы товаров Imkih */
            if (isset($markAbs['categories_imkuh_id'])) {

                /* Выбираем товары из групп */

                $idGroups = Groups::find()->where(['categories_imkuh_id' => $markAbs['categories_imkuh_id']])->select('id');

                /* Ищем товары в диапозоне цен */
                $idPriceGoods = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markAbsFrom])->andWhere(['<=', 'price', $markAbsTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($idPriceGoods);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = $goodsP->price + $markAbs['price_value'];
                    $goodsP->save();

                }
            }

            /* Наценка на группы товаров Holodbar */
            if (isset($markAbs['categories_holodbar_id'])) {

                /* Выбираем товары из групп */
                $idGroups = Groups::find()->where(['categories_holodbar_id' => $markAbs['categories_holodbar_id']])->select('id');

                /* Ищем товары в диапозоне цен */
                $idPriceGoods = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markAbsFrom])->andWhere(['<=', 'price', $markAbsTo])->select('id');

                $goodsPer = Goods::findAll($idPriceGoods);

                /* Делаем наценку на товар */
                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = $goodsP->price + $markAbs['price_value'];
                    $goodsP->save();

                }
            }

            /* Наценка на производителя для Imkuh */
            if (isset($markAbs['manufacturer_id_imkuh'])) {

                $idGoodsMan = ManufacturerHasGoods::find()->where(['manufacturer_id' => $markAbs['manufacturer_id_imkuh']])->select('goods_id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['id' => $idGoodsMan])->andWhere(['>=', 'price', $markAbsFrom])->andWhere(['<=', 'price', $markAbsTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = $goodsP->price + $markAbs['price_value'];
                    $goodsP->save();

                }

            }

            /* Наценка на производителя для Holodbar */
            if (isset($markAbs['manufacturer_id_holodbar'])) {

                $idGoodsMan = ManufacturerHasGoods::find()->where(['manufacturer_id' => $markAbs['manufacturer_id_holodbar']])->select('goods_id');

                /* Ищем товары в диапозоне цен */
                $goodsId = Goods::find()->where(['id' => $idGoodsMan])->andWhere(['>=', 'price', $markAbsFrom])->andWhere(['<=', 'price', $markAbsTo])->select('id');

                /* Делаем наценку на товар */
                $goodsPer = Goods::findAll($goodsId);

                foreach ($goodsPer as $goodsP) {

                    $goodsP->mark_up_price = $goodsP->price + $markAbs['price_value'];
                    $goodsP->save();

                }
            }
        }
    }

}