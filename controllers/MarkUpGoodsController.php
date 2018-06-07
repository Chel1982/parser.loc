<?php

namespace app\controllers;

use app\models\Goods;
use app\models\Groups;
use app\models\Manufacturer;
use app\models\Sites;
use Yii;
use app\models\MarkUpGoods;
use app\models\search\MarkUpGoodsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MarkUpGoodsController implements the CRUD actions for MarkUpGoods model.
 */
class MarkUpGoodsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MarkUpGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MarkUpGoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MarkUpGoods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MarkUpGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MarkUpGoods();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MarkUpGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MarkUpGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $markUp = MarkUpGoods::findOne($id);

        if (isset($markUp->categories_imkuh_id)){

            /* Выбираем товары из групп */
            $idGroups = Groups::find()->where(['categories_imkuh_id' => $markUp->categories_imkuh_id])->select('id');

            /* Ищем товары в диапозоне цен */
            $goodsId = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markUp->from_value])->andWhere(['<=', 'price', $markUp->to_value])->select('id');

            $goods = Goods::findAll($goodsId);

            foreach ($goods as $good) {

                $good->mark_up_price = NULL;
                $good->save();

            }

        }

        if (isset($markUp->categories_holodbar_id)){
            /* Выбираем товары из групп */
            $idGroups = Groups::find()->where(['categories_imkuh_id' => $markUp->categories_holodbar_id])->select('id');

            /* Ищем товары в диапозоне цен */
            $goodsId = Goods::find()->where(['groups_id' => $idGroups])->andWhere(['>=', 'price', $markUp->from_value])->andWhere(['<=', 'price', $markUp->to_value])->select('id');

            $goods = Goods::findAll($goodsId);

            foreach ($goods as $good) {

                $good->mark_up_price = NULL;
                $good->save();

            }
        }

        if (isset($markUp->manufacturer_id_imkuh)){

            $urlManuf = Manufacturer::find()->where(['id' => $markUp->manufacturer_id_imkuh])->select('sites_url');

            $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

            /* Ищем товары в диапозоне цен */
            $goodsId = Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price', $markUp->from_value])->andWhere(['<=', 'price', $markUp->to_value])->select('id');

            $goods = Goods::findAll($goodsId);

            foreach ($goods as $good) {

                $good->mark_up_price = NULL;
                $good->save();

            }

        }

        if(isset($markUp->manufacturer_id_holodbar)){

            $urlManuf = Manufacturer::find()->where(['id' => $markUp->manufacturer_id_holodbar])->select('sites_url');

            $idSites = Sites::find()->where(['url' => $urlManuf])->select('id');

            /* Ищем товары в диапозоне цен */
            $goodsId = Goods::find()->where(['sites_id' => $idSites])->andWhere(['>=', 'price', $markUp->from_value])->andWhere(['<=', 'price', $markUp->to_value])->select('id');

            $goods = Goods::findAll($goodsId);

            foreach ($goods as $good) {

                $good->mark_up_price = NULL;
                $good->save();

            }

        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MarkUpGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarkUpGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MarkUpGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
