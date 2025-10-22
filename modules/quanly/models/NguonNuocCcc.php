<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "nguon_nuoc_ccc".
 *
 * @property int $id
 * @property string|null $ten_nguon
 * @property string|null $loai_nguon
 * @property float|null $dung_tich_m3
 * @property string|null $tinh_trang
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class NguonNuocCcc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nguon_nuoc_ccc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_nguon', 'loai_nguon', 'tinh_trang', 'phuong_xa', 'quan_huyen', 'geom', 'created_by', 'updated_by'], 'string'],
            [['dung_tich_m3', 'lat', 'long'], 'number'],
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
            'ten_nguon' => 'Tên nguồn',
            'loai_nguon' => 'Loại nguồn',
            'dung_tich_m3' => 'Dung tích',
            'tinh_trang' => 'Tình trạng',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
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
