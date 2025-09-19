<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\VuViec;
use yii\db\Expression;

/**
 * VuViecSearch represents the model behind the search form about `app\modules\quanly\models\VuViec`.
 */
class VuViecSearch extends VuViec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nguoi_dan_id', 'linh_vuc_id', 'don_vi_tiep_nhan_id', 'can_bo_tiep_nhan_id', 'trang_thai_hien_tai_id', 'so_nguoi_anh_huong', 'objectid_khupho', 'vu_viec_goc_id', 'status'], 'integer'],
            [['ma_vu_viec', 'tom_tat_noi_dung', 'mo_ta_chi_tiet', 'ngay_tiep_nhan', 'han_xu_ly', 'vi_tri_su_viec', 'dia_chi_su_viec', 'muc_do_canh_bao', 'created_at', 'updated_at', 'ma_dvhc_phuongxa', 'lat', 'long'], 'safe'],
            [['is_lap_lai'], 'boolean'],
            [['diem_rui_ro', 'diem_cam_tinh'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = VuViec::find()->where(['status' => 1])->orderBy('created_at DESC');

        if(Yii::$app->user->identity->phuongxa != null && !Yii::$app->user->identity->is_admin){
            $query->andWhere(['ma_dvhc_phuongxa'=>Yii::$app->user->identity->phuongxa]);
        }

        if(Yii::$app->user->identity->canbo_id != null && !Yii::$app->user->identity->is_admin){
            $query->andWhere(['can_bo_tiep_nhan_id'=>Yii::$app->user->identity->canbo_id]);
        }

        if(Yii::$app->user->identity->is_nguoidan && Yii::$app->user->identity->nguoidan_id != null){
            $query->andWhere(['nguoi_dan_id'=>Yii::$app->user->identity->nguoidan_id, 'is_nguoidanthem' => 1]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ngay_tiep_nhan' => $this->ngay_tiep_nhan,
            'han_xu_ly' => $this->han_xu_ly,
            'nguoi_dan_id' => $this->nguoi_dan_id,
            'linh_vuc_id' => $this->linh_vuc_id,
            'don_vi_tiep_nhan_id' => $this->don_vi_tiep_nhan_id,
            'can_bo_tiep_nhan_id' => $this->can_bo_tiep_nhan_id,
            //'trang_thai_hien_tai_id' => $this->trang_thai_hien_tai_id,
            'so_nguoi_anh_huong' => $this->so_nguoi_anh_huong,
            'is_lap_lai' => $this->is_lap_lai,
            'diem_rui_ro' => $this->diem_rui_ro,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'objectid_khupho' => $this->objectid_khupho,
            'vu_viec_goc_id' => $this->vu_viec_goc_id,
            'diem_cam_tinh' => $this->diem_cam_tinh,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_vu_viec)', mb_strtoupper($this->ma_vu_viec)])
            ->andFilterWhere(['like', 'upper(tom_tat_noi_dung)', mb_strtoupper($this->tom_tat_noi_dung)])
            ->andFilterWhere(['like', 'upper(mo_ta_chi_tiet)', mb_strtoupper($this->mo_ta_chi_tiet)])
            ->andFilterWhere(['like', 'upper(vi_tri_su_viec)', mb_strtoupper($this->vi_tri_su_viec)])
            ->andFilterWhere(['like', 'upper(dia_chi_su_viec)', mb_strtoupper($this->dia_chi_su_viec)])
            ->andFilterWhere(['like', 'upper(muc_do_canh_bao)', mb_strtoupper($this->muc_do_canh_bao)])
            ->andFilterWhere(['like', 'upper(ma_dvhc_phuongxa)', mb_strtoupper($this->ma_dvhc_phuongxa)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)]);

        if($this->trang_thai_hien_tai_id != null){
            if($this->trang_thai_hien_tai_id == 4){
                $query->andWhere(['<', 'han_xu_ly', new Expression('NOW()')]);
                $query->andWhere(['<>', 'trang_thai_hien_tai_id', 4]);
            }else{
                $query->andFilterWhere(['trang_thai_hien_tai_id' => $this->trang_thai_hien_tai_id,]);
            }
        }

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ma_vu_viec',
        'tom_tat_noi_dung',
        'mo_ta_chi_tiet',
        'ngay_tiep_nhan',
        'han_xu_ly',
        'vi_tri_su_viec',
        'dia_chi_su_viec',
        'nguoi_dan_id',
        'linh_vuc_id',
        'don_vi_tiep_nhan_id',
        'can_bo_tiep_nhan_id',
        'trang_thai_hien_tai_id',
        'so_nguoi_anh_huong',
        'is_lap_lai',
        'diem_rui_ro',
        'muc_do_canh_bao',
        'created_at',
        'updated_at',
        'ma_dvhc_phuongxa',
        'objectid_khupho',
        'vu_viec_goc_id',
        'diem_cam_tinh',
        'lat',
        'long',
        'status',        ];
    }
}
