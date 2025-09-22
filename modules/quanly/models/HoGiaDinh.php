<?php

namespace app\modules\quanly\models;

use app\modules\quanly\models\NguoiDan;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\NocGia;
use Yii;

/**
 * This is the model class for table "ho_gia_dinh".
 *
 * @property int $id
 * @property string|null $ma_hsct
 * @property int|null $nocgia_id
 * @property int|null $loaicutru_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $status
 *
 * @property NocGia $nocgia
 * @property NguoiDan[] $nguoiDans
 */
class HoGiaDinh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ho_gia_dinh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ma_hsct'], 'string'],
            [['nocgia_id', 'loaicutru_id', 'created_by', 'updated_by', 'status'], 'default', 'value' => null],
            [['nocgia_id', 'loaicutru_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nocgia_id'], 'exist', 'skipOnError' => true, 'targetClass' => NocGia::className(), 'targetAttribute' => ['nocgia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_hsct' => 'Mã hsct',
            'nocgia_id' => 'Nóc gia',
            'loaicutru_id' => 'Loai cư trú',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Nocgia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNocgia()
    {
        return $this->hasOne(NocGia::className(), ['id' => 'nocgia_id']);
    }

    /**
     * Gets query for [[NguoiDans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNguoiDans()
    {
        return $this->hasMany(NguoiDan::className(), ['hogiadinh_id' => 'id']);
    }

    public function getChuho()
    {
        return $this->hasOne(NguoiDan::class, ['hogiadinh_id' => 'id'])->where(['quanhechuho_id' => 1]);
    }
}
