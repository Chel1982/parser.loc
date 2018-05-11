<?php

namespace app\commands;

use app\models\Goods;
use app\models\Price;
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

            $path = Yii::getAlias("@app/web/uploads/catalogs_price/" . $site['id'] . '/');

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

                            if (Price::find()->where(['goods_id' => $goods->id])->exists()){

                                if ($rowData[0][7] === 'EUR'){
                                    $price = Price::findOne(['goods_id' => $goods->id]);
                                    $price->price = $rowData[0][6];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 2;
                                    $price->save();

                                }elseif($rowData[0][7] === 'руб.'){

                                    $price = Price::findOne(['goods_id' => $goods->id]);
                                    $price->price = $rowData[0][6];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 1;
                                    $price->save();
                                }elseif($rowData[0][6] === 'EUR'){
                                    $price = Price::findOne(['goods_id' => $goods->id]);
                                    $price->price = $rowData[0][5];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 2;
                                    $price->save();
                                }elseif($rowData[0][6] === 'руб.'){
                                    $price = Price::findOne(['goods_id' => $goods->id]);
                                    $price->price = $rowData[0][5];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 1;
                                    $price->save();
                                }

                            }else{

                                if ($rowData[0][7] === 'EUR'){
                                    $price = new Price();
                                    $price->price = $rowData[0][6];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 2;
                                    $price->save();

                                }elseif($rowData[0][7] === 'руб.'){
                                    $price = new Price();
                                    $price->price = $rowData[0][6];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 1;
                                    $price->save();
                                }elseif ($rowData[0][6] === 'EUR'){
                                    $price = new Price();
                                    $price->price = $rowData[0][5];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 2;
                                    $price->save();

                                }elseif($rowData[0][6] === 'руб.'){
                                    $price = new Price();
                                    $price->price = $rowData[0][5];
                                    $price->goods_id = $goods->id;
                                    $price->currency_id = 1;
                                    $price->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}