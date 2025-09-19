<?php

namespace app\modules\quanly\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "kp".
 *
 * @property int $OBJECTID
 * @property string|null $geom
 * @property string|null $TenPhuong
 * @property string|null $TenQuan
 * @property string|null $TenKhuPho
 * @property float|null $MaQuan
 * @property string|null $MaPhuong
 * @property float|null $Shape_Length
 * @property float|null $Shape_Area
 * @property string|null $mv_dvhc
 *
 * @property Phuongxa $phuongXa
 */
class Kp extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kp';
    }

    /**
     * Khai báo rõ ràng khóa chính của bảng
     */
    public static function primaryKey()
    {
        return ['OBJECTID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['OBJECTID'], 'required'],
            [['OBJECTID'], 'integer'],
            [['geom'], 'string'],
            [['MaQuan', 'Shape_Length', 'Shape_Area'], 'number'],
            [['TenPhuong', 'TenQuan', 'TenKhuPho', 'MaPhuong'], 'string', 'max' => 50],
            [['mv_dvhc'], 'string', 'max' => 255],
            [['OBJECTID'], 'unique'],
            [['mv_dvhc'], 'exist', 'skipOnError' => true, 'targetClass' => Phuongxa::class, 'targetAttribute' => ['mv_dvhc' => 'ma_dvhc']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'OBJECTID' => 'ID Khu phố',
            'geom' => 'Dữ liệu không gian',
            'TenPhuong' => 'Tên Phường',
            'TenQuan' => 'Tên Quận',
            'TenKhuPho' => 'Tên Khu phố',
            'MaQuan' => 'Mã Quận',
            'MaPhuong' => 'Mã Phường',
            'Shape_Length' => 'Chu vi',
            'Shape_Area' => 'Diện tích',
            'mv_dvhc' => 'Mã ĐVHC Phường/Xã',
        ];
    }

    /**
     * Gets query for [[Phuongxa]].
     * Đổi tên hàm cho rõ nghĩa
     * @return \yii\db\ActiveQuery
     */
    public function getPhuongXa()
    {
        return $this->hasOne(Phuongxa::class, ['ma_dvhc' => 'mv_dvhc']);
    }

    /**
     * Hàm tiện ích để lấy geometry dưới dạng GeoJSON
     * Rất quan trọng để hiển thị trên các thư viện bản đồ như Leaflet
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