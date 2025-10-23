<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\Phuongxa;

/**
 * PhuongxaSearch represents the model behind the search form about `app\modules\quanly\models\Phuongxa`.
 */
class PhuongxaSearch extends Phuongxa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geom', 'tenTinh', 'maTinh', 'tenXa', 'maXa', 'danSo', 'dienTich', 'ghiChu'], 'safe'],
            [['id'], 'integer'],
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
        $query = Phuongxa::find();

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
        ]);

        $query->andFilterWhere(['like', 'upper(geom)', mb_strtoupper($this->geom)])
            ->andFilterWhere(['like', 'upper(tenTinh)', mb_strtoupper($this->tenTinh)])
            ->andFilterWhere(['like', 'upper(maTinh)', mb_strtoupper($this->maTinh)])
            ->andFilterWhere(['like', 'upper(tenXa)', mb_strtoupper($this->tenXa)])
            ->andFilterWhere(['like', 'upper(maXa)', mb_strtoupper($this->maXa)])
            ->andFilterWhere(['like', 'upper(danSo)', mb_strtoupper($this->danSo)])
            ->andFilterWhere(['like', 'upper(dienTich)', mb_strtoupper($this->dienTich)])
            ->andFilterWhere(['like', 'upper(ghiChu)', mb_strtoupper($this->ghiChu)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'geom',
        'tenTinh',
        'maTinh',
        'tenXa',
        'maXa',
        'danSo',
        'dienTich',
        'ghiChu',
        'id',        ];
    }
}
