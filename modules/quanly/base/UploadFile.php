<?php

namespace app\modules\quanly\base;

use yii\base\Model;

class UploadFile extends Model
{
    public $fileupload;
    public $imageupload;
    public $type;
    public $imageKhaibaoupload;

    public function rules()
    {
        return [
            [['fileupload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf,docx,xlsx,jpeg,png,jpg,tif', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 20],
            [['imageupload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 20],
            [['type'], 'string'],
        ];
    }

    public function uploadFile($path, $file){
        if(file_exists($path)){
            $file->saveAs($path.$file->baseName . '.' . $file->extension);
            return true;
        }
        else{
            mkdir($path, 0777, true);
            $file->saveAs($path.$file->baseName . '.' . $file->extension);
            return true;
        }
    }

    public function upload($ref_id, $type)
    {
        if ($this->validate()) {
            foreach ($this->fileupload as $file) {

                $dir = 'uploads/'.$type.'/' . $this->type;
                if(!is_dir($dir)){
                    mkdir($dir, 0777, true);
                }

                $baseName = str_replace(' ', '-', strtolower(UtilityService::utf8convert(trim($file->baseName))));

                $model = new Upload();
                $model->file_name = $baseName;
                $model->file_path = $dir.  '/' . $baseName . '.' . $file->extension;
                $model->loai = $type;
                $model->save();
                $file->saveAs($model->file_path);
            }
            return true;
        } else {
            return false;
        }
    }
}