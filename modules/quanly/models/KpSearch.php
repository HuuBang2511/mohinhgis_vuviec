<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\Kp;

/**
 * KpSearch represents the model behind the search form about `app\modules\quanly\models\Kp`.
 */
class KpSearch extends Kp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['geom', 'TenPhuong', 'TenQuan', 'TenKhuPho', 'MaPhuong', 'mv_dvhc'], 'safe'],
            [['OBJECTID', 'MaQuan', 'Shape_Leng', 'Shape_Area'], 'number'],
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
        $query = Kp::find();

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
            'OBJECTID' => $this->OBJECTID,
            'MaQuan' => $this->MaQuan,
            'Shape_Leng' => $this->Shape_Leng,
            'Shape_Area' => $this->Shape_Area,
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(TenPhuong)', mb_strtoupper($this->TenPhuong)])
            ->andFilterWhere(['like', 'upper(TenQuan)', mb_strtoupper($this->TenQuan)])
            ->andFilterWhere(['like', 'upper(TenKhuPho)', mb_strtoupper($this->TenKhuPho)])
            ->andFilterWhere(['like', 'upper(MaPhuong)', mb_strtoupper($this->MaPhuong)])
            ->andFilterWhere(['like', 'upper(mv_dvhc)', mb_strtoupper($this->mv_dvhc)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'geom',
        'OBJECTID',
        'TenPhuong',
        'TenQuan',
        'TenKhuPho',
        'MaQuan',
        'MaPhuong',
        'Shape_Leng',
        'Shape_Area',
        'mv_dvhc',        ];
    }
}
