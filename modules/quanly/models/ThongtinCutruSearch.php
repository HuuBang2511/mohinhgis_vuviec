<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\ThongtinCutru;

/**
 * ThongtinCutruSearch represents the model behind the search form about `app\modules\quanly\models\ThongtinCutru`.
 */
class ThongtinCutruSearch extends ThongtinCutru
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loaicutru_id', 'nguoidan_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['ngaybatdau', 'ngayketthuc', 'diachi_thuongtru', 'diachi_cutru', 'diachi_tamtru', 'created_at', 'updated_at'], 'safe'],
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
        $query = ThongtinCutru::find()->where(['status' => 1]);

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
            'ngaybatdau' => $this->ngaybatdau,
            'ngayketthuc' => $this->ngayketthuc,
            'loaicutru_id' => $this->loaicutru_id,
            'nguoidan_id' => $this->nguoidan_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'upper(diachi_thuongtru)', mb_strtoupper($this->diachi_thuongtru)])
            ->andFilterWhere(['like', 'upper(diachi_cutru)', mb_strtoupper($this->diachi_cutru)])
            ->andFilterWhere(['like', 'upper(diachi_tamtru)', mb_strtoupper($this->diachi_tamtru)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ngaybatdau',
        'ngayketthuc',
        'loaicutru_id',
        'nguoidan_id',
        'diachi_thuongtru',
        'diachi_cutru',
        'diachi_tamtru',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',        ];
    }
}
