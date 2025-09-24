<?php

namespace app\modules\quanly\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "kp".
 *
 * @property int $id
 * @property string|null $geom
 * @property float|null $OBJECTID
 * @property string|null $TenPhuong
 * @property string|null $TenQuan
 * @property string|null $TenKhuPho
 * @property float|null $MaQuan
 * @property string|null $MaPhuong
 * @property float|null $Shape_Leng
 * @property float|null $Shape_Area
 * @property string|null $mv_dvhc
 */
class Kp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kp';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['OBJECTID', 'MaQuan', 'Shape_Leng', 'Shape_Area'], 'number'],
            [['TenPhuong', 'TenQuan', 'TenKhuPho', 'MaPhuong'], 'string', 'max' => 50],
            [['mv_dvhc'], 'string', 'max' => 254],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geom' => 'Geom',
            'OBJECTID' => 'Objectid',
            'TenPhuong' => 'Ten Phuong',
            'TenQuan' => 'Ten Quan',
            'TenKhuPho' => 'Ten Khu Pho',
            'MaQuan' => 'Ma Quan',
            'MaPhuong' => 'Ma Phuong',
            'Shape_Leng' => 'Shape Leng',
            'Shape_Area' => 'Shape Area',
            'mv_dvhc' => 'Mv Dvhc',
        ];
    }

    public function getPhuongXa()
    {
        return $this->hasOne(Phuongxa::class, ['maXa' => 'mv_dvhc']);
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
