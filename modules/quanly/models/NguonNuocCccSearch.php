<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\NguonNuocCcc;

/**
 * NguonNuocCccSearch represents the model behind the search form about `app\modules\quanly\models\NguonNuocCcc`.
 */
class NguonNuocCccSearch extends NguonNuocCcc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ten_nguon', 'loai_nguon', 'tinh_trang', 'phuong_xa', 'quan_huyen', 'geom', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['dung_tich_m3', 'lat', 'long'], 'number'],
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
        $query = NguonNuocCcc::find()->where(['status' => 1]);

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
            'dung_tich_m3' => $this->dung_tich_m3,
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ten_nguon)', mb_strtoupper($this->ten_nguon)])
            ->andFilterWhere(['like', 'upper(loai_nguon)', mb_strtoupper($this->loai_nguon)])
            ->andFilterWhere(['like', 'upper(tinh_trang)', mb_strtoupper($this->tinh_trang)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
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
        'ten_nguon',
        'loai_nguon',
        'dung_tich_m3',
        'tinh_trang',
        'phuong_xa',
        'quan_huyen',
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
