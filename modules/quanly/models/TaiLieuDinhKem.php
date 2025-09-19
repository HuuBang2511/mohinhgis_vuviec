<?php

namespace app\modules\quanly\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;
use app\modules\quanly\base\QuanlyBaseModel;

/**
 * This is the model class for table "tai_lieu_dinh_kem".
 *
 * @property int $id
 * @property int $vu_viec_id
 * @property string $ten_file_goc
 * @property string $duong_dan_file
 * @property string|null $loai_file
 * @property int|null $dung_luong_kb
 * @property string|null $uploaded_at
 *
 * @property VuViec $vuViec
 */
class TaiLieuDinhKem extends QuanlyBaseModel
{
    /**
     * @var UploadedFile
     * Thuộc tính ảo để nhận file từ form
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tai_lieu_dinh_kem';
    }

    /**
     * Tự động cập nhật `uploaded_at`
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['uploaded_at'],
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
            [['vu_viec_id'], 'required'],
            [['vu_viec_id', 'dung_luong_kb', 'status'], 'integer'],
            [['ten_file_goc'], 'string', 'max' => 255],
            [['duong_dan_file'], 'string', 'max' => 500],
            [['loai_file'], 'string', 'max' => 50],
            [['vu_viec_id'], 'exist', 'skipOnError' => true, 'targetClass' => VuViec::class, 'targetAttribute' => ['vu_viec_id' => 'id']],
            [['is_nguoidangui'], 'boolean'],
            
            // Rule cho việc upload file
            //[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, doc, docx, xls, xlsx', 'maxSize' => 1024 * 1024 * 10], // Giới hạn 10MB
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
            'ten_file_goc' => 'Tên File Gốc',
            'duong_dan_file' => 'Đường Dẫn File',
            'loai_file' => 'Loại File',
            'dung_luong_kb' => 'Dung Lượng (KB)',
            'uploaded_at' => 'Ngày Tải lên',
            //'file' => 'Chọn File Đính kèm',
            'status' => 'status',
            'is_nguoidangui' => 'File người dân gửi'
        ];
    }

    /**
     * Gets query for [[VuViec]].
     * @return \yii\db\ActiveQuery
     */
    public function getVuViec()
    {
        return $this->hasOne(VuViec::class, ['id' => 'vu_viec_id']);
    }
    
    /**
     * Xử lý việc upload file vật lý lên server
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            // Tạo đường dẫn lưu file
            $path = 'uploads/tailieu/' . date('Y/m/');
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            // Tạo tên file mới để tránh trùng lặp
            $fileName = Yii::$app->security->generateRandomString() . '.' . $this->file->extension;
            $filePath = $path . $fileName;

            if ($this->file->saveAs($filePath)) {
                // Gán các thuộc tính cho model để lưu vào CSDL
                $this->duong_dan_file = $filePath;
                $this->ten_file_goc = $this->file->baseName . '.' . $this->file->extension;
                $this->loai_file = $this->file->extension;
                $this->dung_luong_kb = round($this->file->size / 1024);
                return true;
            }
        }
        return false;
    }

    /**
     * Tự động xóa file vật lý khi record trong CSDL bị xóa
     */
    public function afterDelete()
    {
        parent::afterDelete();
        if ($this->duong_dan_file && file_exists($this->duong_dan_file)) {
            unlink($this->duong_dan_file);
        }
    }

    /**
     * Lấy đường dẫn URL để truy cập file
     * @return string
     */
    public function getUrl()
    {
        return Yii::getAlias('@web') . '/' . $this->duong_dan_file;
    }
}