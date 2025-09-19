<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "trang_thai_xu_ly".
 *
 * @property int $id
 * @property string $ten_trang_thai
 * @property string|null $mo_ta
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property LichSuXuLy[] $lichSuXuLies
 * @property VuViec[] $vuViecs
 * @property VuViec[] $vuViecs0
 */
class TrangThaiXuLy extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trang_thai_xu_ly';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_trang_thai'], 'required'],
            [['mo_ta'], 'string'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ten_trang_thai'], 'string', 'max' => 50],
            [['ten_trang_thai'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten_trang_thai' => 'Tên trạng thái',
            'mo_ta' => 'Mô tả',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[LichSuXuLies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLichSuXuLies()
    {
        return $this->hasMany(LichSuXuLy::className(), ['trang_thai_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs()
    {
        return $this->hasMany(VuViec::className(), ['trang_thai_hien_tai_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs0()
    {
        return $this->hasMany(VuViec::className(), ['trang_thai_hien_tai_id' => 'id']);
    }
}
