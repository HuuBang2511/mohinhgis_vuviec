<?php

namespace app\modules\quanly\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\modules\quanly\base\QuanlyBaseModel;

/**
 * This is the model class for table "lich_su_xu_ly".
 *
 * @property int $id
 * @property int $vu_viec_id
 * @property int|null $can_bo_thuc_hien_id
 * @property int $trang_thai_id
 * @property string|null $ghi_chu_xu_ly
 * @property string|null $ngay_thuc_hien
 *
 * @property CanBo $canBoThucHien
 * @property TrangThaiXuLy $trangThai
 * @property VuViec $vuViec
 */
class LichSuXuLy extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lich_su_xu_ly';
    }

    /**
     * Thêm TimestampBehavior để tự động cập nhật ngay_thuc_hien
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // Chỉ tự động điền khi có bản ghi MỚI được tạo
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ngay_thuc_hien'],
                ],
                // Sử dụng hàm NOW() của DB để đảm bảo múi giờ chính xác
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trang_thai_id'], 'required'],
            [['vu_viec_id', 'can_bo_thuc_hien_id', 'trang_thai_id', 'status'], 'default', 'value' => null],
            [['vu_viec_id', 'can_bo_thuc_hien_id', 'trang_thai_id', 'status'], 'integer'],
            [['ghi_chu_xu_ly'], 'string'],
            [['ngay_thuc_hien'], 'safe'], // Để 'safe' để behavior có thể gán giá trị
            [['can_bo_thuc_hien_id'], 'exist', 'skipOnError' => true, 'targetClass' => CanBo::class, 'targetAttribute' => ['can_bo_thuc_hien_id' => 'id']],
            [['trang_thai_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrangThaiXuLy::class, 'targetAttribute' => ['trang_thai_id' => 'id']],
            [['vu_viec_id'], 'exist', 'skipOnError' => true, 'targetClass' => VuViec::class, 'targetAttribute' => ['vu_viec_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vu_viec_id' => 'Vụ việc',
            'can_bo_thuc_hien_id' => 'Cán bộ Thực hiện',
            'trang_thai_id' => 'Trạng thái',
            'ghi_chu_xu_ly' => 'Ghi chú Xử lý',
            'ngay_thuc_hien' => 'Ngày Thực hiện',
            'status' => 'Status',
        ];
    }

    // CÁC HÀM QUAN HỆ
    
    public function getCanBoThucHien()
    {
        return $this->hasOne(CanBo::class, ['id' => 'can_bo_thuc_hien_id']);
    }

    public function getTrangThai()
    {
        return $this->hasOne(TrangThaiXuLy::class, ['id' => 'trang_thai_id']);
    }

    public function getVuViec()
    {
        return $this->hasOne(VuViec::class, ['id' => 'vu_viec_id']);
    }
}