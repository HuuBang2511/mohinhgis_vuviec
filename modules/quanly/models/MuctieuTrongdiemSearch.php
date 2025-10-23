<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\MuctieuTrongdiem;

/**
 * MuctieuTrongdiemSearch represents the model behind the search form about `app\modules\quanly\models\MuctieuTrongdiem`.
 */
class MuctieuTrongdiemSearch extends MuctieuTrongdiem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ten', 'loai_muctieu', 'cap_quanly', 'dia_chi', 'phuong_xa', 'quan_huyen', 'trang_thai_an_ninh', 'mo_ta', 'geom', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = MuctieuTrongdiem::find()->where(['status' => 1]);

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

        $query->andFilterWhere(['like', 'upper(ten)', mb_strtoupper($this->ten)])
            ->andFilterWhere(['like', 'upper(loai_muctieu)', mb_strtoupper($this->loai_muctieu)])
            ->andFilterWhere(['like', 'upper(cap_quanly)', mb_strtoupper($this->cap_quanly)])
            ->andFilterWhere(['like', 'upper(dia_chi)', mb_strtoupper($this->dia_chi)])
            ->andFilterWhere(['like', 'upper(phuong_xa)', mb_strtoupper($this->phuong_xa)])
            ->andFilterWhere(['like', 'upper(quan_huyen)', mb_strtoupper($this->quan_huyen)])
            ->andFilterWhere(['like', 'upper(trang_thai_an_ninh)', mb_strtoupper($this->trang_thai_an_ninh)])
            ->andFilterWhere(['like', 'upper(mo_ta)', mb_strtoupper($this->mo_ta)])
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
        'ten',
        'loai_muctieu',
        'cap_quanly',
        'dia_chi',
        'phuong_xa',
        'quan_huyen',
        'trang_thai_an_ninh',
        'mo_ta',
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
