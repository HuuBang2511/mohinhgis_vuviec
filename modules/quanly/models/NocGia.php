<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "noc_gia".
 *
 * @property int $id
 * @property string|null $so_nha
 * @property string|null $ten_duong
 * @property int|null $khupho_id
 * @property string|null $phuongxa_id
 * @property string|null $dia_chi
 * @property string|null $geom
 * @property string|null $lat
 * @property string|null $long
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property HoGiaDinh[] $hoGiaDinhs
 * @property Kp $khupho
 * @property Phuongxa $phuongxa
 */
class NocGia extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'noc_gia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['so_nha', 'ten_duong', 'phuongxa_id', 'dia_chi', 'geom', 'lat', 'long'], 'string'],
            [['khupho_id', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['khupho_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['khupho_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kp::className(), 'targetAttribute' => ['khupho_id' => 'OBJECTID']],
            [['phuongxa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phuongxa::className(), 'targetAttribute' => ['phuongxa_id' => 'ma_dvhc']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'so_nha' => 'Số nhà',
            'ten_duong' => 'Tên đường',
            'khupho_id' => 'Khu phố',
            'phuongxa_id' => 'Phường xã',
            'dia_chi' => 'Địa chỉ',
            'geom' => 'Geom',
            'lat' => 'Lat',
            'long' => 'Long',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[HoGiaDinhs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHoGiaDinhs()
    {
        return $this->hasMany(HoGiaDinh::className(), ['nocgia_id' => 'id']);
    }

    /**
     * Gets query for [[Khupho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKhupho()
    {
        return $this->hasOne(Kp::className(), ['OBJECTID' => 'khupho_id']);
    }

    /**
     * Gets query for [[Phuongxa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhuongxa()
    {
        return $this->hasOne(Phuongxa::className(), ['ma_dvhc' => 'phuongxa_id']);
    }
}
