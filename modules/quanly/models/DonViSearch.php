<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\DonVi;

/**
 * DonViSearch represents the model behind the search form about `app\modules\quanly\models\DonVi`.
 */
class DonViSearch extends DonVi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['ten_don_vi', 'loai_don_vi', 'created_at', 'updated_ at'], 'safe'],
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
        $query = DonVi::find()->where(['status' => 1]);

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
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'upper(ten_don_vi)', mb_strtoupper($this->ten_don_vi)])
            ->andFilterWhere(['like', 'upper(loai_don_vi)', mb_strtoupper($this->loai_don_vi)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ten_don_vi',
        'parent_id',
        'loai_don_vi',
        'status',
        'created_at',
        'updated_ at',
        'created_by',
        'updated_by',        ];
    }
}
