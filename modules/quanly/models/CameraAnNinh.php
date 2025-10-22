<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "camera_an_ninh".
 *
 * @property int $id
 * @property string|null $ma_camera
 * @property string|null $ten_diem
 * @property string|null $dia_chi
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $don_vi_quan_ly
 * @property string|null $trang_thai
 * @property string|null $nguon_du_lieu
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class CameraAnNinh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_an_ninh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ma_camera', 'ten_diem', 'dia_chi', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'trang_thai', 'nguon_du_lieu', 'geom', 'created_by', 'updated_by'], 'string'],
            [['lat', 'long'], 'number'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_camera' => 'Mã camera',
            'ten_diem' => 'Tên điểm',
            'dia_chi' => 'Địa chỉ',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'don_vi_quan_ly' => 'Đơn vị quản lý',
            'trang_thai' => 'Trạng thái',
            'nguon_du_lieu' => 'Nguồn dữ liệu',
            'lat' => 'Lat',
            'long' => 'Long',
            'geom' => 'Geom',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
