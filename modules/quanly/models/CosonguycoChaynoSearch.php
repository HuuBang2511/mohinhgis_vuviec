<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\CosonguycoChayno;

/**
 * CosonguycoChaynoSearch represents the model behind the search form about `app\modules\quanly\models\CosonguycoChayno`.
 */
class CosonguycoChaynoSearch extends CosonguycoChayno
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ten_co_so', 'loai_hinh', 'muc_do_nguy_co', 'phuong_xa', 'quan_huyen', 'don_vi_quan_ly', 'geom', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = CosonguycoChayno::find()->where(['status' => 1]);

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
            'lat' => $this->lat,
            'long' => $this->long,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'upper(ten_co_so)', mb_strtoupper($this->ten_co_so)])
            ->andFilterWhere(['like', 'upper(loai_hinh)', mb_strtoupper($this->loai_hinh)])
            ->andFilterWhere(['like', 'upper(muc_do_nguy_co)', mb_strtoupper($this->muc_do_nguy_co)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(don_vi_quan_ly)', mb_strtoupper($this->don_vi_quan_ly)])
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
        'loai_hinh',
        'muc_do_nguy_co',
        'phuong_xa',
        'quan_huyen',
        'don_vi_quan_ly',
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
