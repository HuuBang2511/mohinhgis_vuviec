<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "diem_tenannxh".
 *
 * @property int $id
 * @property string|null $ten_diem
 * @property string|null $loai_ten_nan
 * @property string|null $muc_do_nguy_co
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $tinh_trang_xu_ly
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class DiemTenannxh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diem_tenannxh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_diem', 'loai_ten_nan', 'muc_do_nguy_co', 'phuong_xa', 'quan_huyen', 'tinh_trang_xu_ly', 'geom', 'created_by', 'updated_by', 'file_dinhkem', 'motachung'], 'string'],
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
            'ten_diem' => 'Tên điểm',
            'loai_ten_nan' => 'Loại tệ nạn',
            'muc_do_nguy_co' => 'Mức độ nguy cơ',
            'phuong_xa' => 'Phường xã',
            'quan_huyen' => 'Tỉnh Thành phố',
            'tinh_trang_xu_ly' => 'Tình trạng xử lý',
            'lat' => 'Lat',
            'long' => 'Long',
            'geom' => 'Geom',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'file_dinhkem' => 'File đính kèm',
            'motachung' => 'Mô tả chung',
        ];
    }
}
