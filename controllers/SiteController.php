<?php

namespace app\controllers;

use app\models\CategoriesHolodbar;
use app\models\CategoriesImkuh;
use app\models\ProductGroupsHolodbar;
use app\models\ProductGroupsImkuh;
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
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
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

                $countImkus = count($prodGrsIm);

                $resСompareImkuh['count'] = $countImkus;

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

                $countImkus = count($prodGrsIm);

                $resСompareHolodbar['count'] = $countImkus;

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
