<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\DiemNhayCam;

/**
 * DiemNhayCamSearch represents the model behind the search form about `app\modules\quanly\models\DiemNhayCam`.
 */
class DiemNhayCamSearch extends DiemNhayCam
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vuviec_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['tenloaihinh', 'thongtin', 'ghichu', 'geom', 'lat', 'long', 'created_at', 'updated_at'], 'safe'],
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
        $query = DiemNhayCam::find()->where(['status' => 1]);

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
            'vuviec_id' => $this->vuviec_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'upper(tenloaihinh)', mb_strtoupper($this->tenloaihinh)])
            ->andFilterWhere(['like', 'upper(thongtin)', mb_strtoupper($this->thongtin)])
            ->andFilterWhere(['like', 'upper(ghichu)', mb_strtoupper($this->ghichu)])
            ->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(lat)', mb_strtoupper($this->lat)])
            ->andFilterWhere(['like', 'upper(long)', mb_strtoupper($this->long)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'tenloaihinh',
        'thongtin',
        'ghichu',
        'geom',
        'lat',
        'long',
        'vuviec_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',        ];
    }
}
