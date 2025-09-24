<?php

namespace app\modules\quanly\controllers;

use Yii;
use app\modules\quanly\models\HoGiaDinh;
use app\modules\quanly\models\HoGiaDinhSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use \app\modules\quanly\base\QuanlyBaseModel;
use app\modules\services\CategoriesService;
use app\modules\quanly\models\NocGia;
use app\modules\services\UtilityService;
use app\modules\quanly\models\NguoiDan;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\modules\quanly\models\VHogiadinh;
use app\modules\quanly\models\VHogiadinhSearch;
/**
 * HoGiaDinhController implements the CRUD actions for HoGiaDinh model.
 */
class HoGiaDinhController extends \app\modules\quanly\base\QuanlyBaseController
{

    public $title = "Hộ gia đình";

    public $const;

    public function init()
    {
        parent::init();
        $this->const = [
            'title' => 'Hộ gia đình',
            'label' => [
                'index' => 'Danh sách',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
            ],
            'url' => [
                'index' => 'index',
                'create' => 'Thêm mới',
                'update' => 'Cập nhật',
                'view' => 'Thông tin chi tiết',
                'statistic' => 'Thống kê',
            ],
        ];
    }

    /**
     * Lists all HoGiaDinh models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VHogiadinhSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single HoGiaDinh model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $congdans = NguoiDan::find()->where(['hogiadinh_id' => $id, 'status' => 1])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'congdans' => $congdans
        ]);
    }

    /**
     * Creates a new HoGiaDinh model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;
        $nocgia = NocGia::findOne($id);
        if ($nocgia != null) {
            $hogiadinh = new HoGiaDinh(['nocgia_id' => $nocgia->id]);
        } else {
            $hogiadinh = new HoGiaDinh();
        }

        $diachiNocgia = (new Query())->select('id, text')
            ->from('v_nocgia_timkiem')
            ->where(['id' => $id])
            ->one();

        $thanhviens = [new NguoiDan()];

        if ($request->isPost && $hogiadinh->load($request->post())) {
            $thanhviens = QuanlyBaseModel::createMultiple(NguoiDan::classname());
            QuanlyBaseModel::loadMultiple($thanhviens, $request->post());

            $valid = $hogiadinh->validate();
            $valid = QuanlyBaseModel::validateMultiple($thanhviens) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $hogiadinh->save(false)) {

                        foreach ($thanhviens as $thanhvien) {
                            $thanhvien->hogiadinh_id = $hogiadinh->id;
                            if (!($flag = $thanhvien->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        //dd($hogiadinh);
                        if ($id != null) {
                            return $this->redirect(['noc-gia/view', 'id' => $id]);
                        }
                        return $this->redirect(['view', 'id' => $hogiadinh->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    dd($e);
                }
            }

            return $this->redirect(['hogiadinh/view', 'id' => $hogiadinh->id]);
        }

        return $this->render('create', [
            'hogiadinh' => $hogiadinh,
            'nocgia' => $nocgia,
            //'chuho' => $chuho,
            'categories' => CategoriesService::getCategoriesCongdan(),
            'thanhviens' => (empty($thanhviens)) ? [new CongDan()] : $thanhviens,
            'diachiNocgia' => $diachiNocgia,
        ]);

    }

    /**
     * Updates an existing HoGiaDinh model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $hogiadinh = $this->findModel($id);
        $nocgia = $hogiadinh->nocgia;

        $diachiNocgia = (new Query())->select('id, text')
            ->from('v_nocgia_timkiem')
            ->where(['id' => $nocgia->id])
            ->one();

        $thanhviens = NguoiDan::find()
            ->where(['hogiadinh_id' => $hogiadinh->id, 'status' => 1])
            ->andWhere(['or',
                ['<>', 'quanhechuho_id', 1],
                ['quanhechuho_id' => null]
            ])->orderBy('quanhechuho_id')->all();

        if ($request->isPost && $hogiadinh->load($request->post())) {

            $oldIDs = ArrayHelper::map($thanhviens, 'id', 'id');
            $thanhviens = QuanlyBaseModel::updateMultiple(NguoiDan::classname());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($thanhviens, 'id', 'id')));
            QuanlyBaseModel::loadMultiple($thanhviens, Yii::$app->request->post());

            $valid = $hogiadinh->validate();

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $hogiadinh->save(false)) {

                        //xử lý xóa công dân đã xóa trong dynamic form
                        if (sizeof($deletedIDs) > 0) {
                            foreach ($deletedIDs as $i => $item) {
                                Yii::$app->db->createCommand("UPDATE nguoi_dan SET status = 0 WHERE id = :id")
                                    ->bindValue(':id', $item)
                                    ->execute();
                            }
                            //CongDan::deleteAll(['id' => $deletedIDs]);
                        }

                        //thêm mới hoặc cập nhật thông tin
                        foreach ($thanhviens as $thanhvien) {
                            $thanhvien->hogiadinh_id = $hogiadinh->id;
                            if (!($flag = $thanhvien->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        if ($id != null) {
                            return $this->redirect(['view', 'id' => $id]);
                        }
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
            return $this->redirect(['update', 'id' => $id]);
        }

        return $this->render('update', [
            'hogiadinh' => $hogiadinh,
            'categories' => CategoriesService::getCategoriesCongdan(),
            'thanhviens' => (empty($thanhviens)) ? [new CongDan()] : $thanhviens,
            'diachiNocgia' => $diachiNocgia,
        ]);
    }

    /**
     * Delete an existing HoGiaDinh model.
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

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Xóa HoGiaDinh #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Xóa',['class'=>'btn btn-danger float-left','type'=>"submit"])
                ];
            }else if($request->isPost && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "HoGiaDinh #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Update HoGiaDinh #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('delete', [
                    'model' => $model,
                    'const' => $this->const,
                ]);
            }
        }
    }

    
    /**
     * Finds the HoGiaDinh model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HoGiaDinh the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HoGiaDinh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
