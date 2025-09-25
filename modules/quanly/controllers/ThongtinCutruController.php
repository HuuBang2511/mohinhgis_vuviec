<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\ThongtinCutru;
use app\modules\quanly\models\ThongtinCutruSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\models\danhmuc\DmLoaicutru;

/**
 * ThongtinCutruController implements the CRUD actions for ThongtinCutru model.
 */
class ThongtinCutruController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Thông tin cư trú";

    /**
     * Lists all ThongtinCutru models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ThongtinCutruSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ThongtinCutru model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ThongtinCutru model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;
        $model = new ThongtinCutru(['nguoidan_id' => $id]);

        $categories['loaicutru'] =  DmLoaicutru::find()->where(['status' => 1])->orderBy('ten')->all();


        if($model->load($request->post())){

            $model->save();
            
            return $this->redirect(['nguoi-dan/view', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);

    }

    /**
     * Updates an existing ThongtinCutru model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $categories['loaicutru'] =  DmLoaicutru::find()->where(['status' => 1])->orderBy('ten')->all();


        if($model->load($request->post())){

            $model->save();
            
            return $this->redirect(['nguoi-dan/view', 'id' => $model->congdan_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Delete an existing ThongtinCutru model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = 0;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Xóa ThongtinCutru #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Xóa',['class'=>'btn btn-danger float-left','type'=>"submit"])
                ];
            }else if($request->isPost && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "ThongtinCutru #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Update ThongtinCutru #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('delete', [
                    'model' => $model,
                    'const' => $this->const,
                ]);
            }
        }
    }

    
    /**
     * Finds the ThongtinCutru model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ThongtinCutru the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ThongtinCutru::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
