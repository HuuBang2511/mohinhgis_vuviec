<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "can_bo".
 *
 * @property int $id
 * @property string $ho_ten
 * @property string|null $email
 * @property string|null $mat_khau
 * @property int $don_vi_id
 * @property string $quyen_han
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property DonVi $donVi
 * @property LichSuXuLy[] $lichSuXuLies
 * @property VuViec[] $vuViecs
 * @property VuViec[] $vuViecs0
 */
class CanBo extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'can_bo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ho_ten', 'don_vi_id', 'quyen_han'], 'required'],
            [['don_vi_id', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['don_vi_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ho_ten', 'email'], 'string', 'max' => 100],
            [['mat_khau'], 'string', 'max' => 255],
            [['quyen_han'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['email'], 'required'],
            [['don_vi_id'], 'exist', 'skipOnError' => true, 'targetClass' => DonVi::className(), 'targetAttribute' => ['don_vi_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ho_ten' => 'Họ tên',
            'email' => 'Email',
            'mat_khau' => 'Mat Khau',
            'don_vi_id' => 'Đơn vị',
            'quyen_han' => 'Quyền hạn',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[DonVi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDonVi()
    {
        return $this->hasOne(DonVi::className(), ['id' => 'don_vi_id']);
    }

    /**
     * Gets query for [[LichSuXuLies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLichSuXuLies()
    {
        return $this->hasMany(LichSuXuLy::className(), ['can_bo_thuc_hien_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs()
    {
        return $this->hasMany(VuViec::className(), ['can_bo_tiep_nhan_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs0()
    {
        return $this->hasMany(VuViec::className(), ['can_bo_tiep_nhan_id' => 'id']);
    }
}
