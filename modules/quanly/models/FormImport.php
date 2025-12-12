<?php
namespace app\modules\quanly\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\services\UtilityService;

class FormImport extends Model
{
    public $file;
    public $link;
    public $table;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => FALSE, 'extensions' => 'xlsx,xls', 'maxSize' => 1024 * 1024 * 10],
            [['table'], 'string'],
            [['table'], 'required']
        ];
    }

    public function uploadFile()
    {

        if ($this->validate()) {
            $dir = 'uploads/files/import';
            date_default_timezone_set("Asia/Ho_Chi_Minh");
            $today = (date("YmdHis"));
            $baseName = str_replace(' ', '-', strtolower(UtilityService::utf8convert(trim($this->file->baseName))));
            $this->link = $file_path = $dir .  '/' . $baseName . '_' . $today . '.' . $this->file->extension;
            $this->file->saveAs($file_path);
            return true;
        } else {
            return false;
        }
    }
}