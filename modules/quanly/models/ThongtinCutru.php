<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use app\modules\quanly\models\danhmuc\DmLoaicutru;
use Yii;

/**
 * This is the model class for table "thongtin_cutru".
 *
 * @property int $id
 * @property string|null $ngaybatdau
 * @property string|null $ngayketthuc
 * @property int|null $loaicutru_id
 * @property int|null $nguoidan_id
 * @property string|null $diachi_thuongtru
 * @property string|null $diachi_cutru
 * @property string|null $diachi_tamtru
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property DmLoaicutru $loaicutru
 * @property NguoiDan $nguoidan
 */
class ThongtinCutru extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thongtin_cutru';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ngaybatdau', 'ngayketthuc', 'created_at', 'updated_at', 'ghichu'], 'safe'],
            [['loaicutru_id', 'nguoidan_id', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['loaicutru_id', 'nguoidan_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['diachi_thuongtru', 'diachi_cutru', 'diachi_tamtru'], 'string'],
            [['loaicutru_id'], 'exist', 'skipOnError' => true, 'targetClass' => DmLoaicutru::className(), 'targetAttribute' => ['loaicutru_id' => 'id']],
            [['nguoidan_id'], 'exist', 'skipOnError' => true, 'targetClass' => NguoiDan::className(), 'targetAttribute' => ['nguoidan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ngaybatdau' => 'Ngày bắt đầu',
            'ngayketthuc' => 'Ngày kết thúc',
            'loaicutru_id' => 'Loại cư trú',
            'nguoidan_id' => 'Người dân',
            'diachi_thuongtru' => 'Địa chỉ thường trú',
            'diachi_cutru' => 'Địa chỉ cư trú',
            'diachi_tamtru' => 'Địa chỉ tạm trú',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'ghichu' => 'Ghi chú',
        ];
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
     * Gets query for [[Nguoidan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNguoidan()
    {
        return $this->hasOne(NguoiDan::className(), ['id' => 'nguoidan_id']);
    }
}
