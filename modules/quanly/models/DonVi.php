<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "don_vi".
 *
 * @property int $id
 * @property string $ten_don_vi
 * @property int|null $parent_id
 * @property string|null $loai_don_vi
 *
 * @property CanBo[] $canBos
 * @property DonVi $parent
 * @property DonVi[] $donViCons
 * @property VuViec[] $vuViecs
 */
class DonVi extends QuanlyBaseModel
{
    // Hằng số cho các loại đơn vị
    const LOAI_SO = 'Sở';
    const LOAI_QUAN_HUYEN = 'Quận';
    const LOAI_PHUONG_XA = 'Phường';
    // Thêm các loại khác nếu cần...

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'don_vi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ten_don_vi'], 'required'],
            [['parent_id'], 'default', 'value' => null],
            [['parent_id'], 'integer'],
            [['ten_don_vi'], 'string', 'max' => 255],
            [['loai_don_vi'], 'string', 'max' => 50],
            [['ten_don_vi'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => DonVi::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ten_don_vi' => 'Tên Đơn vị',
            'parent_id' => 'Đơn vị Cha',
            'loai_don_vi' => 'Loại Đơn vị',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    // CÁC HÀM QUAN HỆ
    
    public function getCanBos()
    {
        return $this->hasMany(CanBo::class, ['don_vi_id' => 'id']);
    }

    /**
     * Lấy đơn vị cha
     */
    public function getParent()
    {
        return $this->hasOne(DonVi::class, ['id' => 'parent_id']);
    }

    /**
     * Lấy các đơn vị con
     * Đổi tên hàm cho rõ nghĩa
     */
    public function getDonViCons()
    {
        return $this->hasMany(DonVi::class, ['parent_id' => 'id']);
    }

    /**
     * Lấy các vụ việc do đơn vị này tiếp nhận
     */
    public function getVuViecs()
    {
        return $this->hasMany(VuViec::class, ['don_vi_tiep_nhan_id' => 'id']);
    }
    
    /**
     * Hàm tiện ích: Lấy danh sách đơn vị cho dropdown list
     * @return array
     */
    public static function getDonViForDropdown()
    {
        return static::find()->select(['ten_don_vi', 'id'])->indexBy('id')->column();
    }
}