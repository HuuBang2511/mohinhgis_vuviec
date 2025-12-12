<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\TruNuocCcc;
use app\modules\quanly\models\TruNuocCccSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\modules\quanly\base\UploadFile;

/**
 * TruNuocCccController implements the CRUD actions for TruNuocCcc model.
 */
class TruNuocCccController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Trụ nước PCCC";

    /**
     * Lists all TruNuocCcc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TruNuocCccSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single TruNuocCcc model.
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
     * Creates a new TruNuocCcc model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new TruNuocCcc();

        $filedinhkem = new UploadFile();

        if($model->load($request->post()) && $model->save() && $filedinhkem->load($request->post())){
            
            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/trunuocpccc/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/trunuocpccc/'.$model->id.'/';
                    
                    $tailieu->save();

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->file_dinhkem = json_encode($file);
                $model->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'filedinhkem' => $filedinhkem,
        ]);

    }

    /**
     * Updates an existing TruNuocCcc model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $filedinhkem = new UploadFile();

        if($model->load($request->post()) && $model->save() && $filedinhkem->load($request->post())){
            
            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/trunuocpccc/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/trunuocpccc/'.$model->id.'/';
                    
                    $tailieu->save();

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->file_dinhkem = json_encode($file);
                $model->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'filedinhkem' => $filedinhkem,
        ]);
    }

    /**
     * Delete an existing TruNuocCcc model.
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

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Xóa #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Xóa', ['class' => 'btn btn-danger float-left', 'type' => "submit"])
                ];
            } else if ($request->isPost && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Xóa thành công #" . $id,
                    'content' => '<span class="text-success">Xóa thành công</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Update #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    
    /**
     * Finds the TruNuocCcc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TruNuocCcc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TruNuocCcc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
