<?php

namespace app\modules\quanly\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "phuongxa".
 *
 * @property string $ma_dvhc
 * @property int $OBJECTID
 * @property string|null $geom
 * @property string|null $ten_dvhc
 * // ... các thuộc tính khác
 *
 * @property Kp[] $kps
 */
class Phuongxa extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phuongxa';
    }

    /**
     * Khai báo rõ ràng khóa chính của bảng là 'ma_dvhc'
     */
    public static function primaryKey()
    {
        return ['ma_dvhc'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Khóa chính 'ma_dvhc' là bắt buộc
            [['ma_dvhc'], 'required'],
            [['OBJECTID'], 'integer'],
            [['geom', 'sapxeptu'], 'string'],
            [['dan_so', 'dien_tich', 'tsdvhc_cap', 'so_phuong', 'mien_nui', 'Shape_Length', 'Shape_Area'], 'number'],
            [['ten_dvhc', 'tinh_thanh', 'so_xa', 'dac_khu', 'vung_cao', 'hai_dao', 'bi_thu', 'ho_ten_ct', 'ten_phongx'], 'string', 'max' => 254],
            [['quanhuyen_cu', 'tinhthanh_cu'], 'string', 'max' => 50],
            [['ma_dvhc'], 'string', 'max' => 255],
            [['sdt_ctubnd', 'sdt_bithu'], 'string', 'max' => 14],
            [['ma_dvhc'], 'unique'],
            [['OBJECTID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'OBJECTID' => 'Object ID',
            'geom' => 'Dữ liệu không gian',
            'ten_dvhc' => 'Tên Đơn vị HC',
            'tinh_thanh' => 'Tỉnh/Thành',
            'quanhuyen_cu' => 'Quận/Huyện cũ',
            'tinhthanh_cu' => 'Tỉnh/Thành cũ',
            'sapxeptu' => 'Sắp xếp từ',
            'dan_so' => 'Dân số',
            'dien_tich' => 'Diện tích',
            'tsdvhc_cap' => 'Tổng số ĐVHC cấp',
            'so_xa' => 'Số xã',
            'so_phuong' => 'Số phường',
            'dac_khu' => 'Đặc khu',
            'mien_nui' => 'Miền núi',
            'vung_cao' => 'Vùng cao',
            'hai_dao' => 'Hải đảo',
            'ma_dvhc' => 'Mã Đơn vị HC',
            'bi_thu' => 'Bí thư',
            'ho_ten_ct' => 'Họ tên Chủ tịch',
            'ten_phongx' => 'Tên phòng',
            'Shape_Length' => 'Chu vi',
            'Shape_Area' => 'Diện tích',
            'sdt_ctubnd' => 'SĐT Chủ tịch UBND',
            'sdt_bithu' => 'SĐT Bí thư',
        ];
    }

    /**
     * Gets query for [[Kps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKps()
    {
        return $this->hasMany(Kp::class, ['mv_dvhc' => 'ma_dvhc']);
    }
    
    /**
     * Hàm tiện ích để lấy geometry dưới dạng GeoJSON
     * @return \yii\db\ActiveQuery
     */
    public static function findWithGeoJSON()
    {
        return static::find()->select([
            '*', // Lấy tất cả các cột khác
            new Expression('ST_AsGeoJSON(geom) as geojson') // Chuyển đổi cột geom sang định dạng GeoJSON
        ]);
    }
}