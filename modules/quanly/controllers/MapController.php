<?php

namespace app\modules\quanly\controllers;


use app\modules\quanly\models\VuViec;
use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\Response;

class MapController extends \app\modules\quanly\base\QuanlyBaseController
{
    public $layout = '@app/views/layouts/map/main';

    public function actionVuviec()
    {   
        return $this->render('vuviec');
    }
    
}
