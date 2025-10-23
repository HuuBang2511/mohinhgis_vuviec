<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\CosokinhdoanhCodk;

/**
 * CosokinhdoanhCodkSearch represents the model behind the search form about `app\modules\quanly\models\CosokinhdoanhCodk`.
 */
class CosokinhdoanhCodkSearch extends CosokinhdoanhCodk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ten_co_so', 'loai_hinh_kinh_doanh', 'chu_so_huu', 'so_dien_thoai', 'giay_phep_so', 'ngay_cap', 'phuong_xa', 'quan_huyen', 'trang_thai_hoat_dong', 'geom', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['lat', 'long'], 'number'],
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
        $query = CosokinhdoanhCodk::find()->where(['status' => 1]);

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
            'ngay_cap' => $this->ngay_cap,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ten_co_so)', mb_strtoupper($this->ten_co_so)])
            ->andFilterWhere(['like', 'upper(loai_hinh_kinh_doanh)', mb_strtoupper($this->loai_hinh_kinh_doanh)])
            ->andFilterWhere(['like', 'upper(chu_so_huu)', mb_strtoupper($this->chu_so_huu)])
            ->andFilterWhere(['like', 'upper(so_dien_thoai)', mb_strtoupper($this->so_dien_thoai)])
            ->andFilterWhere(['like', 'upper(giay_phep_so)', mb_strtoupper($this->giay_phep_so)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(trang_thai_hoat_dong)', mb_strtoupper($this->trang_thai_hoat_dong)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(created_by)', mb_strtoupper($this->created_by)])
            ->andFilterWhere(['like', 'upper(updated_by)', mb_strtoupper($this->updated_by)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ten_co_so',
        'loai_hinh_kinh_doanh',
        'chu_so_huu',
        'so_dien_thoai',
        'giay_phep_so',
        'ngay_cap',
        'phuong_xa',
        'quan_huyen',
        'trang_thai_hoat_dong',
        'lat',
        'long',
        'geom',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',        ];
    }
}
