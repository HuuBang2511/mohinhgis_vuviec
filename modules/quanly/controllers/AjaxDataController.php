<?php

namespace app\modules\quanly\controllers;

use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;
use Yii;
use app\modules\quanly\models\VNguoidan;
use yii\helpers\ArrayHelper;
use app\modules\quanly\models\CanBo;
use app\modules\quanly\models\VuViec;
use app\modules\quanly\models\NguoiDan;
use app\modules\quanly\models\Kp;

class AjaxDataController extends Controller{
    public function actionGetNguoidan($q = null)
    {
        $query = new Query;

        $query->select('id, text')
            ->from('v_nguoidan')
            ->where (['ilike','text' , $q ])
            ->orderBy('text')
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return json_encode($out);
    }

    public function actionGetCanbo(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $donvi_id = $parents[0];
                // $out = CanBo::find()->select('id, ho_ten as name')
                //     ->where(['don_vi_id' => $donvi_id])
                //     ->orderBy('ho_ten')->asArray()->all();

                if(Yii::$app->user->identity->canbo_id != null){
                   $out = CanBo::find()->select('id, ho_ten as name')
                    ->where(['don_vi_id' => $donvi_id])
                    ->andWhere(['id' => Yii::$app->user->identity->canbo_id])
                    ->orderBy('ho_ten')->asArray()->all();
                }else{
                    $out = CanBo::find()->select('id, ho_ten as name')
                    ->where(['don_vi_id' => $donvi_id])
                    ->orderBy('ho_ten')->asArray()->all();
                }

                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetKhupho(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
       
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $phuongxa_id = $parents[0];
                
               
                $query = new Query;

                $query->select([
                    'OBJECTID as id',
                    "('Phường cũ: ' || \"TenPhuong\" || ', Khu phố: ' || \"TenKhuPho\") as name"
                ])
                ->from('kp')
                ->andWhere(['mv_dvhc' => $phuongxa_id])
                ->orderBy('TenKhuPho');
                $command = $query->createCommand();
                $data = $command->queryAll();

                return ['output'=>array_values($data), 'selected'=>''];
            }

            
        }
        return ['output' => '', 'selected' => ''];
    }


    public function actionGetDataVuviec($id){
        $vuviec  = VuViec::find()->where(['id' => $id])->asArray()->one();

        $model = VuViec::find()->where(['id' => $id])->one();

        $dateAttributes = ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type');

        if(isset($dateAttributes['date'])) {
            foreach($dateAttributes['date'] as $i => $item){
                if($vuviec[$i] != null){
                    $vuviec[$i] = date("d/m/Y", strtotime($vuviec[$i]));
                }
            }
        }
    
        return json_encode($vuviec);
    }


    public function actionGetDataNguoidan($id){
        $nguoidan  = NguoiDan::find()->where(['id' => $id])->asArray()->one();

        $model = NguoiDan::find()->where(['id' => $id])->one();

        $dateAttributes = ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type');

        if(isset($dateAttributes['date'])) {
            foreach($dateAttributes['date'] as $i => $item){
                if($nguoidan[$i] != null){
                    $nguoidan[$i] = date("d/m/Y", strtotime($nguoidan[$i]));
                }
            }
        }
    
        return json_encode($nguoidan);
    }
}