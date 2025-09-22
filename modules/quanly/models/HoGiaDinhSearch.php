<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\HoGiaDinh;

/**
 * HoGiaDinhSearch represents the model behind the search form about `app\modules\quanly\models\HoGiaDinh`.
 */
class HoGiaDinhSearch extends HoGiaDinh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nocgia_id', 'loaicutru_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['ma_hsct', 'created_at', 'updated_at'], 'safe'],
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
        $query = HoGiaDinh::find();

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
            'nocgia_id' => $this->nocgia_id,
            'loaicutru_id' => $this->loaicutru_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(ma_hsct)', mb_strtoupper($this->ma_hsct)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ma_hsct',
        'nocgia_id',
        'loaicutru_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',        ];
    }
}
