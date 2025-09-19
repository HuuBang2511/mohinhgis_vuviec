<?php

namespace app\modules\auth\controllers;

use app\modules\quanly\base\QuanlyBaseController;
use hcmgis\user\models\AuthChangePass;
use hcmgis\user\models\AuthUser;
use hcmgis\user\services\AuthService;
use hcmgis\user\services\AuthUserService;
use hcmgis\user\models\AuthAssignment;
use Yii;
use yii\helpers\ArrayHelper;
use hcmgis\user\models\AuthGroup;

use app\modules\auth\models\User;
use app\modules\auth\models\UserSearch;
use app\modules\auth\services\UserService;
use app\modules\auth\models\UserNguoidan;
use hcmgis\user\Constant;
use yii\web\Controller;


use hcmgis\user\models\AuthUpdatePass;
use hcmgis\user\models\AuthUserSearch;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use app\modules\quanly\models\NguoiDan;
use app\modules\services\UtilityService;
use app\modules\services\CategoriesService;
use yii\db\Query;
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'lock' => ['POST'],
                    'unlock' => ['POST'],
                ],
            ],
        ];
    }

    public $title = 'Tài khoản người dùng';
    //public $layout = '@app/modules/layouts/phuong/main';

    public function actionIndex(){
        $searchModel = new UserSearch();
        $queryParams =  Yii::$app->request->queryParams;
        $queryParams['UserSearch']['status'] = 1;
        $dataProvider = $searchModel->search($queryParams);

        //dd($dataProvider);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = UserService::View($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionThongTinTaiKhoan()
    {
        //$this->layout = "@app/modules/layouts/main";
        $this->title = 'Thông tin tài khoản';
        $request = Yii::$app->request;
        $model = AuthUser::find()->where(['id' => Yii::$app->user->id])->one();
        // dd($userGroups);
        /*
         *   Process for non-ajax request
         */
        if ($request->isPost) {
            $message = AuthUserService::Update($model, $request->post());
            if ($message === true) {
                return $this->redirect(['thong-tin-tai-khoan']);
            } else {
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['thong-tin-tai-khoan']);
            }
        }

        return $this->render('thong-tin-tai-khoan', [
            'model' => $model,
        ]);
    }

    public function actionDoiMatKhau()
    {
        $this->layout = "@app/modules/layouts/main";
        $this->title = 'Đổi mật khẩu';
        $request = Yii::$app->request;
        $model = AuthUser::find()->where(['id' => Yii::$app->user->id])->one();
        $authChangePass = new AuthChangePass();
        if ($request->isPost && $authChangePass->load($request->post())) {
            if ($authChangePass->changePassword() == TRUE) {
                Yii::$app->session->setFlash('success', "Đã cập nhật mật khẩu");
                return $this->redirect(['doi-mat-khau']);
            } else {
                Yii::$app->session->setFlash('error', "Cập nhật thất bại!");
            }
        }
        return $this->render('doi-mat-khau', compact('model', 'authChangePass'));
    }

    public function actionTaoTaiKhoanNguoiDan(){

        $this->layout = '@app/modules/layouts/main_taotaikhoan';

        $request = Yii::$app->request;
        $model = new UserNguoidan();

        $groups = AuthGroup::find()->where(['status' => Constant::STATUS_ACTIVE])->orderBy('name')->asArray()->all();
        $userGroups = [];


        if ($request->isPost) {
            if ($request->isPost) {
                $model->is_nguoidan = 1;
                $message = UserService::CreateNguoidan($model, $request->post());
                if ($message === true) {
                    if ($request->post('roles') != NULL) {
                        AuthService::assign($model->id, [10]);
                    }
                    return $this->redirect(['xac-thuc-tai-khoan-nguoi-dan', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', $message);
                    return $this->redirect(['tao-tai-khoan-nguoi-dan']);
                }
            }
        }
        return $this->render('tao-tai-khoan-nguoi-dan', [
            'model' => $model,
            'groups' => $groups,
            'userGroups' => $userGroups,
            'categories' => CategoriesService::getCategoriesUser(),
        ]);
    }

    public function actionXacThucTaiKhoanNguoiDan($id){

        $this->layout = '@app/modules/layouts/main_taotaikhoan';


        $request = Yii::$app->request;
        $model = User::findOne($id);
        $model->maxacthuc = '';
        $model->active = 0;
        //$groups = AuthService::getAllGroups();
        $userGroups = ArrayHelper::getColumn(AuthService::getGroupsByUserId($id), 'id');

        $groups = AuthGroup::find()->where(['status' => Constant::STATUS_ACTIVE])->orderBy('name')->asArray()->all();

        //dd($model);
        
        if ($request->isPost) {
            $model->active = 1;
            $message = UserService::Update($model, $request->post());
            //dd($model);
            
            if ($message === true && $model->maxacthuc == '111111') {
                $nguoidan = new NguoiDan();
                $nguoidan->ho_ten = $model->fullname;
                $nguoidan->so_dien_thoai = $model->sodienthoai;
                $nguoidan->dia_chi = $model->diachi;
                $nguoidan->email = $model->email;
                $nguoidan->save();
                $model->nguoidan_id = $nguoidan->id;
                $model->save();

                AuthService::assign($model->id, [10]);
                return $this->redirect(['/quanly/dashboard/index']);
            } else {
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['xac-thuc-tai-khoan-nguoi-dan', 'id' => $model->id]);
            }
            
            
        }

        return $this->render('xac-thuc-tai-khoan-nguoi-dan', [
            'model' => $model,
            'groups' => $groups,
            'userGroups' => $userGroups,
            'categories' => CategoriesService::getCategoriesUser(),
        ]);
    }


    public function actionTaoTaiKhoan(){
        $request = Yii::$app->request;
        $model = new User();

        //$groups = AuthService::getAllGroups();
        $groups = AuthGroup::find()->where(['status' => Constant::STATUS_ACTIVE])->orderBy('name')->asArray()->all();
        $userGroups = [];

        /*
         *   Process for non-ajax request
         */
        if ($request->isPost) {
            //dd($request->post());
            if($model->nguoidan_id != null){
                $model->is_nguoidan = 1;
            }else{
                $model->is_nguoidan = 0;
            }
            $message = UserService::Create($model, $request->post());
            //dd($message);
            if ($message === true) {
                if ($request->post('roles') != NULL) {
                    AuthService::assign($model->id, $request->post('roles'));
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['tao-tai-khoan']);
            }
        }
        return $this->render('tao-tai-khoan', [
            'model' => $model,
            'groups' => $groups,
            'userGroups' => $userGroups,
            'categories' => CategoriesService::getCategoriesVuViec(),
        ]);
    }

    public function actionCapNhatTaiKhoan($id){
        $request = Yii::$app->request;
        $model = User::findOne($id);
        //$groups = AuthService::getAllGroups();
        $userGroups = ArrayHelper::getColumn(AuthService::getGroupsByUserId($id), 'id');

        if($model->nguoidan_id != null){
            $nguoidan = (new Query())->select('id, text')
            ->from('v_nguoidan')
            ->where(['id' => $model->nguoidan_id])
            ->one();
        }else{
            $nguoidan = null;
        }

        $groups = AuthGroup::find()->where(['status' => Constant::STATUS_ACTIVE])->orderBy('name')->asArray()->all();
        
        if ($request->isPost) {
            //dd($model);
            if($model->nguoidan_id != null){
                $model->is_nguoidan = 1;
            }else{
                $model->is_nguoidan = 0;
            }
            $message = UserService::Update($model, $request->post());
            if ($message === true) {
                //dd($request->post('roles', []));
                AuthService::assign($model->id, $request->post('roles', []));
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['cap-nhat-tai-khoan', 'id' => $model->id]);
            }
        }

        return $this->render('cap-nhat-tai-khoan', [
            'model' => $model,
            'groups' => $groups,
            'userGroups' => $userGroups,
            'categories' => CategoriesService::getCategoriesVuViec(),
            'nguoidan' => $nguoidan,
        ]);
    }

    public function actionDelete($id)
    {
        $model = User::findOne($id);
        if (AuthUserService::Delete($model)) {
            Yii::$app->session->setFlash('success', 'Xóa thành công');
        }
        return $this->redirect(['index']);
    }



    public function actionLock($id)
    {
        $model = User::findOne($id);

        $model->active = 0;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Khóa tài khoản thành công');
        } else {
            Yii::$app->session->setFlash('error', 'Khóa tài khoản thất bại');
        }

        return $this->redirect(['index']);
    }

    public function actionUnlock($id)
    {
        $model = User::findOne($id);

        $model->active = 1;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Mở khóa tài khoản thành công');
        } else {
            dd($model->errors);
            Yii::$app->session->setFlash('error', 'Mở khóa tài khoản thất bại');
        }

        return $this->redirect(['index']);
    }


    /**
     * Updates password.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangePass()
    {
        $request = Yii::$app->request;
        $model = AuthUser::find()->where(['id' => Yii::$app->user->id])->one();
        $authChangePass = new AuthChangePass();
        if ($request->isPost && $authChangePass->load($request->post())) {
            if ($authChangePass->changePassword() == TRUE) {
                Yii::$app->session->setFlash('success', "Đã cập nhật mật khẩu");
                return $this->redirect(['change-pass']);
            } else {
                Yii::$app->session->setFlash('error', "Cập nhật thất bại!");
            }
        }
        return $this->render('change-pass', compact('model', 'authChangePass'));
    }
    /**
     * Updates password.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatePass($id)
    {
        $request = Yii::$app->request;
        $model['tai-khoan'] = AuthUser::find()->where(['id' => $id])->one();
        $model['doimatkhau'] = new AuthUpdatePass();
        if ($request->isPost && $model['doimatkhau']->load($request->post())) {
            if ($model['doimatkhau']->updatePassword($model['tai-khoan']) == TRUE) {
                Yii::$app->session->setFlash('success', "Đã cập nhật mật khẩu");
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', "Cập nhật thất bại!");
                return $this->redirect(['update-pass', 'id' => $id]);
            }
        }
        return $this->render('update-pass', [
            'model' => $model
        ]);
    }

    public function actionGetLophoc(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $truonghoc_id = $parents[0];
                //$khupho = Khupho::find()->where(['phuongxa_id' => $phuong_id])->one();
                $out = Lophoc::find()->select('id, ten as name')
                    ->where(['truonghoc_id' => $truonghoc_id,'status' => 1])
                    ->orderBy('ten')->asArray()->all();
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetPhuongxa(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $quanhuyen_id = $parents[0];
                //$khupho = Khupho::find()->where(['phuongxa_id' => $phuong_id])->one();
                $out = HcPhuongxa::find()->select('id, ten as name')
                    ->where(['quanhuyen_id' => $quanhuyen_id])
                    ->orderBy('ten')->asArray()->all();
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}