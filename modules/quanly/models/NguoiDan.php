<?php

namespace app\modules\quanly\models;
use app\modules\quanly\models\danhmuc\DmLoaicutru;
use app\modules\quanly\models\danhmuc\DmQuanhechuho;
use app\modules\quanly\models\danhmuc\DmGioitinh;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "nguoi_dan".
 *
 * @property int $id
 * @property string $ho_ten
 * @property string|null $dia_chi
 * @property string|null $so_dien_thoai
 * @property string|null $email
 * @property string|null $nhom_doi_tuong
 * @property int|null $gioitinh_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $hogiadinh_id
 * @property int|null $loaicutru_id
 * @property string|null $cccd
 * @property string|null $cccd_ngaycap
 * @property string|null $cccd_noicap
 * @property int|null $quanhechuho_id
 *
 * @property DmGioitinh $gioitinh
 * @property DmLoaicutru $loaicutru
 * @property DmQuanhechuho $quanhechuho
 * @property HoGiaDinh $hogiadinh
 * @property VuViec[] $vuViecs
 * @property VuViec[] $vuViecs0
 */
class NguoiDan extends QuanlyBaseModel{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nguoi_dan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ho_ten'], 'required'],
            [['gioitinh_id', 'created_by', 'updated_by', 'hogiadinh_id', 'loaicutru_id', 'quanhechuho_id'], 'default', 'value' => null],
            [['gioitinh_id', 'created_by', 'updated_by', 'hogiadinh_id', 'loaicutru_id', 'quanhechuho_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['cccd', 'cccd_ngaycap', 'cccd_noicap'], 'string'],
            [['ho_ten', 'email'], 'string', 'max' => 100],
            [['dia_chi'], 'string', 'max' => 255],
            [['so_dien_thoai'], 'string', 'max' => 15],
            [['nhom_doi_tuong'], 'string', 'max' => 50],
            //[['so_dien_thoai'], 'unique'],
            [['gioitinh_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmGioitinh::className(), 'targetAttribute' => ['gioitinh_id' => 'id']],
            [['loaicutru_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoaicutru::className(), 'targetAttribute' => ['loaicutru_id' => 'id']],
            [['quanhechuho_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmQuanhechuho::className(), 'targetAttribute' => ['quanhechuho_id' => 'id']],
            [['hogiadinh_id'], 'exist', 'skipOnError' => true, 'targetClass' => HoGiaDinh::className(), 'targetAttribute' => ['hogiadinh_id' => 'id']],
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
            'dia_chi' => 'Địa chỉ',
            'so_dien_thoai' => 'Số điện thoại',
            'email' => 'Email',
            'nhom_doi_tuong' => 'Nhóm đối tượng',
            'gioitinh_id' => 'Giới tính',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'hogiadinh_id' => 'Hộ gia đình',
            'loaicutru_id' => 'Loại cư trú',
            'cccd' => 'Cccd',
            'cccd_ngaycap' => 'CCCD ngày cấp',
            'cccd_noicap' => 'CCCD nơi cấp',
            'quanhechuho_id' => 'Quan hệ chủ hộ',
        ];
    }

    /**
     * Gets query for [[Gioitinh]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGioitinh()
    {
        return $this->hasOne(DmGioitinh::className(), ['id' => 'gioitinh_id']);
    }

    /**
     * Gets query for [[Loaicutru]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoaicutru()
    {
        return $this->hasOne(DmLoaicutru::className(), ['id' => 'loaicutru_id']);
    }

    /**
     * Gets query for [[Quanhechuho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuanhechuho()
    {
        return $this->hasOne(DmQuanhechuho::className(), ['id' => 'quanhechuho_id']);
    }

    /**
     * Gets query for [[Hogiadinh]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHogiadinh()
    {
        return $this->hasOne(HoGiaDinh::className(), ['id' => 'hogiadinh_id']);
    }

    /**
     * Gets query for [[VuViecs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs()
    {
        return $this->hasMany(VuViec::className(), ['nguoi_dan_id' => 'id']);
    }

    /**
     * Gets query for [[VuViecs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuViecs0()
    {
        return $this->hasMany(VuViec::className(), ['nguoi_dan_id' => 'id']);
    }
}
