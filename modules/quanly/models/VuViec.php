<?php

namespace app\modules\quanly\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\modules\quanly\base\QuanlyBaseModel;

/**
 * This is the model class for table "vu_viec".
 *
 * @property int $id
 * @property string $ma_vu_viec
 * @property string $tom_tat_noi_dung
 * @property string|null $mo_ta_chi_tiet
 * @property string $ngay_tiep_nhan
 * @property string|null $han_xu_ly
 * @property string $vi_tri_su_viec
 * @property string|null $dia_chi_su_viec
 * @property int $nguoi_dan_id
 * @property int $linh_vuc_id
 * @property int $don_vi_tiep_nhan_id
 * @property int $can_bo_tiep_nhan_id
 * @property int $trang_thai_hien_tai_id
 * @property int|null $so_nguoi_anh_huong
 * @property bool|null $is_lap_lai
 * @property float|null $diem_rui_ro
 * @property float|null $diem_cam_tinh
 * @property string|null $muc_do_canh_bao
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $ma_dvhc_phuongxa
 * @property int|null $objectid_khupho
 * @property int|null $vu_viec_goc_id
 *
 * @property LichSuXuLy[] $lichSuXuLies
 * @property TaiLieuDinhKem[] $taiLieuDinhKems
 * @property CanBo $canBoTiepNhan
 * @property DonVi $donViTiepNhan
 * @property LinhVuc $linhVuc
 * @property NguoiDan $nguoiDan
 * @property TrangThaiXuLy $trangThaiHienTai
 * @property Phuongxa $phuongXa
 * @property VuViec $vuViecGoc
 * @property VuViec[] $cacVuViecLapLai
 */
class VuViec extends QuanlyBaseModel
{
    // Hằng số để quản lý mức độ cảnh báo
    const CANH_BAO_XANH = 'Xanh';
    const CANH_BAO_VANG = 'Vàng';
    const CANH_BAO_DO = 'Đỏ';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vu_viec';
    }

    /**
     * Thêm TimestampBehavior để tự động cập nhật created_at và updated_at
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
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
            //[['ma_vu_viec', 'tom_tat_noi_dung', 'nguoi_dan_id', 'linh_vuc_id', 'don_vi_tiep_nhan_id', 'can_bo_tiep_nhan_id', 'trang_thai_hien_tai_id', 'lat', 'long'], 'required'],
            //[['so_nguoi_anh_huong'], 'required'],
            [['mo_ta_chi_tiet', 'vi_tri_su_viec', 'url_dinhkem', 'url_dinhkem_nguoidan'], 'string'],
            [['ngay_tiep_nhan', 'han_xu_ly'], 'safe'],
            [['nguoi_dan_id', 'linh_vuc_id', 'don_vi_tiep_nhan_id', 'can_bo_tiep_nhan_id', 'trang_thai_hien_tai_id', 'so_nguoi_anh_huong', 'objectid_khupho', 'vu_viec_goc_id', 'status'], 'default', 'value' => null],
            [['nguoi_dan_id', 'linh_vuc_id', 'don_vi_tiep_nhan_id', 'can_bo_tiep_nhan_id', 'trang_thai_hien_tai_id', 'so_nguoi_anh_huong', 'objectid_khupho', 'vu_viec_goc_id', 'status'], 'integer'],
            [['is_lap_lai', 'is_nguoidanthem'], 'boolean'],
            [['diem_rui_ro', 'diem_cam_tinh'], 'number'],
            [['ma_vu_viec'], 'string', 'max' => 20],
            [['tom_tat_noi_dung', 'dia_chi_su_viec'], 'string', 'max' => 500],
            [['muc_do_canh_bao'], 'string', 'max' => 10],
            [['ma_dvhc_phuongxa'], 'string', 'max' => 255],
            //[['ma_vu_viec'], 'unique'],
            [['can_bo_tiep_nhan_id'], 'exist', 'skipOnError' => true, 'targetClass' => CanBo::class, 'targetAttribute' => ['can_bo_tiep_nhan_id' => 'id']],
            [['don_vi_tiep_nhan_id'], 'exist', 'skipOnError' => true, 'targetClass' => DonVi::class, 'targetAttribute' => ['don_vi_tiep_nhan_id' => 'id']],
            [['linh_vuc_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhVuc::class, 'targetAttribute' => ['linh_vuc_id' => 'id']],
            [['nguoi_dan_id'], 'exist', 'skipOnError' => true, 'targetClass' => NguoiDan::class, 'targetAttribute' => ['nguoi_dan_id' => 'id']],
            [['trang_thai_hien_tai_id'], 'exist', 'skipOnError' => true, 'targetClass' => TrangThaiXuLy::class, 'targetAttribute' => ['trang_thai_hien_tai_id' => 'id']],
            [['vu_viec_goc_id'], 'exist', 'skipOnError' => true, 'targetClass' => VuViec::class, 'targetAttribute' => ['vu_viec_goc_id' => 'id']],
            [['lat', 'long'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_vu_viec' => 'Mã Vụ việc',
            'tom_tat_noi_dung' => 'Tóm tắt Nội dung',
            'mo_ta_chi_tiet' => 'Mô tả Chi tiết',
            'ngay_tiep_nhan' => 'Ngày Tiếp nhận',
            'han_xu_ly' => 'Hạn Xử lý',
            'vi_tri_su_viec' => 'Vị trí vụ việc',
            'dia_chi_su_viec' => 'Địa chỉ Sự việc',
            'nguoi_dan_id' => 'Người dân',
            'linh_vuc_id' => 'Lĩnh vực',
            'don_vi_tiep_nhan_id' => 'Đơn vị Tiếp nhận',
            'can_bo_tiep_nhan_id' => 'Cán bộ Tiếp nhận',
            'trang_thai_hien_tai_id' => 'Trạng thái Hiện tại',
            'so_nguoi_anh_huong' => 'Số người Ảnh hưởng',
            'is_lap_lai' => 'Vụ việc Lặp lại',
            'diem_rui_ro' => 'Điểm Rủi ro',
            'diem_cam_tinh' => 'Điểm Cảm tính',
            'muc_do_canh_bao' => 'Mức độ Cảnh báo',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'ma_dvhc_phuongxa' => 'Mã ĐVHC Phường/Xã',
            'objectid_khupho' => 'Khu phố',
            'vu_viec_goc_id' => 'Vụ việc Gốc',
            'lat' => 'lat',
            'long' => 'long',
            'status' => 'Status',
            'url_dinhkem' => 'url_dinhkem',
            'is_nguoidanthem' => 'Người dân thêm mới',
            'url_dinhkem_nguoidan' => 'url_dinhkem_nguoidan',
        ];
    }

    //================================================================
    // CẤU HÌNH THUẬT TOÁN CHẤM ĐIỂM RỦI RO (PHIÊN BẢN NÂNG CAO)
    //================================================================

    // Trọng số cho các yếu tố
    public static $w_nghiem_trong = 0.30;  // Mức độ nghiêm trọng của Lĩnh vực & Đối tượng
    public static $w_cam_tinh = 0.25;      // Thái độ, cảm tính của người phản ánh
    public static $w_lap_lai = 0.25;       // Tính lặp lại / Quá hạn
    public static $w_tan_suat = 0.10;      // Tần suất trong khu vực
    public static $w_quy_mo = 0.10;        // Quy mô, số người ảnh hưởng

    // Ngưỡng phân loại Xanh-Vàng-Đỏ
    public static $nguong_do = 80;
    public static $nguong_vang = 40;

    // Điểm phạt cho các trường hợp đặc biệt
    public static $diem_phat_lap_lai = 100;
    public static $diem_phat_qua_han = 80;
    public static $diem_thuong_yeu_the = 20; // Điểm cộng thêm cho đối tượng dễ bị tổn thương


    //================================================================
    // CÁC HÀM QUAN HỆ
    //================================================================
    
    public function getLichSuXuLies()
    {
        return $this->hasMany(LichSuXuLy::class, ['vu_viec_id' => 'id']);
    }

    public function getTaiLieuDinhKems()
    {
        return $this->hasMany(TaiLieuDinhKem::class, ['vu_viec_id' => 'id']);
    }

    public function getCanBoTiepNhan()
    {
        return $this->hasOne(CanBo::class, ['id' => 'can_bo_tiep_nhan_id']);
    }

    public function getDonViTiepNhan()
    {
        return $this->hasOne(DonVi::class, ['id' => 'don_vi_tiep_nhan_id']);
    }

    public function getLinhVuc()
    {
        return $this->hasOne(LinhVuc::class, ['id' => 'linh_vuc_id']);
    }

    public function getNguoiDan()
    {
        return $this->hasOne(NguoiDan::class, ['id' => 'nguoi_dan_id']);
    }

    public function getKhupho()
    {
        return $this->hasOne(Kp::class, ['OBJECTID' => 'objectid_khupho']);
    }

    public function getTrangThaiHienTai()
    {
        return $this->hasOne(TrangThaiXuLy::class, ['id' => 'trang_thai_hien_tai_id']);
    }

    public function getVuViecGoc()
    {
        return $this->hasOne(VuViec::class, ['id' => 'vu_viec_goc_id']);
    }
    
    public function getCacVuViecLapLai()
    {
        return $this->hasMany(VuViec::class, ['vu_viec_goc_id' => 'id']);
    }

    /**
     * Gets query for [[PhuongXa]].
     * @return \yii\db\ActiveQuery
     */
    public function getPhuongXa()
    {
        return $this->hasOne(Phuongxa::class, ['ma_dvhc' => 'ma_dvhc_phuongxa']);
    }

    //================================================================
    // CÁC HÀM TÍNH ĐIỂM RỦI RO (PHIÊN BẢN NÂNG CAO)
    //================================================================

    /**
     * Hàm chính: Tính toán và cập nhật điểm rủi ro
     * @param int $tanSuatKhuVuc Điểm tần suất của khu vực (0-100)
     * @param int $diemTruyenThong Điểm ảnh hưởng từ mạng xã hội (0-100)
     * @return bool
     */
    public function updateDiemRuiRo($tanSuatKhuVuc = 0, $diemTruyenThong = 0)
    {
        // 1. Điểm Nghiêm trọng (kết hợp Lĩnh vực và Đối tượng)
        $diemNghiemTrong = $this->getDiemNghiemTrong();

        // 2. Điểm Cảm tính (từ API phân tích ngôn ngữ)
        // GIẢ ĐỊNH: cột diem_cam_tinh đã được cập nhật từ một service NLP bên ngoài
        $diemCamTinh = $this->diem_cam_tinh ?? 0;

        // 3. Điểm Lặp lại và Quá hạn
        $diemLapLai = $this->getDiemLapLaiVaQuaHan();
        
        // 4. Điểm Quy mô
        $diemQuyMo = $this->getDiemQuyMo();

        // Áp dụng công thức tổng hợp, thêm điểm truyền thông như một yếu tố cộng thẳng
        $diemTongHop = (self::$w_nghiem_trong * $diemNghiemTrong) +
                        (self::$w_cam_tinh * $diemCamTinh) +
                        (self::$w_lap_lai * $diemLapLai) +
                        (self::$w_tan_suat * $tanSuatKhuVuc) +
                        (self::$w_quy_mo * $diemQuyMo) +
                        $diemTruyenThong; // Cộng điểm lan truyền media
        
        $this->diem_rui_ro = min(100, round($diemTongHop, 2)); // Giới hạn điểm tối đa là 100
        $this->muc_do_canh_bao = $this->xacDinhMucDoCanhBao($this->diem_rui_ro);

        return $this->save(false, ['diem_rui_ro', 'muc_do_canh_bao', 'diem_cam_tinh']);
    }

    /**
     * Lấy điểm mức độ nghiêm trọng (kết hợp Lĩnh vực và đối tượng yếu thế)
     * @return float|int
     */
    private function getDiemNghiemTrong()
    {
        $diem = 0;
        // Điểm từ lĩnh vực
        if ($this->linhVuc) {
            $diem += $this->linhVuc->trong_so_nghiem_trong * 50;
        }
        
        // Cộng điểm nếu là đối tượng dễ bị tổn thương
        if ($this->nguoiDan && $this->nguoiDan->nhom_doi_tuong !== 'Thường') {
            $diem += self::$diem_thuong_yeu_the;
        }

        return min(100, $diem); // Giới hạn điểm tối đa là 100
    }
    
    /**
     * Lấy điểm quy mô ảnh hưởng
     * Chuyển đổi số người bị ảnh hưởng sang thang điểm 100
     * @return float|int
     */
    private function getDiemQuyMo()
    {
        $soNguoi = $this->so_nguoi_anh_huong ?? 1;
        // Dùng hàm log để điểm tăng chậm lại khi số người quá lớn
        if ($soNguoi <= 1) return 0;
        return min(100, log($soNguoi, 1000) * 100);
    }
    
    /**
     * Lấy điểm cho tính lặp lại và quá hạn
     * @return int
     */
    private function getDiemLapLaiVaQuaHan()
    {
        // Ưu tiên cao nhất cho vụ việc lặp lại
        if ($this->is_lap_lai === true) {
            return self::$diem_phat_lap_lai;
        }

        // Kiểm tra nếu bị quá hạn
        if ($this->han_xu_ly && strtotime($this->han_xu_ly) < time()) {
            // Kiểm tra xem trạng thái đã giải quyết chưa
            if ($this->trangThaiHienTai && $this->trangThaiHienTai->ten_trang_thai !== 'Đã giải quyết') {
                 return self::$diem_phat_qua_han;
            }
        }
        
        return 0;
    }

    /**
     * Phân loại Xanh-Vàng-Đỏ dựa trên điểm số
     * @param float $diem
     * @return string
     */
    private function xacDinhMucDoCanhBao($diem)
    {
        if ($diem > self::$nguong_do) {
            return self::CANH_BAO_DO;
        }
        if ($diem > self::$nguong_vang) {
            return self::CANH_BAO_VANG;
        }
        return self::CANH_BAO_XANH;
    }
}
