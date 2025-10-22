<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "chot_tuantre".
 *
 * @property int $id
 * @property string|null $ten_chot
 * @property string|null $loai_chot
 * @property string|null $don_vi_phu_trach
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $gio_truc
 * @property string|null $ghi_chu
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class ChotTuantre extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chot_tuantre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_chot', 'loai_chot', 'don_vi_phu_trach', 'phuong_xa', 'quan_huyen', 'gio_truc', 'ghi_chu', 'geom', 'created_by', 'updated_by'], 'string'],
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
            'ten_chot' => 'Tên chốt',
            'loai_chot' => 'Loại chốt',
            'don_vi_phu_trach' => 'Đơn vị phụ trác',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'gio_truc' => 'Giờ trực',
            'ghi_chu' => 'Ghi chú',
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
