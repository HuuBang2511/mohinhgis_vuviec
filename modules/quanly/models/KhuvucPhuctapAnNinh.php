<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "khuvuc_phuctap_an_ninh".
 *
 * @property int $id
 * @property string|null $ten
 * @property string|null $loai_khuvuc
 * @property string|null $muc_do_phuctap
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
class KhuvucPhuctapAnNinh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'khuvuc_phuctap_an_ninh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten', 'loai_khuvuc', 'muc_do_phuctap', 'phuong_xa', 'quan_huyen', 'ghi_chu', 'geom', 'created_by', 'updated_by','file_dinhkem'], 'string'],
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
            'loai_khuvuc' => 'Loại khu vực',
            'muc_do_phuctap' => 'Mức độ phức tạp',
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
            'file_dinhkem' => 'File đính kèm',
        ];
    }
}
