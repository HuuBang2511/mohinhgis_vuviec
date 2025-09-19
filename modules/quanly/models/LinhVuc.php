<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "linh_vuc".
 *
 * @property int $id
 * @property string $ten_linh_vuc
 * @property float $trong_so_nghiem_trong
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property VuViec[] $vuViecs
 * @property VuViec[] $vuViecs0
 */
class LinhVuc extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linh_vuc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_linh_vuc'], 'required'],
            [['trong_so_nghiem_trong'], 'number'],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ten_linh_vuc'], 'string', 'max' => 100],
            [['ten_linh_vuc'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten_linh_vuc' => 'Ten lĩnh vực',
            'trong_so_nghiem_trong' => 'Trọng số nghiêm trọng',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[VuViecs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs()
    {
        return $this->hasMany(VuViec::className(), ['linh_vuc_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs0()
    {
        return $this->hasMany(VuViec::className(), ['linh_vuc_id' => 'id']);
    }
}
