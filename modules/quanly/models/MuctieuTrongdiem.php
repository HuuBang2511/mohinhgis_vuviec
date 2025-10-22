<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "muctieu_trongdiem".
 *
 * @property int $id
 * @property string|null $ten
 * @property string|null $loai_muctieu
 * @property string|null $cap_quanly
 * @property string|null $dia_chi
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $trang_thai_an_ninh
 * @property string|null $mo_ta
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class MuctieuTrongdiem extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'muctieu_trongdiem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten', 'loai_muctieu', 'cap_quanly', 'dia_chi', 'phuong_xa', 'quan_huyen', 'trang_thai_an_ninh', 'mo_ta', 'geom', 'created_by', 'updated_by'], 'string'],
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
            'ten' => 'Tên',
            'loai_muctieu' => 'Loại mục tiêu',
            'cap_quanly' => 'Cấp quản lý',
            'dia_chi' => 'Địa chỉ',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'trang_thai_an_ninh' => 'Trạng thái an ninh',
            'mo_ta' => 'Mô tả',
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
