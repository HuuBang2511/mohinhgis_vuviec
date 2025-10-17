<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\NguoiDan;
use app\modules\quanly\models\NguoiDanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\services\CategoriesService;
use app\modules\quanly\models\ThongtinCutru;
use yii\web\UploadedFile;
use app\modules\quanly\base\UploadFile;

/**
 * NguoiDanController implements the CRUD actions for NguoiDan model.
 */
class NguoiDanController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Người dân";
    public $const;
    public function init()
    {
        parent::init();
        $this->const = [
            'title' => 'Người dân',
            'label' => [
                'index' => 'Danh sách',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
                
            ],
            'url' => [
                'index' => 'index',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
            
            ],
        ];
    }

    /**
     * Lists all NguoiDan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NguoiDanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single NguoiDan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

       
        $thongtinLienquan['cutru'] = ThongtinCutru::find()->where(['status' => 1, 'nguoidan_id' => $id])->all();
        
        
        //dd($thongtinLienquan['tochuc'][0]->loaitochuc);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'thongtinLienquan' => $thongtinLienquan,
        ]);
    }

    /**
     * Creates a new NguoiDan model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;

        if($id != null){
            $model = new NguoiDan(['hogiadinh_id' => $id]);
        }else{
            $model = new NguoiDan();
        }

        $filedinhkem = new UploadFile();
       
        if($model->load($request->post()) && $filedinhkem->load($request->post())){

            $model->save();

            $model->dia_chi = $model->hogiadinh->nocgia->so_nha.', '.$model->hogiadinh->nocgia->ten_duong.', '.$model->hogiadinh->nocgia->khupho->TenKhuPho.', '.$model->hogiadinh->nocgia->phuongxa->tenXa;

            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/nguoidan/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/nguoidan/'.$model->id.'/';

                    $filedinhkem->uploadFile($path, $item);
                }
                $model->url_dinhkem = json_encode($file);
                $model->save();
            }

            $model->save();
            
            return $this->redirect(['ho-gia-dinh/view', 'id' => $model->hogiadinh_id]);
        }
        
        return $this->render('create', [
            'model' => $model,
            'categories' => CategoriesService::getCategoriesCongdan(),
            'filedinhkem' => $filedinhkem
        ]);
    }

    /**
     * Updates an existing NguoiDan model.
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

        if($model->load($request->post()) && $filedinhkem->load($request->post())){
            $model->dia_chi = $model->hogiadinh->nocgia->so_nha.', '.$model->hogiadinh->nocgia->ten_duong.', '.$model->hogiadinh->nocgia->khupho->TenKhuPho.', '.$model->hogiadinh->nocgia->phuongxa->tenXa;
            $model->save();

            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){

                if($model->url_dinhkem != null){
                    $fileCu = $model->url_dinhkem;
                    $fileCu = json_decode($fileCu, true);
                    if(count($fileCu) > 0){
                        foreach($fileCu as $i => $item){
                            if(is_file($item)){
                                unlink($item); 
                            } 
                        }
                    }
                }

                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/nguoidan/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/nguoidan/'.$model->id.'/';

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->url_dinhkem = json_encode($file);
                $model->save();
            }
            
            return $this->redirect(['ho-gia-dinh/view', 'id' => $model->hogiadinh_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => CategoriesService::getCategoriesCongdan(),
            'filedinhkem' => $filedinhkem
        ]);
    }

    /**
     * Delete an existing NguoiDan model.
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
     * Finds the NguoiDan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NguoiDan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NguoiDan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
