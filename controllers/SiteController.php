<?php

namespace app\controllers;

use app\models\CategoriesHolodbar;
use app\models\CategoriesImkuh;
use app\models\Config;
use app\models\Goods;
use app\models\Groups;
use app\models\Manufacturer;
use app\models\MarkUpGoods;
use app\models\ProductGroupsHolodbar;
use app\models\ProductGroupsImkuh;
use app\models\Sites;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
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

                $countGoods = 0;
                $countGoodsEUR = 0;
                $countGoodsUSD = 0;

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

                if (empty($markUpPercent) && empty($markUpAbsolute)){
                    $resMarkUp = 'Задайте хотя бы 1 наценку на товар';

                    return $this->render('index',[
                        'resMarkUp' => $resMarkUp
                    ]);
                }

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

                        $countGoods += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsEUR += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsUSD += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

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

                        $countGoods += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsEUR += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsUSD += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

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

                        $countGoods += Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsEUR += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsUSD += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

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

                        $countGoods += Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsEUR += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

                        $countGoodsUSD += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markPerFrom])->andWhere(['<=', 'price_rub', $markPerTo])->count();

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

                        $countGoods += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsEUR += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsUSD += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

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

                    /* Наценка на группы товаров Holodbar */
                    if (isset($markAbs['categories_holodbar_id'])) {

                        /* Выбираем товары из групп */
                        $idGroups = Groups::find()->where(['categories_holodbar_id' => $markAbs['categories_holodbar_id']])->select('id');

                        $countGoods += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsEUR += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsUSD += Goods::find()->where(['groups_id' => $idGroups])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        /* Ищем товары в диапозоне цен поля price_rub */
                        $goodsIdPriceRub = Goods::find()
                            ->where(['groups_id' => $idGroups])
                            ->andWhere(['>=', 'price_rub', $markAbsFrom])
                            ->andWhere(['<=', 'price_rub', $markAbsTo])
                            ->select('id')->asArray()->all();

                        /* Делаем наценку на товар */
                        Goods::updateAll(
                            ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value'] . ')')], ['id' => $goodsIdPriceRub]
                        );
                    }

                    /* Наценка на производителя для Imkuh */
                    if (isset($markAbs['manufacturer_id_imkuh'])) {

                        $idCatImkuh = MarkUpGoods::find()->where(['is not', 'manufacturer_id_imkuh', NULL])->select('manufacturer_id_imkuh');

                        $urlManuf = Manufacturer::find()->where(['id' => $idCatImkuh])->select('sites_url');

                        $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                        $countGoods += Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsEUR += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsUSD += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        /* Ищем товары в диапозоне цен поля price_rub */
                        $goodsIdPriceRub = Goods::find()
                            ->where(['sites_id' => $idSites])
                            ->andWhere(['>=', 'price_rub', $markAbsFrom])
                            ->andWhere(['<=', 'price_rub', $markAbsTo])
                            ->select('id')->asArray()->all();

                        /* Делаем наценку на товар */
                        Goods::updateAll(
                            ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value'] . ')')], ['id' => $goodsIdPriceRub]
                        );

                    }

                    /* Наценка на производителя для Holodbar */
                    if (isset($markAbs['manufacturer_id_holodbar'])) {

                        $idCatHolod = MarkUpGoods::find()->where(['is not', 'manufacturer_id_holodbar', NULL])->select('manufacturer_id_holodbar');

                        $urlManuf = Manufacturer::find()->where(['id' => $idCatHolod])->select('sites_url');

                        $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

                        $countGoods += Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsEUR += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 2])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        $countGoodsUSD += Goods::find()->where(['sites_id' => $idSites])->andWhere(['currency_id' => 3])->andWhere(['>=', 'price_rub', $markAbsFrom])->andWhere(['<=', 'price_rub', $markAbsTo])->count();

                        /* Ищем товары в диапозоне цен поля price_rub */
                        $goodsIdPriceRub = Goods::find()
                            ->where(['sites_id' => $idSites])
                            ->andWhere(['>=', 'price_rub', $markAbsFrom])
                            ->andWhere(['<=', 'price_rub', $markAbsTo])
                            ->select('id')->asArray()->all();

                        /* Делаем наценку на товар */
                        Goods::updateAll(
                            ['mark_up_price' => new Expression('ROUND(price_rub + ' . $markAbs['price_value'] . ')')], ['id' => $goodsIdPriceRub]
                        );
                    }

                }

                return $this->render('index',[
                    'countGoodsUSD' => $countGoodsUSD,
                    'countGoodsEUR' => $countGoodsEUR,
                    'countGoods' => $countGoods,
                ]);

            }

            if (\Yii::$app->request->post('export')){

                shell_exec( 'php ' . \Yii::$app->basePath . '/yii export-goods/init' );

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
