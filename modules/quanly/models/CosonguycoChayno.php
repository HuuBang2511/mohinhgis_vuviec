<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "cosonguyco_chayno".
 *
 * @property int $id
 * @property string|null $ten_co_so
 * @property string|null $loai_hinh
 * @property string|null $muc_do_nguy_co
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $don_vi_quan_ly
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class CosonguycoChayno extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cosonguyco_chayno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_co_so', 'loai_hinh', 'muc_do_nguy_co', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'geom', 'created_by', 'updated_by'], 'string'],
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
            'ten_co_so' => 'Tên cơ sở',
            'loai_hinh' => 'Loại hình',
            'muc_do_nguy_co' => 'Mức độ nguy cơ',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'don_vi_quan_ly' => 'Đơn vị quản lý',
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
