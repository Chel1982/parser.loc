<?php
namespace app\commands;

use app\models\Goods;
use app\models\Groups;
use app\models\ImagesImkuh;
use app\models\Manufacturer;
use app\models\MarkUpGoods;
use app\models\ProductsImkuh;
use app\models\Sites;
use SplFileInfo;
use yii\console\Controller;
use Exception;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ExportGoodsController extends Controller
{
    public function actionInit()
    {
        if (MarkUpGoods::find()->where(['is not', 'categories_imkuh_id', NULL])->orWhere(['is not', 'categories_holodbar_id', NULL])->orWhere(['is not', 'manufacturer_id_imkuh', NULL])->orWhere(['is not', 'manufacturer_id_holodbar', NULL])->exists()){

            /* Для группы товаров сайта Imkuh */
            if (MarkUpGoods::find()->where(['is not', 'categories_imkuh_id', NULL])->exists()){

                $exportsImkuh = MarkUpGoods::find()->where(['is not', 'categories_imkuh_id', NULL])->asArray()->all();

                foreach ($exportsImkuh as $exportImkuh){

                    /* Выбираем id группы товаров */
                    $idGroups = Groups::find()->where(['categories_imkuh_id' => $exportImkuh['categories_imkuh_id']])->select('id');

                    /* Проверяем налчие товара */
                    $goods = Goods::find()
                        ->where(['groups_id' => $idGroups])
                        ->andWhere(['availability' => 1])
                        ->andWhere(['not', ['mark_up_price' => NULL]])
                        ->andWhere(['not', ['mark_up_price' => 0]])
                        ->with(['descriptions', 'images','productAttributes', 'groups'  => function($query) {$query->with('categoriesImkuh');}])
                        ->asArray()
                        ->all();

                    ProductsImkuh::updateAll(
                        ['in_case' => 3, 'on_off' => 0], ['parser_status' => '1', 'type' => $exportImkuh['categories_imkuh_id']]
                    );

                    foreach ($goods as $good) {

                        if (ProductsImkuh::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()) {

                            $product = ProductsImkuh::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
                            $product->name = $good['name_goods'];
                            $product->text = $good['descriptions']['main'];
                            $product->text .= $good['descriptions']['additional'];
                            $product->text .= $good['productAttributes']['content'];
                            $product->vendor = ($good['manufacturer'] == NULL) ? '' : $good['manufacturer'];
                            $product->type = $good['groups']['categoriesImkuh']['pgid'];
                            $product->price = $good['mark_up_price'];
                            $good['currency'] == 'RUB' ? $product->currency = 0 : $product->currency = 2;
                            $product->in_case = 0;
                            $product->on_off = 1;
                            $product->parser_status = 1;
                            $product->save();

                        } else {

                            $product = new ProductsImkuh();
                            $product->name = $good['name_goods'];
                            $product->text = $good['descriptions']['main'];
                            $product->text .= $good['descriptions']['additional'];
                            $product->text .= $good['productAttributes']['content'];
                            $product->vendor = ($good['manufacturer'] == NULL) ? '' : $good['manufacturer'];
                            $product->type = $good['groups']['categoriesImkuh']['pgid'];
                            $product->price = $good['mark_up_price'];
                            $good['currency'] == 'RUB' ? $product->currency = 0 : $product->currency = 2;
                            $product->in_case = 0;
                            $product->on_off = 1;
                            $product->parser_status = 1;
                            $product->save();



                            $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';

                            if (file_exists($path)) {

                                $files = array_diff(scandir($path), array('.', '..'));

                                foreach ($files as $file) {
                                    try {
                                        $info = new SplFileInfo($file);
                                        $ext = $info->getExtension();

                                        $image = new ImagesImkuh();
                                        $image->pid = $product->pid;
                                        $image->main = 0;
                                        $image->save();

                                        $imageId = ImagesImkuh::findOne(['iid' => $image->iid]);
                                        $imageId->image = $image->iid . '.' . $ext;
                                        $imageId->save();

                                        $nameFile = $image->iid . '.' . $ext;

                                        file_put_contents('/var/www/imkuhru/data/www/test18.imkuh.ru/images/products/' . $nameFile, file_get_contents($path . $file));

                                    } catch (Exception $e) {
                                        echo $e;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            /* Для группы товаров сайта Holodbar */
//            if (MarkUpGoods::find()->where(['is not', 'categories_holodbar_id', NULL])->exists()){
//
//                /* Выбираем товары из групп */
//                $idCatImkuh = MarkUpGoods::find()->where(['is not', 'categories_holodbar_id', NULL])->select('categories_holodbar_id');
//                $idGroups = Groups::find()->where(['categories_holodbar_id' => $idCatImkuh])->select('id');
//                $idGoods = Goods::find()->where(['groups_id' => $idGroups])->select('id');
//
//                /* Проверяем налчие товара */
//                $idGoodsAvail = Availability::find()->where(['goods_id' => $idGoods])->andWhere(['availability' => 1])->select('goods_id');
//                $idGoodsPrice = Price::find()->where(['goods_id' => $idGoodsAvail])->andWhere(['not', ['mark_up_price' => null]])->andWhere(['not', ['mark_up_price' => 0]])->select('goods_id');
//
//                $goods = Goods::find()
//                    ->where(['id' => $idGoodsPrice])
//                    ->with(['descriptions', 'images', 'manufacturers', 'prices','productAttributes', 'groups'  => function($query) {$query->with('categoriesImkuh');}])
//                    ->asArray()
//                    ->all();
//
//                ProductsImkuh::updateAll(
//                    ['in_case' => 3, 'on_off' => 0], 'parser_status = 1'
//                );
//
//                foreach ($goods as $good){
//
//                    if (ProductsImkuh::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()){
//
//                        $product = ProductsImkuh::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
//                        $product->name = $good['name_goods'];
//                        $product->text = $good['descriptions']['main'];
//                        $product->text .= $good['descriptions']['additional'];
//                        $product->text .= $good['productAttributes']['content'];
//                        $product->vendor = ($good['manufacturers']['name'] == NULL) ? '' : $good['manufacturers']['name'];
//                        $product->type = $good['groups']['categoriesImkuh']['pgid'];
//                        $product->price = $good['prices']['mark_up_price'];
//                        $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
//                        $product->in_case = 0;
//                        $product->on_off = 1;
//                        $product->parser_status = 1;
//                        $product->save();
//
//                    }else{
//
//                        $product = new ProductsImkuh();
//                        $product->name = $good['name_goods'];
//                        $product->text = $good['descriptions']['main'];
//                        $product->text .= $good['descriptions']['additional'];
//                        $product->text .= $good['productAttributes']['content'];
//                        $product->vendor = ($good['manufacturers']['name'] == NULL) ? '' : $good['manufacturers']['name'];
//                        $product->type = $good['groups']['categoriesImkuh']['pgid'];
//                        $product->price = $good['prices']['mark_up_price'];
//                        $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
//                        $product->in_case = 0;
//                        $product->on_off = 1;
//                        $product->parser_status = 1;
//                        $product->save();
//
//                        $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';
//
//                        if (file_exists($path)) {
//
//                            $files = array_diff(scandir($path), array('.', '..'));
//
//                            foreach ($files as $file) {
//
//                                $info = new SplFileInfo($file);
//                                $ext = $info->getExtension();
//
//                                $image = new ImagesImkuh();
//                                $image->pid = $product->pid;
//                                $image->main = 0;
//                                $image->save();
//
//                                $imageId = ImagesImkuh::findOne(['iid' => $image->iid]);
//                                $imageId->image = $image->iid . '.' . $ext;
//                                $imageId->save();
//
//                                $nameFile = $image->iid . '.' . $ext;
//                                ///var/www/import18imkuhru/data/www/import18.imkuh.ru/images/products/
//                                file_put_contents('/var/www/import18imkuhru/data/www/import18.imkuh.ru/images/products/' . $nameFile, file_get_contents($path . $file));
//
//                            }
//                        }
//                    }
//                }
//            }

            /* Для производителя сайта Imkuh */
            if (MarkUpGoods::find()->where(['is not', 'manufacturer_id_imkuh', NULL])->exists()){

                /* Выбираем товары из групп */
                $idCatImkuh = MarkUpGoods::find()->where(['is not', 'manufacturer_id_imkuh', NULL])->select('manufacturer_id_imkuh');

                $urlManuf = Manufacturer::find()->where(['id' => $idCatImkuh])->select('sites_url');

                $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                /* Проверяем наличие товара */
                $goods = Goods::find()
                    ->where(['sites_id' => $idSites])
                    ->andWhere(['availability' => 1])
                    ->andWhere(['not', ['mark_up_price' => NULL]])
                    ->andWhere(['not', ['mark_up_price' => 0]])
                    ->with(['descriptions', 'images', 'manufacturers','productAttributes'])
                    ->asArray()
                    ->all();

                ProductsImkuh::updateAll(
                    ['in_case' => 3, 'on_off' => 0], ['parser_status' => '1', 'type' => 0]
                );

                foreach ($goods as $good){

                    if (ProductsImkuh::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()){

                        $product = ProductsImkuh::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
                        $product->name = $good['name_goods'];
                        $product->text = $good['descriptions']['main'];
                        $product->text .= $good['descriptions']['additional'];
                        $product->text .= $good['productAttributes']['content'];
                        $product->vendor = ($good['manufacturer'] == NULL) ? '' : $good['manufacturer'];
                        $product->type = 0;
                        $product->price = $good['mark_up_price'];
                        $good['currency'] == 'RUB' ? $product->currency = 0 : $product->currency = 2;
                        $product->in_case = 0;
                        $product->on_off = 1;
                        $product->parser_status = 1;
                        $product->save();

                    }else{

                        $product = new ProductsImkuh();
                        $product->name = $good['name_goods'];
                        $product->text = $good['descriptions']['main'];
                        $product->text .= $good['descriptions']['additional'];
                        $product->text .= $good['productAttributes']['content'];
                        $product->vendor = ($good['manufacturer'] == NULL) ? '' : $good['manufacturer'];
                        $product->type = 0;
                        $product->price = $good['mark_up_price'];
                        $good['currency'] == 'RUB' ? $product->currency = 0 : $product->currency = 2;
                        $product->in_case = 0;
                        $product->on_off = 1;
                        $product->parser_status = 1;
                        $product->save();

                        $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';

                        if (file_exists($path)) {

                            $files = array_diff(scandir($path), array('.', '..'));

                            foreach ($files as $file) {
                                try{
                                    $info = new SplFileInfo($file);
                                    $ext = $info->getExtension();

                                    $image = new ImagesImkuh();
                                    $image->pid = $product->pid;
                                    $image->main = 0;
                                    $image->save();

                                    $imageId = ImagesImkuh::findOne(['iid' => $image->iid]);
                                    $imageId->image = $image->iid . '.' . $ext;
                                    $imageId->save();

                                    $nameFile = $image->iid . '.' . $ext;

                                    file_put_contents('/var/www/imkuhru/data/www/test18.imkuh.ru/images/products/' . $nameFile, file_get_contents($path . $file));
                                }catch (Exception $e){
                                    echo $e;
                                }


                            }
                        }
                    }
                }
            }

            /* Для производителя сайта Holodbar */
//            if (MarkUpGoods::find()->where(['is not', 'manufacturer_id_holodbar', NULL])->exists()){
//
//                /* Выбираем товары из групп */
//                $idCatImkuh = MarkUpGoods::find()->where(['is not', 'manufacturer_id_holodbar', NULL])->select('manufacturer_id_holodbar');
//
//                $idGoods = ManufacturerHasGoods::find()->where(['manufacturer_id' => $idCatImkuh])->select('goods_id');
//
//                /* Проверяем налчие товара */
//                $idGoodsAvail = Availability::find()->where(['goods_id' => $idGoods])->andWhere(['availability' => 1])->select('goods_id');
//                $idGoodsPrice = Price::find()->where(['goods_id' => $idGoodsAvail])->andWhere(['not', ['mark_up_price' => null]])->andWhere(['not', ['mark_up_price' => 0]])->select('goods_id');
//
//                $goods = Goods::find()
//                    ->where(['id' => $idGoodsPrice])
//                    ->with(['descriptions', 'images', 'manufacturers', 'prices','productAttributes', 'groups'  => function($query) {$query->with('categoriesImkuh');}])
//                    ->asArray()
//                    ->all();
//
//                ProductsImkuh::updateAll(
//                    ['in_case' => 3, 'on_off' => 0], 'parser_status = 1'
//                );
//
//                foreach ($goods as $good){
//
//                    if (ProductsImkuh::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()){
//
//                        $product = ProductsImkuh::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
//                        $product->name = $good['name_goods'];
//                        $product->text = $good['descriptions']['main'];
//                        $product->text .= $good['descriptions']['additional'];
//                        $product->text .= $good['productAttributes']['content'];
//                        $product->vendor = ($good['manufacturers']['name'] == NULL) ? '' : $good['manufacturers']['name'];
//                        $product->type = $good['groups']['categoriesImkuh']['pgid'];
//                        $product->price = $good['prices']['mark_up_price'];
//                        $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
//                        $product->in_case = 0;
//                        $product->on_off = 1;
//                        $product->parser_status = 1;
//                        $product->save();
//
//                    }else{
//
//                        $product = new ProductsImkuh();
//                        $product->name = $good['name_goods'];
//                        $product->text = $good['descriptions']['main'];
//                        $product->text .= $good['descriptions']['additional'];
//                        $product->text .= $good['productAttributes']['content'];
//                        $product->vendor = ($good['manufacturers']['name'] == NULL) ? '' : $good['manufacturers']['name'];
//                        $product->type = $good['groups']['categoriesImkuh']['pgid'];
//                        $product->price = $good['prices']['mark_up_price'];
//                        $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
//                        $product->in_case = 0;
//                        $product->on_off = 1;
//                        $product->parser_status = 1;
//                        $product->save();
//
//                        $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';
//
//                        if (file_exists($path)) {
//
//                            $files = array_diff(scandir($path), array('.', '..'));
//
//                            foreach ($files as $file) {
//
//                                $info = new SplFileInfo($file);
//                                $ext = $info->getExtension();
//
//                                $image = new ImagesImkuh();
//                                $image->pid = $product->pid;
//                                $image->main = 0;
//                                $image->save();
//
//                                $imageId = ImagesImkuh::findOne(['iid' => $image->iid]);
//                                $imageId->image = $image->iid . '.' . $ext;
//                                $imageId->save();
//
//                                $nameFile = $image->iid . '.' . $ext;
//                                ///var/www/import18imkuhru/data/www/import18.imkuh.ru/images/products/
//                                /// /var/www/imkuhru/data/www/imkuh.ru/images/products/
//                                file_put_contents('/var/www/import18imkuhru/data/www/import18.imkuh.ru/images/products/' . $nameFile, file_get_contents($path . $file));
//
//                            }
//                        }
//                    }
//                }
//            }
        }
    }
}