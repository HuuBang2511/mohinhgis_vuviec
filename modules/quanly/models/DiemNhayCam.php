<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "diem_nhay_cam".
 *
 * @property int $id
 * @property string|null $tenloaihinh
 * @property string|null $thongtin
 * @property string|null $ghichu
 * @property string|null $geom
 * @property string|null $lat
 * @property string|null $long
 * @property int|null $vuviec_id
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property VuViec $vuviec
 */
class DiemNhayCam extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diem_nhay_cam';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tenloaihinh', 'thongtin', 'ghichu', 'geom', 'lat', 'long'], 'string'],
            [['vuviec_id', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['vuviec_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['vuviec_id'], 'exist', 'skipOnError' => true, 'targetClass' => VuViec::className(), 'targetAttribute' => ['vuviec_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tenloaihinh' => 'Tên loại hình',
            'thongtin' => 'Thông tin',
            'ghichu' => 'Ghi chú',
            'geom' => 'Geom',
            'lat' => 'Lat',
            'long' => 'Long',
            'vuviec_id' => 'Vuviec ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[Vuviec]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuviec()
    {
        return $this->hasOne(VuViec::className(), ['id' => 'vuviec_id']);
    }
}
