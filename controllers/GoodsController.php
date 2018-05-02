<?php

namespace app\controllers;

use app\models\Images;
use Yii;
use app\models\Goods;
use app\models\search\GoodsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (Yii::$app->request->post('Description') !== null){
                $model->descriptions->load(Yii::$app->request->post());
                $model->descriptions->save();
            }

            if (Yii::$app->request->post('Price') !== null){
                $model->prices->load(Yii::$app->request->post());
                $model->prices->save();
            }

            if (Yii::$app->request->post('Manufacturer') !== null){
                $model->manufacturers->load(Yii::$app->request->post());
                $model->manufacturers->save();
            }

            if (Yii::$app->request->post('ProductAttributes') !== null){
                $model->productAttributes->load(Yii::$app->request->post());
                $model->productAttributes->save();
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Goods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = Yii::getAlias("@app/web/uploads/images/" . $model->id);

        if (file_exists($path)) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                (is_dir("$path/$file")) ? rmdir("$path/$file") : unlink("$path/$file");
            }
            rmdir($path);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteImage($id, $idImage, $name)
    {
        $path = Yii::getAlias("@app/web/uploads/images/" . $id . "/" . $name);

        if (file_exists($path)) {
            unlink($path);
        }

        if (isset($idImage)){
            $image = Images::findOne($idImage);
            $image->delete();
        }

        return  $this->redirect(['update', 'id' => $id]);

    }

}
