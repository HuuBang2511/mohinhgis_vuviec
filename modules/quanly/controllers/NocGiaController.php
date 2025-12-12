<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\NocGia;
use app\modules\quanly\models\NocGiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\services\CategoriesService;
use app\modules\quanly\models\Hogiadinh;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use app\modules\quanly\models\ImportUpload;
use app\modules\quanly\models\FormImport;
use app\modules\quanly\models\danhmuc\DmGioitinh;
use app\modules\quanly\models\danhmuc\DmQuanhechuho;
use app\modules\quanly\models\danhmuc\DmLoaicutru;
use app\modules\quanly\models\Phuongxa;
use app\modules\quanly\models\Kp;
use app\modules\quanly\models\Nguoidan;
use app\modules\services\UtilityService;



/**
 * NocGiaController implements the CRUD actions for NocGia model.
 */
class NocGiaController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Nóc gia";

    public function actionImportTable(){
        $request = Yii::$app->request;
        $fileUpload = new FormImport();
        $errorRow = null;
        $notification = null;
        

        $nocgia = new Nocgia();
        $attributes = $nocgia->attributeLabels();

        $phuongxa = Phuongxa::find()->select(['id', 'tenXa', 'maXa'])->indexBy('tenXa')->asArray()->all();
        $khupho = Kp::find()->select(['id', 'TenKhuPho'])->indexBy('TenKhuPho')->asArray()->all();

        if($fileUpload->load($request->post())){
           
            $highestRow = 0;
            $newRecords = 0;
            $updateRecords = 0;

            $errors = false; 
        
            $notification = null;
            $notification = [];
            $fileUpload->file = UploadedFile::getInstance($fileUpload, 'file');

            $className = '\app\modules\quanly\models\\'.$fileUpload->table.'';
            
            $model = new $className();  
            $attributes = $model->attributeLabels();
           //dd($attributes);
            
            if($fileUpload->uploadFile()){
                $spreadsheet = IOFactory::load($fileUpload->link);
                $worksheet = $spreadsheet->getSheet(0);
                $csvPath = 'uploads/files/import/csv/' . $fileUpload->file->baseName . '.csv'; 
                
                $transaction = Yii::$app->db->beginTransaction();
                try{

                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                    if($worksheet->getHighestRow() < 2){
                        Yii::$app->session->setFlash('noData', "Không có dữ liệu!");
                        return $this->render('import-table', [
                            'fileUpload' => $fileUpload,
                            'notification' => $notification,
                        ]);
                    }
                    else{
                        
                        for($col = 2; $col <= $highestColumnIndex; $col++){
                            if(!in_array($worksheet->getCellByColumnAndRow($col, 1)->getValue(), $attributes)){
                                dd($worksheet->getCellByColumnAndRow($col, 1)->getValue());
                                Yii::$app->session->setFlash('noData', "Dữ liệu trường dữ liệu không đúng");
                                return $this->render('import-table', [
                                        'fileUpload' => $fileUpload,
                                        'notification' => $notification,
                                ]);
                            }
                        }

                        for($row = 2; $row <= $highestRow; $row++){
                            for($col = 2; $col <= $highestColumnIndex; $col++){

                                $data[$row][$fileUpload->table][array_search($worksheet->getCellByColumnAndRow($col, 1)->getValue(),$attributes)] = 
                                $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            }

                            $dataInput =  $data[$row][$fileUpload->table];

                            $dateAttributes = ArrayHelper::index($model->getTableSchema()->columns, 'name', 'type');

                            //dd( $data[$row]);

                            if(isset($dateAttributes['date'])) {
                                foreach ($dateAttributes['date'] as $dateAttribute) {
                                    if(array_key_exists($dateAttribute->name, $dataInput)){
                                        if($dataInput[$dateAttribute->name] != null){
                                            //dd($dataInput[$dateAttribute->name]);
                                            $data[$row][$fileUpload->table][$dateAttribute->name] = UtilityService::convertDateFromMaskedInput($data[$row][$fileUpload->table][$dateAttribute->name]);
                                        }
                                    }
                                }
                            }

                            //dd($data[$row][$fileUpload->table]);

                            if(isset($dateAttributes['decimal'])) {
                                foreach ($dateAttributes['decimal'] as $i => $dateAttribute) {
                                    //dd($dateAttribute->name);
                                    if(array_key_exists($dateAttribute->name, $dataInput)){
                                        if($dataInput[$dateAttribute->name] != null){
                                            if(!is_numeric($dataInput[$dateAttribute->name])){
                                                $notification[$row]['style'] = 'text-danger';
                                                $notification[$row]['data'] = 'Dữ liệu '.$attributes[$dataInput[$dateAttribute->name]].' phải là số dòng: '. $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                                $errors = true;
                                                continue;
                                            }
                                        }
                                    }
                                }
                            }

                            if(isset($dateAttributes['integer'])) {
                                foreach ($dateAttributes['integer'] as $i => $dateAttribute) {
                                    //dd($dateAttribute->name);
                                    if(array_key_exists($dateAttribute->name, $dataInput)){
                                        if($dataInput[$dateAttribute->name] != null){
                                            if(!is_int($dataInput[$dateAttribute->name])){
                                                $notification[$row]['style'] = 'text-danger';
                                                $notification[$row]['data'] = 'Dữ liệu '.$attributes[$dataInput[$dateAttribute->name]].' phải là số dòng: '. $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                                $errors = true;
                                                continue;
                                            }
                                        }
                                    }
                                }
                            }

                             if(isset($dateAttributes['bigint'])) {
                                foreach ($dateAttributes['bigint'] as $i => $dateAttribute) {
                                    //dd($dateAttribute->name);
                                    if(array_key_exists($dateAttribute->name, $dataInput)){
                                        if($dataInput[$dateAttribute->name] != null){
                                            if(!is_int($dataInput[$dateAttribute->name])){
                                                $notification[$row]['style'] = 'text-danger';
                                                $notification[$row]['data'] = 'Dữ liệu '.$attributes[$dataInput[$dateAttribute->name]].' phải là số dòng: '. $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                                $errors = true;
                                                continue;
                                            }
                                        }
                                    }
                                }
                            }

                            $import = new $className();  
                            $import->load($data[$row]);

                            //dd($model::tableName());

                            if (!$import->save(false)) {
                                $notification[$lineNumber] = [
                                    'style' => 'text-danger',
                                    'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                ];
                                $errors = true; // Đánh dấu có lỗi
                                continue;
                            }else{
                                $import->save();

                                if(isset($import->lat) && isset($import->long)){
                                    
                                    if($import->lat != null && $import->long != null){
                                        if($fileUpload->table == 'VuViec'){
                                            $tableName = $import::tableName();
                                            \Yii::$app->db->createCommand("UPDATE $tableName SET vi_tri_su_viec = ST_SetSRID(ST_MakePoint($import->long,$import->lat),4326) WHERE id=:id")
                                            ->bindValue(':id', $import->id)
                                            ->execute();
                                        }else{
                                            $tableName = $import::tableName();
                                            \Yii::$app->db->createCommand("UPDATE $tableName SET geom = ST_SetSRID(ST_MakePoint($import->long,$import->lat),4326) WHERE id=:id")
                                            ->bindValue(':id', $import->id)
                                            ->execute();
                                        }
                                        
                                    }
                                }

                                $newRecords += 1;
                            }
                        }
                    }

                    if ($errors) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('uploadFail', 'Có lỗi trong quá trình import, không có dữ liệu nào được lưu.');
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('uploadSuccess', 'Import thành công '.$newRecords. ' dòng dữ liệu');
                    }
                }catch(Exception $e){
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('uploadFail', "Lỗi dòng $errorRow!");
                    return $this->render('import-table',[
                        'fileUpload' => $fileUpload,
                        'errorRow' => $errorRow,
                        'notification' => $notification,
                    ]);
                }
            }

            return $this->render('import-table', [
                'check' => null,
                'fileUpload' => $fileUpload,
                'notification' => $notification,
            ]);
        }

        return $this->render('import-table', [
            'check' => null,
            'fileUpload' => $fileUpload,
            'notification' => $notification,
        ]);
    }

    public function actionImport(){
        $request = Yii::$app->request;
        $fileUpload = new ImportUpload();
        $errorRow = null;
        $notification = null;

        $nocgia = new Nocgia();
        $attributes = $nocgia->attributeLabels();

        $phuongxa = Phuongxa::find()->select(['id', 'tenXa', 'maXa'])->indexBy('tenXa')->asArray()->all();
        $gioitinh = DmGioitinh::find()->select(['id', 'ten'])->where(['status' => 1])->indexBy('ten')->asArray()->all();
        $quanhechuho = DmQuanhechuho::find()->select(['id', 'ten'])->where(['status' => 1])->indexBy('ten')->asArray()->all();
        $loaicutru = DmLoaicutru::find()->select(['id', 'ten'])->where(['status' => 1])->indexBy('ten')->asArray()->all();
        $khupho = Kp::find()->select(['id', 'TenKhuPho'])->indexBy('TenKhuPho')->asArray()->all();


        //dd($gioitinh);

        if($request->isPost){
            $highestRow = 0;
            $newNocgiaRecords = 0;
            $updateNocgiaRecords = 0;
            $newHogiadinh = 0;
            $updateHogiadinh = 0;
            $newCongdan = 0;
            $updateCongdan = 0;
            $notification = null;
            $notification = [];
            $fileUpload->file = UploadedFile::getInstance($fileUpload, 'file');
            if($fileUpload->uploadFile()){
            
                $spreadsheet = IOFactory::load($fileUpload->link);
                $worksheet = $spreadsheet->getSheet(0);
                $csvPath = 'uploads/files/import/csv/' . $fileUpload->file->baseName . '.csv'; 

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($worksheet->getHighestRow() < 2) {
                        $notification[] = [
                            'style' => 'text-danger',
                            'data' => 'Không có dữ liệu!',
                        ];
                        Yii::$app->session->setFlash('noData', 'Không có dữ liệu!');
                        return $this->render('import', [
                            'fileUpload' => $fileUpload,
                            'notification' => $notification,
                        ]);
                    }

                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                    $writer = new Csv($spreadsheet);
                    $writer->setDelimiter(',');
                    $writer->setEnclosure('"');
                    $writer->setSheetIndex(0);
                    $writer->save($csvPath);

                    $file = new \SplFileObject($csvPath);
                    $file->setFlags(\SplFileObject::READ_CSV);
                    $file->setCsvControl(',');


                    $firstRow = true;
                    $errors = false; 

                    $lineNumber = 0;

                    $currentNocgiaid = null;
                    $currentHogiadinhid = null;

                    foreach ($file as $row) {

                        

                        $lineNumber++;
                    
                        if ($firstRow) {
                            $firstRow = false;
                            continue; // bỏ dòng đầu
                        }

                        if ($lineNumber === 2) {
                            continue; // bỏ dòng 2
                        }
                        

                        if ($row === [null]) continue; // bỏ dòng trống

                        if(trim($row[7]) == ''){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin stt công dân trong hộ gia đình: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[5]) == '' && trim($row[7]) == '1'){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin stt hộ gia đình dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[0]) == '' && trim($row[5]) == '1'){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin stt nóc gia dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[4]) == '' && trim($row[0]) != '' ){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin phường xã dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[3]) == '' && trim($row[0]) != '' ){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin khu phố xã dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[1]) == '' && trim($row[0]) != ''){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin số nhà dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[2]) == '' && trim($row[0]) != ''){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin tên đường dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[6]) == '' && trim($row[7]) == '1'){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin mã hồ sơ cư trú: '. $lineNumber;
                            $errors = true;
                            continue;
                        }

                        if(trim($row[10]) == ''){
                            $notification[$lineNumber]['style'] = 'text-danger';
                            $notification[$lineNumber]['data'] = 'Thiếu thông tin CCCD dòng: '. $lineNumber;
                            $errors = true;
                            continue;
                        }


                        if(trim($row[4]) != ''){
                            if(!(array_key_exists(trim($row[4]),$phuongxa))){
                                $notification[$lineNumber]['style'] = 'text-danger';
                                $notification[$lineNumber]['data'] = 'Sai tên phường xã dòng: '. $lineNumber;
                                $errors = true;
                                continue;
                            }   
                            else{
                                $row[4] = $phuongxa[trim($row[4])]['maXa'];
                            }
    
                        }

                        if(trim($row[3]) != ''){
                            if(!(array_key_exists(trim($row[3]),$khupho))){
                                $notification[$lineNumber]['style'] = 'text-danger';
                                $notification[$lineNumber]['data'] = 'Sai tên phường khu phố: '. $lineNumber;
                                $errors = true;
                                continue;
                            }   
                            else{
                                $row[3] = $khupho[trim($row[3])]['id'];
                            }
    
                        }

                        if(trim($row[12]) != ''){
                            if(!(array_key_exists(trim($row[12]),$gioitinh))){
                                $notification[$lineNumber]['style'] = 'text-danger';
                                $notification[$lineNumber]['data'] = 'Sai tên giới tính dòng: '. $lineNumber;
                                $errors = true;
                                continue;
                            }   
                            else{
                                $row[12] = $gioitinh[trim($row[12])]['id'];

                            }
    
                        }

                        if(trim($row[14]) != ''){
                            if(!(array_key_exists(trim($row[14]),$loaicutru))){
                                $notification[$lineNumber]['style'] = 'text-danger';
                                $notification[$lineNumber]['data'] = 'Sai tên loại cư trú dòng: '. $lineNumber;
                                $errors = true;
                                continue;
                            }   
                            else{
                                $row[14] = $loaicutru[trim($row[14])]['id'];

                            }
    
                        }

                        if(trim($row[15]) != ''){
                            if(!(array_key_exists(trim($row[15]),$quanhechuho))){
                                $notification[$lineNumber]['style'] = 'text-danger';
                                $notification[$lineNumber]['data'] = 'Sai tên quan hệ chủ hộ dòng: '. $lineNumber;
                                $errors = true;
                                continue;
                            }   
                            else{
                                $row[15] = $quanhechuho[trim($row[15])]['id'];

                            }
    
                        }

                        if(trim($row[2]) != '' && trim($row[1]) != '' && trim($row[4]) != ''){
                            $nocgia = NocGia::find()->where(['status' => 1])->andWhere(['phuongxa_id' => trim($row[4])])
                            ->andWhere(['khupho_id' => trim($row[3])])
                            ->andWhere(['so_nha' => trim($row[1])])->andWhere(['ten_duong' => trim($row[2])])
                            ->one();
                            if($nocgia != null){
                                $nocgia->phuongxa_id = trim($row[4]);
                                $nocgia->khupho_id = trim($row[3]);
                                $nocgia->so_nha = trim($row[1]);
                                $nocgia->ten_duong = trim($row[2]);
                            
                                if (!$nocgia->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $nocgia->save();
                                    if($row[5] != null && $row[5] == '1'){
                                        $currentNocgiaid = $nocgia->id;
                                    }
                                    $updateNocgiaRecords += 1;
                                }
                            }else{
                                $nocgia = new NocGia();
                                $nocgia->phuongxa_id = trim($row[4]);
                                $nocgia->khupho_id = trim($row[3]);
                                $nocgia->so_nha = trim($row[1]);
                                $nocgia->ten_duong = trim($row[2]);
                                

                                if (!$nocgia->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $nocgia->save();
                                    if($row[5] != null && $row[5] == '1'){
                                        $currentNocgiaid = $nocgia->id;
                                    }
                                    $newNocgiaRecords += 1;
                                }
                            }
                        }

                        if(trim($row[6]) != ''){
                            $hogiadinh = HoGiaDinh::find()->where(['ma_hsct' => trim($row[6]), 'status' => 1])->one();

                            if($hogiadinh != null){
                                $hogiadinh->ma_hsct = trim($row[6]);

                                if($currentNocgiaid != null){
                                    $hogiadinh->nocgia_id = $currentNocgiaid;
                                }

                                if (!$hogiadinh->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $hogiadinh->save();
                                    if($row[7] != null && $row[7] == '1'){
                                        $currentHogiadinhid = $hogiadinh->id;
                                    }
                                    $updateHogiadinh += 1;
                                }
                            }else{
                                $hogiadinh = new HoGiaDinh();

                                $hogiadinh->ma_hsct = trim($row[6]);

                                if($currentNocgiaid != null){
                                    $hogiadinh->nocgia_id = $currentNocgiaid;
                                }

                                if (!$hogiadinh->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $hogiadinh->save();
                                    if($row[7] != null && $row[7] == '1'){
                                        $currentHogiadinhid = $hogiadinh->id;
                                    }
                                    $newHogiadinh += 1;
                                }
                            }
                        }

                        if(trim($row[10]) != ''){
                            $congdan = Nguoidan::find()->where(['cccd' => trim($row[10]), 'status' => 1])->one();

                            if($congdan != null){
                                $congdan->ho_ten = trim($row[8]);
                                $congdan->ngaysinh = trim($row[9]);
                                $congdan->cccd = trim($row[10]);
                                $congdan->cccd_ngaycap = trim($row[11]);
                                $congdan->gioitinh_id = $row[12];
                                $congdan->so_dien_thoai = trim($row[13]);
                                $congdan->loaicutru_id = $row[14];
                                $congdan->quanhechuho_id = $row[15];
                                

                                if($currentHogiadinhid != null){
                                    $congdan->hogiadinh_id = $currentHogiadinhid;
                                }

                               

                                if (!$congdan->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $congdan->save();
                                    $updateCongdan += 1;
                                }
                            }else{
                                $congdan = new Nguoidan();

                                $congdan->ho_ten = trim($row[8]);
                                $congdan->ngaysinh = trim($row[9]);
                                $congdan->cccd = trim($row[10]);
                                $congdan->cccd_ngaycap = trim($row[11]);
                                $congdan->gioitinh_id = $row[12];
                                $congdan->so_dien_thoai = trim($row[13]);
                                $congdan->loaicutru_id = $row[14];
                                $congdan->quanhechuho_id = $row[15];

                                if($currentHogiadinhid != null){
                                    $congdan->hogiadinh_id = $currentHogiadinhid;
                                }

                                

                                if (!$congdan->save(false)) {
                                    $notification[$lineNumber] = [
                                        'style' => 'text-danger',
                                        'data' => 'Lỗi lưu dữ liệu tại dòng: ' . $lineNumber,
                                    ];
                                    $errors = true; // Đánh dấu có lỗi
                                    continue;
                                }else{
                                    $congdan->save();
                                    $newCongdan += 1;
                                }
                            }
                        }
                    }

                    if ($errors) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('uploadFail', 'Có lỗi trong quá trình import, không có dữ liệu nào được lưu.');
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('uploadSuccess', 'Import thành công thêm mới '.$newNocgiaRecords.' nóc gia, cập nhật '.$updateNocgiaRecords.' nóc gia, thêm mới '.$newHogiadinh.' hộ gia đình, cập nhật '.$updateHogiadinh.' hộ gia đình, thêm mới '.$newCongdan.' người dân, cập nhật '.$updateCongdan.' người dân');
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $notification[] = [
                        'style' => 'text-danger',
                        'data' => 'Lỗi hệ thống: ' . $e->getMessage(),
                    ];
                    Yii::$app->session->setFlash('uploadFail', 'Lỗi hệ thống khi import.');
                    return $this->render('import', [
                        'fileUpload' => $fileUpload,
                        'errorRow' => $lineNumber ?? null,
                        'notification' => $notification,
                    ]);
                }
            }
        }

        return $this->render('import', [
            'check' => null,
            'fileUpload' => $fileUpload,
            'notification' => $notification,
        ]);
    }

    /**
     * Lists all NocGia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NocGiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single NocGia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        $hogiadinhs = Hogiadinh::find()->where(['nocgia_id' => $id, 'status' => 1])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'hogiadinhs' => $hogiadinhs
        ]);
    }

    /**
     * Creates a new NocGia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    
        $request = Yii::$app->request;
        $model = new Nocgia();

        //dd(CategoriesService::getCategoriesNocgia());

        if($model->load($request->post())){
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'categories' => CategoriesService::getCategoriesNocgia(),
        ]);

    }

    /**
     * Updates an existing NocGia model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($model->load($request->post())){
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => CategoriesService::getCategoriesNocgia(),
        ]);
    }

    /**
     * Delete an existing NocGia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = 0;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Xóa #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Xóa', ['class' => 'btn btn-danger float-left', 'type' => "submit"])
                ];
            } else if ($request->isPost && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Xóa thành công #" . $id,
                    'content' => '<span class="text-success">Xóa thành công</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Update #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    
    /**
     * Finds the NocGia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NocGia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NocGia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}