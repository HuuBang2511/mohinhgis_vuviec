<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "cosokinhdoanh_codk".
 *
 * @property int $id
 * @property string|null $ten_co_so
 * @property string|null $loai_hinh_kinh_doanh
 * @property string|null $chu_so_huu
 * @property string|null $so_dien_thoai
 * @property string|null $giay_phep_so
 * @property string|null $ngay_cap
 * @property string|null $phuong_xa
 * @property string|null $quan_huyen
 * @property string|null $trang_thai_hoat_dong
 * @property float|null $lat
 * @property float|null $long
 * @property string|null $geom
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 */
class CosokinhdoanhCodk extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cosokinhdoanh_codk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_co_so', 'loai_hinh_kinh_doanh', 'chu_so_huu', 'so_dien_thoai', 'giay_phep_so', 'phuong_xa', 'quan_huyen', 'trang_thai_hoat_dong', 'geom', 'created_by', 'updated_by'], 'string'],
            [['ngay_cap', 'created_at', 'updated_at'], 'safe'],
            [['lat', 'long'], 'number'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
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
            'loai_hinh_kinh_doanh' => 'Loại hình kinh doanh',
            'chu_so_huu' => 'Chủ sở hữu',
            'so_dien_thoai' => 'Số điện thoại',
            'giay_phep_so' => 'Giấy phép số',
            'ngay_cap' => 'Ngày cấp',
            'phuong_xa' => 'Phuong Xa',
            'quan_huyen' => 'Quan Huyen',
            'trang_thai_hoat_dong' => 'Trạng thái hoạt động',
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
