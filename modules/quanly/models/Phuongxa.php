<?php

namespace app\modules\quanly\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "phuongxa".
 *
 * @property string|null $geom
 * @property string|null $tenTinh
 * @property string|null $maTinh
 * @property string|null $tenXa
 * @property string|null $maXa
 * @property string|null $danSo
 * @property string|null $dienTich
 * @property string|null $ghiChu
 * @property int $id
 */
class Phuongxa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phuongxa';
    }


    public static function primaryKey()
    {
        return ['maXa'];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['tenTinh', 'maTinh', 'tenXa', 'danSo', 'dienTich', 'ghiChu'], 'string', 'max' => 254],
            [['maXa'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'geom' => 'Geom',
            'tenTinh' => 'Ten Tinh',
            'maTinh' => 'Ma Tinh',
            'tenXa' => 'Ten Xa',
            'maXa' => 'Ma Xa',
            'danSo' => 'Dan So',
            'dienTich' => 'Dien Tich',
            'ghiChu' => 'Ghi Chu',
            'id' => 'ID',
        ];
    }

    public static function findWithGeoJSON()
    {
        return static::find()->select([
            '*', // Lấy tất cả các cột khác
            new Expression('ST_AsGeoJSON(geom) as geojson') // Chuyển đổi cột geom sang định dạng GeoJSON
        ]);
    }
}
