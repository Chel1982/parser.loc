<?php

namespace app\controllers;

use app\models\Availability;
use app\models\CategoriesHolodbar;
use app\models\CategoriesImkuh;
use app\models\Goods;
use app\models\Groups;
use app\models\ManufacturerHasGoods;
use app\models\MarkUpGoods;
use app\models\Price;
use app\models\ProductGroupsHolodbar;
use app\models\ProductGroupsImkuh;
use app\models\ProductsImkuh;
use app\models\Sites;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'index', 'sites' ,'logout','error'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (\Yii::$app->request->post()){

            if (\Yii::$app->request->post('imkush')){

                $resСompareImkuh = [];

                $prodGrsIm = ProductGroupsImkuh::find()->select(['pgid','pmgid','name'])->asArray()->all();
                $prodCatsIm = CategoriesImkuh::find()->select(['pgid','pmgid','name'])->asArray()->all();

                $countImkuh = count($prodGrsIm);

                $resСompareImkuh['count'] = $countImkuh;

                $ch = 0;

                foreach ($prodGrsIm as $keyGr => $valGr) {

                    foreach ($prodCatsIm as $keyCat => $valCat){

                        if((int)$valGr['pgid'] === (int)$valCat['pgid']){

                            if($valGr['pmgid'] != (int)$valCat['pmgid'] or $valGr['name'] != $valCat['name']){

                                $ch += 1;

                                $resСompareImkuh['change'] = $ch;

                                if($valGr['pmgid'] != (int)$valCat['pmgid']){

                                    $newCatPmgid = CategoriesImkuh::findOne(['pgid' => $valCat['pgid']]);

                                    $newCatPmgid->pmgid = $valGr['pmgid'];
                                    $newCatPmgid->save();
                                }

                                if($valGr['name'] != $valCat['name']){
                                    $newCatName = CategoriesImkuh::findOne(['pgid' => $valCat['pgid']]);

                                    $newCatName->name = $valGr['name'];
                                    $newCatName->save();
                                }
                            }
                            unset($prodGrsIm[$keyGr]);
                            unset($prodCatsIm[$keyCat]);
                        }

                        continue;
                    }

                }

                $resСompareImkuh['add'] = count($prodGrsIm);
                $resСompareImkuh['delete'] = count($prodCatsIm);

                if (!empty($prodGrsIm)){
                    foreach ($prodGrsIm as $prodGrIm){
                        $catImkushAdd = new CategoriesImkuh();
                        $catImkushAdd->pgid = $prodGrIm['pgid'];
                        $catImkushAdd->pmgid = $prodGrIm['pmgid'];
                        $catImkushAdd->name = $prodGrIm['name'];
                        $catImkushAdd->save();
                    }
                }

                if (!empty($prodCatsIm)){
                    foreach ($prodCatsIm as $prodCatIm) {
                        $catImkushDel = CategoriesImkuh::findOne(['pgid' => $prodCatIm['pgid']]);
                        $catImkushDel->delete();
                    }
                }


                return $this->render('index',[
                    'resСompareImkuh' => $resСompareImkuh
                ]);
            }

            if (\Yii::$app->request->post('holodbar')){

                $resСompareHolodbar = [];

                $prodGrsIm = ProductGroupsHolodbar::find()->select(['pgid','pmgid','name'])->asArray()->all();
                $prodCatsIm = CategoriesHolodbar::find()->select(['pgid','pmgid','name'])->asArray()->all();

                $countHolodbar = count($prodGrsIm);

                $resСompareHolodbar['count'] = $countHolodbar;

                $ch = 0;

                foreach ($prodGrsIm as $keyGr => $valGr) {

                    foreach ($prodCatsIm as $keyCat => $valCat){

                        if((int)$valGr['pgid'] === (int)$valCat['pgid']){

                            if($valGr['pmgid'] != (int)$valCat['pmgid'] or $valGr['name'] != $valCat['name']){

                                $ch += 1;

                                $resСompareHolodbar['change'] = $ch;

                                if($valGr['pmgid'] != (int)$valCat['pmgid']){

                                    $newCatPmgid = CategoriesHolodbar::findOne(['pgid' => $valCat['pgid']]);

                                    $newCatPmgid->pmgid = $valGr['pmgid'];
                                    $newCatPmgid->save();
                                }

                                if($valGr['name'] != $valCat['name']){
                                    $newCatName = CategoriesHolodbar::findOne(['pgid' => $valCat['pgid']]);

                                    $newCatName->name = $valGr['name'];
                                    $newCatName->save();
                                }
                            }
                            unset($prodGrsIm[$keyGr]);
                            unset($prodCatsIm[$keyCat]);
                        }

                        continue;
                    }

                }

                $resСompareHolodbar['add'] = count($prodGrsIm);
                $resСompareHolodbar['delete'] = count($prodCatsIm);

                if (!empty($prodGrsIm)){
                    foreach ($prodGrsIm as $prodGrIm){
                        $catImkushAdd = new CategoriesHolodbar();
                        $catImkushAdd->pgid = $prodGrIm['pgid'];
                        $catImkushAdd->pmgid = $prodGrIm['pmgid'];
                        $catImkushAdd->name = $prodGrIm['name'];
                        $catImkushAdd->save();
                    }
                }

                if (!empty($prodCatsIm)){
                    foreach ($prodCatsIm as $prodCatIm) {
                        $catImkushDel = CategoriesHolodbar::findOne(['pgid' => $prodCatIm['pgid']]);
                        $catImkushDel->delete();
                    }
                }

                return $this->render('index',[
                    'resСompareHolodbar' => $resСompareHolodbar
                ]);
            }

            if (\Yii::$app->request->post('mark_up_price')){

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

                $resMarkUp = 'Наценки на товары применены успешно';

                return $this->render('index',[
                    'resMarkUp' => $resMarkUp
                ]);

            }

            if (\Yii::$app->request->post('export')){

                $result = shell_exec( 'php ' . \Yii::$app->basePath . '/yii export-goods/init' );
                //echo $result;

            }
        }

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSites()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Sites::find()
        ]);

        return $this->render('sites', [
            'dataProvider' => $dataProvider
        ]);
    }
}
