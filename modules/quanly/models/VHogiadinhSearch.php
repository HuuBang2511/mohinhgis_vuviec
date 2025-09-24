<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\VHogiadinh;

/**
 * VHogiadinhSearch represents the model behind the search form about `app\modules\quanly\models\VHogiadinh`.
 */
class VHogiadinhSearch extends VHogiadinh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nocgia_id', 'loaicutru_id'], 'integer'],
            [['ma_hsct', 'diachi_nocgia'], 'safe'],
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
        $query = VHogiadinh::find();

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
        ]);

        $query->andFilterWhere(['like', 'upper(ma_hsct)', mb_strtoupper($this->ma_hsct)])
            ->andFilterWhere(['like', 'upper(diachi_nocgia)', mb_strtoupper($this->diachi_nocgia)]);

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
        'diachi_nocgia',        ];
    }
}
