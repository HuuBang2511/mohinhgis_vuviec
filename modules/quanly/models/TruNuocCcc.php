<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "tru_nuoc_ccc".
 *
 * @property int $id
 * @property string|null $ma_tru
 * @property string|null $tinh_trang
 * @property float|null $ap_suat_psi
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
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
class TruNuocCcc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tru_nuoc_ccc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ma_tru', 'tinh_trang', 'phuong_xa', 'quan_huyen', 'ghi_chu', 'geom', 'created_by', 'updated_by'], 'string'],
            [['ap_suat_psi', 'lat', 'long'], 'number'],
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
            'ma_tru' => 'Mã trụ',
            'tinh_trang' => 'Tình trạng',
            'ap_suat_psi' => 'Áp xuất PSI',
            'phuong_xa' => 'Phường xã',
            'quan_huyen' => 'Tỉnh Thành phố',
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
