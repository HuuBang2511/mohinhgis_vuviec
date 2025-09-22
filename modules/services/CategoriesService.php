<?php
namespace app\modules\services;

use app\modules\danhmuc\models\DmKtvhxh;
use app\modules\danhmuc\models\DmTongiao;
use app\modules\quanly\models\RanhphuongThuduc;
use app\modules\quanly\models\LinhVuc;
use app\modules\quanly\models\CanBo;
use app\modules\quanly\models\DonVi;
use app\modules\quanly\models\TrangThaiXuLy;
use app\modules\quanly\models\Phuongxa;
use app\modules\quanly\models\VuViec;
use app\modules\quanly\models\danhmuc\DmLoaicutru;
use app\modules\quanly\models\danhmuc\DmQuanhechuho;
use app\modules\quanly\models\danhmuc\DmGioitinh;
use Yii;
class CategoriesService
{

    public static function getCategories()
    {
        $categories = [];
        $categories['phuong'] = RanhphuongThuduc::find()->where(['status'=>1])->orderBy('name_3')->asArray()->all();
        $categories['dm_ktvhxh'] = DmKtvhxh::find()->where(['status'=>1])->orderBy('dm_tv')->asArray()->all();
        $categories['dm_tongiao'] = DmTongiao::find()->where(['status'=>1])->orderBy('dm_tv')->asArray()->all();
        return $categories;
    }

    public static function getCategoriesUser(){
        $categories = [];
        $categories['phuongxa'] = Phuongxa::find()->orderBy('ten_dvhc')->asArray()->all();
        return $categories;
    }

    public static function getCategoriesNocgia(){
        $categories = [];

        if(Yii::$app->user->identity->phuongxa !== null){
            $categories['phuongxa'] = Phuongxa::find()->where(['ma_dvhc' => Yii::$app->user->identity->phuongxa])->orderBy('ten_dvhc')->asArray()->all();
        }else{
            $categories['phuongxa'] = Phuongxa::find()->orderBy('ten_dvhc')->asArray()->all();
        }

        return $categories;
    }

    public static function getCategoriesCongdan(){
        $categories = [];
        $categories['gioitinh'] = DmGioitinh::find()->where(['status' => 1])->orderBy('id')->all();
        $categories['quanhechuho'] = DmQuanhechuho::find()->where(['status' => 1])->orderBy('id')->all();
        $categories['loaicutru'] = DmLoaicutru::find()->where(['status' => 1])->orderBy('id')->all();
        return $categories;
    }

    public static function getCategoriesVuViec(){
        $categories = [];
        $categories['linhvuc'] = LinhVuc::find()->where(['status' => 1])->orderBy('ten_linh_vuc')->asArray()->all();
        $categories['canbo'] = CanBo::find()->where(['status' => 1])->orderBy('ho_ten')->asArray()->all();
        $categories['donvi'] = DonVi::find()->where(['status' => 1])->orderBy('ten_don_vi')->asArray()->all();

        if(Yii::$app->user->identity->donvi_id != null){
            $categories['canbo'] = CanBo::find()->where(['status' => 1, 'don_vi_id' => Yii::$app->user->identity->donvi_id])->orderBy('ho_ten')->asArray()->all();
            $categories['donvi'] = DonVi::find()->where(['status' => 1, 'id' => Yii::$app->user->identity->donvi_id])->orderBy('ten_don_vi')->asArray()->all();
        }

        if(Yii::$app->user->identity->canbo_id != null){
            $categories['canbo'] = CanBo::find()->where(['status' => 1, 'id' => Yii::$app->user->identity->canbo_id])->orderBy('ho_ten')->asArray()->all();
        }

        if(Yii::$app->user->identity->phuongxa !== null){
            $categories['phuongxa'] = Phuongxa::find()->where(['ma_dvhc' => Yii::$app->user->identity->phuongxa])->orderBy('ten_dvhc')->asArray()->all();
        }else{
            $categories['phuongxa'] = Phuongxa::find()->orderBy('ten_dvhc')->asArray()->all();
        }

        $categories['trangthaixuly'] = TrangThaiXuLy::find()->where(['status' => 1])->orderBy('ten_trang_thai')->asArray()->all();
        $categories['vuviec'] = VuViec::find()->where(['status' => 1])->orderBy('ma_vu_viec')->asArray()->all();
        $categories['captaikhoan'] = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
        ];

        //dd($categories);
        return $categories;
    }

}