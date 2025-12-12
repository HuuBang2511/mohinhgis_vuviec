<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\VuViec;
use app\modules\quanly\models\VuViecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\models\LichSuXuLy;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\services\CategoriesService;
use Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

use app\modules\quanly\models\VNguoidan;
use app\modules\quanly\models\TaiLieuDinhKem;

use yii\web\UploadedFile;
use app\modules\quanly\base\UploadFile;
use app\modules\quanly\models\NguoiDan;

use DateTime;

/**
 * VuViecController implements the CRUD actions for VuViec model.
 */
class VuViecController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Vụ việc";

    /**
     * Lists all VuViec models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $request = Yii::$app->request;
        $queryParams = $request->queryParams;
        $searchModel = new VuViecSearch();
        $dataProvider = $searchModel->search($queryParams);

        if ($request->isPost && $searchModel->load($request->post())) {
             $url = ['index'];
            foreach ($request->post()['VuViecSearch'] as $i => $item) {
                $url = array_merge($url,["VuViecSearch[$i]" => $item]);
            }
            return $this->redirect($url);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => CategoriesService::getCategoriesVuViec(),
        ]);
    }


    /**
     * Displays a single VuViec model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $lichsus = LichSuXuLy::find()
        ->where(['vu_viec_id' => $id])  
        ->andWhere(['status' => 1])  
        ->all();

        $filedinhkems = TaiLieuDinhKem::find()->where(['status' => 1, 'vu_viec_id' => $id, 'is_nguoidangui' => 0])->all();
        $filedinhkemNguoidans = TaiLieuDinhKem::find()->where(['status' => 1, 'vu_viec_id' => $id, 'is_nguoidangui' => 1])->all();

        return $this->render('view',[
            'model' => $model,
            'lichsus' => $lichsus,
            'filedinhkems' => $filedinhkems,
            'filedinhkemNguoidans' => $filedinhkemNguoidans,
        ]);
    }

    /**
     * Creates a new VuViec model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionThongbaoThanhcong(){
        return $this->render('thongbao-thanhcong');
    }
     
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new VuViec();

        $model->trang_thai_hien_tai_id = 1;

        if(Yii::$app->user->identity->phuongxa != null && !Yii::$app->user->identity->is_admin){
            $model->ma_dvhc_phuongxa = Yii::$app->user->identity->phuongxa;
        }

        if(Yii::$app->user->identity->donvi_id != null && !Yii::$app->user->identity->is_admin && !Yii::$app->user->identity->is_nguoidan){
            $model->don_vi_tiep_nhan_id = Yii::$app->user->identity->donvi_id;
        }

        if(Yii::$app->user->identity->canbo_id != null && !Yii::$app->user->identity->is_admin && !Yii::$app->user->identity->is_nguoidan){
            $model->can_bo_tiep_nhan_id = Yii::$app->user->identity->canbo_id;
        }

        //dd($model);

        $congdan = new NguoiDan();

        $today = new DateTime();
        $today->modify('+10 days');
        
        $model->han_xu_ly = $today->format('d/m/Y');

        $filedinhkem = new UploadFile();

        $filedinhkemNguoidan = new UploadFile();

        $lichsus = [new LichSuXuLy()];

        if ($request->isPost  && $model->load($request->post()) && $filedinhkemNguoidan->load($request->post())  && Yii::$app->user->identity->is_nguoidan){
            if($model->nguoi_dan_id == null && $congdan->ho_ten){
                $congdan->save();
                $model->nguoi_dan_id = $congdan->id;
            }

            if($model->ma_dvhc_phuongxa != null){
                $vuviecDaco = VuViec::find()->where(['status' => 1])->andWhere(['ma_dvhc_phuongxa' => $model->ma_dvhc_phuongxa])->count();
                $model->ma_vu_viec = $model->ma_dvhc_phuongxa.'_'.$vuviecDaco;
            }

            $model->nguoi_dan_id = Yii::$app->user->identity->nguoidan_id;
            $model->is_nguoidanthem = true;

            //dd($model);

            $model->save();

            $filedinhkemNguoidan->fileupload = UploadedFile::getInstances($filedinhkemNguoidan, 'fileupload');

            if($filedinhkemNguoidan->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkemNguoidan->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    //dd($item);

                    $tailieu = new TaiLieuDinhKem();
                    

                    $file[] = 'uploads/'.date('Y').'/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/'.date('Y').'/'.$model->id.'/nguoidan/';

                    $tailieu->duong_dan_file = 'uploads/'.date('Y').'/'.$model->id.'/nguoidan/'.$item->baseName.'.'.$item->extension;
                    $tailieu->ten_file_goc = $item->baseName.'.'.$item->extension;
                    $tailieu->loai_file = $item->extension;
                    $tailieu->vu_viec_id = $model->id;
                    $tailieu->is_nguoidangui = 1;
                    
                    $tailieu->save();

                    $filedinhkemNguoidan->uploadFile($path, $item);
                }

                $model->url_dinhkem_nguoidan = json_encode($file);
                $model->save();
            }

            return $this->redirect('thongbao-thanhcong');
        }

        if ($request->isPost  && $model->load($request->post()) && $filedinhkem->load($request->post()) && $congdan->load($request->post()) &&!Yii::$app->user->identity->is_nguoidan) {
            $lichsus = QuanlyBaseModel::createMultiple(LichSuXuLy::classname());
            QuanlyBaseModel::loadMultiple($lichsus, $request->post());

            if($model->vu_viec_goc_id != null){
                $model->is_lap_lai = true;
            }else{
                $model->is_lap_lai = false;
            }

            // if(!$model->validate()){
            //     dd($model->getErrors());
            // }

            //dd($model->validate());

            if($model->nguoi_dan_id == null && $congdan->ho_ten){
                $congdan->save();
                $model->nguoi_dan_id = $congdan->id;
            }

            if($model->ma_dvhc_phuongxa != null){
                $vuviecDaco = VuViec::find()->where(['status' => 1])->andWhere(['ma_dvhc_phuongxa' => $model->ma_dvhc_phuongxa])->count();
                $model->ma_vu_viec = $model->ma_dvhc_phuongxa.'_'.$vuviecDaco;
            }

            $valid = $model->validate();
            $valid = QuanlyBaseModel::validateMultiple($lichsus) && $valid;
            //dd($valid);

            //dd($filedinhkem);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($lichsus as $lichsu) {
                            $lichsu->vu_viec_id = $model->id;
                            //dd($model->id);
                            if (!($flag = $lichsu->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    //dd($flag);
                    if ($flag) {
                        $transaction->commit();
                        // if ($id != null) {
                        //     return $this->redirect(['vu-viec/view', 'id' => $id]);
                        // }
                        // return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    //dd($e);
                }
            }

            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    //dd($item);

                    $tailieu = new TaiLieuDinhKem();
                    

                    $file[] = 'uploads/'.date('Y').'/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/'.date('Y').'/'.$model->id.'/';

                    $tailieu->duong_dan_file = 'uploads/'.date('Y').'/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $tailieu->ten_file_goc = $item->baseName.'.'.$item->extension;
                    $tailieu->loai_file = $item->extension;
                    $tailieu->vu_viec_id = $model->id;
                    $tailieu->is_nguoidangui = 0;
                    
                    $tailieu->save();

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->url_dinhkem = json_encode($file);
                $model->save();
                
            }

            
            $model->save();

            //dd($lichsus);
            if(Yii::$app->user->identity->is_nguoidan){
                return $this->redirect('thongbaoThanhcong');
            }
            return $this->redirect(['vu-viec/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => CategoriesService::getCategoriesVuViec(),
            'lichsus' => (empty($lichsus)) ? [new LichSuXuLy()] : $lichsus,
            'filedinhkem' => $filedinhkem,
            'filedinhkemNguoidan' => $filedinhkemNguoidan,
            'congdan' => $congdan,
        ]);

    }

    /**
     * Updates an existing VuViec model.
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

        $filedinhkemNguoidan = new UploadFile();

        $nguoidan = (new Query())->select('id, text')
        ->from('v_nguoidan')
        ->where(['id' => $model->nguoi_dan_id])
        ->one();

        $nguoidan = null;
        if ($model->nguoi_dan_id != null) {
            $nguoidan = (new Query())->select('id, text')
            ->from('v_nguoidan')
            ->where(['id' => $model->nguoi_dan_id])
            ->one();
        }

        if($model->nguoi_dan_id != null){
            $congdan = NguoiDan::findOne($model->nguoi_dan_id);
            $congdanid_cu = $model->nguoi_dan_id;
        }else{
            $congdan = new NguoiDan();
        }

        //dd($nguoidan);
        
        $lichsus = LichSuXuLy::find()
        ->where(['vu_viec_id' => $id])  
        ->andWhere(['status' => 1])  
        ->all();

        //dd($model);

        if ($request->isPost  && $model->load($request->post()) && $filedinhkemNguoidan->load($request->post()) && Yii::$app->user->identity->is_nguoidan){

            //dd($model);

            if($model->ma_dvhc_phuongxa != null){
                $vuviecDaco = VuViec::find()->where(['status' => 1])->andWhere(['ma_dvhc_phuongxa' => $model->ma_dvhc_phuongxa])->count();
                $model->ma_vu_viec = $model->ma_dvhc_phuongxa.'_'.$vuviecDaco;
            }

            $filedinhkemNguoidan->fileupload = UploadedFile::getInstances($filedinhkemNguoidan, 'fileupload');

            if($filedinhkemNguoidan->fileupload != null){

                $fileCanxoa = TaiLieuDinhKem::find()->where(['status' => 1, 'vu_viec_id' => $id, 'is_nguoidangui' => 1])->all();

                if($fileCanxoa != null){
                    foreach($fileCanxoa as $i => $item){
                        $item->status = 0;
                        $item->save();
                    }
                }

                if($model->url_dinhkem_nguoidan != null){
                    $fileCu = $model->url_dinhkem_nguoidan;
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
                foreach($filedinhkemNguoidan->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $tailieu = new TaiLieuDinhKem();

                    $file[] = 'uploads/'.date('Y').'/'.$model->id.'/nguoidan/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/'.date('Y').'/'.$model->id.'/nguoidan/';

                    $tailieu->duong_dan_file = 'uploads/'.date('Y').'/'.$model->id.'/nguoidan/'.$item->baseName.'.'.$item->extension;
                    $tailieu->ten_file_goc = $item->baseName.'.'.$item->extension;
                    $tailieu->loai_file = $item->extension;
                    $tailieu->vu_viec_id = $id;
                    $tailieu->is_nguoidangui = 1;

                    //dd($tailieu);

                    if(!$tailieu->save()){
                        dd($tailieu->getErrors());
                    }
                    
                    $tailieu->save();

                    $filedinhkemNguoidan->uploadFile($path, $item);
                }

                $model->url_dinhkem_nguoidan = json_encode($file);
                $model->save();
            }else{
                $model->save();
            }

            return $this->redirect('thongbao-thanhcong');
        }

        if ($request->isPost  && $model->load($request->post()) && $filedinhkem->load($request->post()) && $congdan->load($request->post()) &&!Yii::$app->user->identity->is_nguoidan) {

            if($model->ma_dvhc_phuongxa != null){
                $vuviecDaco = VuViec::find()->where(['status' => 1])->andWhere(['ma_dvhc_phuongxa' => $model->ma_dvhc_phuongxa])->count();
                $model->ma_vu_viec = $model->ma_dvhc_phuongxa.'_'.$vuviecDaco;
            }

            $oldIDs = ArrayHelper::map($lichsus, 'id', 'id');
            $lichsus = QuanlyBaseModel::updateMultiple(LichSuXuLy::classname(), $lichsus);
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($lichsus, 'id', 'id')));
            QuanlyBaseModel::loadMultiple($lichsus, Yii::$app->request->post());

            // if(!$model->validate()){
            //     dd($model->getErrors());
            // }

          

            if($model->vu_viec_goc_id != null){
                $model->is_lap_lai = true;
            }else{
                $model->is_lap_lai = false;
            }

            // if(!$congdan->isNewRecord){
            //     $congdan->save();
            // }

            if($model->nguoi_dan_id == null && $congdan->ho_ten && $congdan->isNewRecord){
                $congdan->save();
                $model->nguoi_dan_id = $congdan->id;
            }

            if($model->nguoi_dan_id != null && $model->nguoi_dan_id == $congdanid_cu && !$congdan->isNewRecord){
                $congdan->save();
            }

            //dd($congdan);

            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            //dd($filedinhkem);

            if($filedinhkem->fileupload != null){

                $fileCanxoa = TaiLieuDinhKem::find()->where(['status' => 1, 'vu_viec_id' => $id, 'is_nguoidangui' => 0])->all();

                if($fileCanxoa != null){
                    foreach($fileCanxoa as $i => $item){
                        $item->status = 0;
                        $item->save();
                    }
                }

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

                    $tailieu = new TaiLieuDinhKem();

                    $file[] = 'uploads/'.date('Y').'/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/'.date('Y').'/'.$model->id.'/';

                    $tailieu->duong_dan_file = 'uploads/'.date('Y').'/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $tailieu->ten_file_goc = $item->baseName.'.'.$item->extension;
                    $tailieu->loai_file = $item->extension;
                    $tailieu->vu_viec_id = $id;
                    $tailieu->is_nguoidangui = 0;

                    //dd($tailieu);

                    if(!$tailieu->save()){
                        dd($tailieu->getErrors());
                    }
                    
                    $tailieu->save();

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->url_dinhkem = json_encode($file);
                $model->save();
            }


            $valid = $model->validate();

            //dd($valid);

            if($valid){
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if ($flag = $model->save(false)) {

                        //xử lý xóa
                        if (sizeof($deletedIDs) > 0) {
                            foreach ($deletedIDs as $i => $item) {
                                Yii::$app->db->createCommand("UPDATE lich_su_xu_ly SET status = 0 WHERE id = :id")
                                    ->bindValue(':id', $item)
                                    ->execute();
                            }
                            //CongDan::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($lichsus as $lichsu) {
                            $lichsu->vu_viec_id = $model->id;
                            if (!($flag = $lichsu->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        if ($id != null) {
                            return $this->redirect(['view', 'id' => $id]);
                        }
                        return $this->redirect(['index']);
                    }
                }
                catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('update', [
            'model' => $model,
            'nguoidan' => $nguoidan,
            'categories' => CategoriesService::getCategoriesVuViec(),
            'lichsus' => (empty($lichsus)) ? [new LichSuXuLy()] : $lichsus,
            'filedinhkem' => $filedinhkem,
            'congdan' => $congdan,
            'filedinhkemNguoidan' => $filedinhkemNguoidan,
        ]);
    }

    /**
     * Delete an existing VuViec model.
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
     * Finds the VuViec model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VuViec the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VuViec::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
