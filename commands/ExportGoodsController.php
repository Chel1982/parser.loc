<?php
namespace app\commands;

use app\models\Availability;
use app\models\Goods;
use app\models\Groups;
use app\models\ImagesHolodbar;
use app\models\ImagesImkuh;
use app\models\Price;
use app\models\ProductsHolodbar;
use app\models\ProductsImkuh;
use SplFileInfo;
use yii\console\Controller;

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
        if (Groups::find()->where(['is not', 'categories_imkuh_id', NULL])->exists()){

            $goodsGroup = Groups::find()->where(['is not', 'categories_imkuh_id', NULL])->select('id');

            $idGoodsGroups = Goods::find()->where(['groups_id' => $goodsGroup])->select('id');

            $idGoodsAvail = Availability::find()->where(['goods_id' => $idGoodsGroups, 'availability' => 1])->select('goods_id');

            $goodsPrice = Price::find()->where(['goods_id' => $idGoodsAvail])->andWhere(['not', ['mark_up_price' => null]])->andWhere(['not', ['mark_up_price' => 0]])->select('goods_id');

            $goods = Goods::find()->where(['id' => $goodsPrice])->with(['descriptions', 'images', 'manufacturers', 'prices','productAttributes', 'groups'  => function($query) {
                $query->with('categoriesImkuh');
            }])->asArray()->all();

            ProductsImkuh::updateAll(
                ['in_case' => 3, 'on_off' => 0], 'parser_status = 1'
            );

            foreach ($goods as $good){

                if (ProductsImkuh::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()){

                    $product = ProductsImkuh::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
                    $product->name = $good['name_goods'];
                    $product->text = $good['descriptions']['main'];
                    $product->text .= $good['descriptions']['additional'];
                    $product->text .= $good['productAttributes']['content'];
                    $product->type = $good['groups']['categoriesImkuh']['pgid'];
                    $product->price = $good['prices']['mark_up_price'];
                    $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
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
                    $product->type = $good['groups']['categoriesImkuh']['pgid'];
                    $product->price = $good['prices']['mark_up_price'];
                    $good['prices']['currency_id'] == 1 ? $product->currency = 0 : $product->currency = 2;
                    $product->in_case = 0;
                    $product->on_off = 1;
                    $product->parser_status = 1;
                    $product->save();

                    $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';

                    if (file_exists($path)) {

                        $files = array_diff(scandir($path), array('.', '..'));

                        foreach ($files as $file) {

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
                            //'/var/www/imkuhru/data/www/imkuh.ru/images/products/'
                            file_put_contents('/var/www/imkuh.ru/images/products/' . $nameFile, file_get_contents($path . $file));

                        }
                    }
                }
            }
        }

        if (Groups::find()->where(['is not', 'categories_holodbar_id', NULL])->exists()){

            $goodsGroup = Groups::find()->where(['is not', 'categories_holodbar_id', NULL])->select('id');

            $idGoodsGroups = Goods::find()->where(['groups_id' => $goodsGroup])->select('id');

            $idGoodsAvail = Availability::find()->where(['goods_id' => $idGoodsGroups, 'availability' => 1])->select('goods_id');

            $goodsPrice = Price::find()->where(['goods_id' => $idGoodsAvail])->andWhere(['not', ['mark_up_price' => null]])->andWhere(['not', ['mark_up_price' => 0]])->select('goods_id');

            $goods = Goods::find()->where(['id' => $goodsPrice])->with(['descriptions', 'images', 'manufacturers', 'prices','productAttributes', 'groups'  => function($query) {
                                                                                                                                                        $query->with('categoriesHolodbar');
            }])->asArray()->all();

            ProductsHolodbar::updateAll(
                ['in_case' => 3, 'on_off' => 0], 'parser_status = 1'
            );

            foreach ($goods as $good){

                if (ProductsHolodbar::find()->where(['parser_status' => 1])->andWhere(['name' => $good['name_goods']])->exists()){

                    $product = ProductsHolodbar::findOne(['parser_status' => 1, 'name' => $good['name_goods']]);
                    $product->name = $good['name_goods'];
                    $product->text = $good['descriptions']['main'];
                    $product->text .= $good['descriptions']['additional'];
                    $product->text .= $good['productAttributes']['content'];
                    $product->type = $good['groups']['categoriesHolodbar']['pgid'];
                    $product->price = $good['prices']['mark_up_price'];
                    $good['prices']['currency_id'] == 1 ? $product->valuta = 0 : $product->valuta = 2;
                    $product->in_case = 0;
                    $product->on_off = 1;
                    $product->parser_status = 1;
                    $product->save();

                }else{

                    $product = new ProductsHolodbar();
                    $product->name = $good['name_goods'];
                    $product->text = $good['descriptions']['main'];
                    $product->text .= $good['descriptions']['additional'];
                    $product->text .= $good['productAttributes']['content'];
                    $product->type = $good['groups']['categoriesHolodbar']['pgid'];
                    $product->price = $good['prices']['mark_up_price'];
                    $good['prices']['currency_id'] == 1 ? $product->valuta = 0 : $product->valuta = 2;
                    $product->in_case = 0;
                    $product->on_off = 1;
                    $product->parser_status = 1;
                    $product->save();

                    $path = \Yii::$app->basePath . '/web/uploads/images/' . $good['id'] . '/';

                    if (file_exists($path)) {

                        $files = array_diff(scandir($path), array('.', '..'));

                        foreach ($files as $file) {

                            $info = new SplFileInfo($file);
                            $ext = $info->getExtension();

                            $image = new ImagesHolodbar();
                            $image->pid = $product->pid;
                            $image->main = 0;
                            $image->sort = 0;
                            $image->save();

                            $imageId = ImagesHolodbar::findOne(['iid' => $image->iid]);
                            $imageId->image = $image->iid . '.' . $ext;
                            $imageId->save();

                            $nameFile = $image->iid . '.' . $ext;
                            //'/var/www/imkuhru/data/www/imkuh.ru/images/products/'
                            file_put_contents('/var/www/holodbar.ru/images/products/' . $nameFile, file_get_contents($path . $file));

                        }
                    }
                }
            }
        }
    }
}