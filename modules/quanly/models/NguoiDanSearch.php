<?php

namespace app\modules\quanly\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\quanly\models\NguoiDan;

/**
 * NguoiDanSearch represents the model behind the search form about `app\modules\quanly\models\NguoiDan`.
 */
class NguoiDanSearch extends NguoiDan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gioitinh_id', 'created_by', 'updated_by', 'hogiadinh_id', 'loaicutru_id', 'quanhechuho_id', 'status'], 'integer'],
            [['ho_ten', 'dia_chi', 'so_dien_thoai', 'email', 'nhom_doi_tuong', 'created_at', 'updated_at', 'cccd', 'cccd_ngaycap', 'cccd_noicap'], 'safe'],
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
        $query = NguoiDan::find();

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
            'gioitinh_id' => $this->gioitinh_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'hogiadinh_id' => $this->hogiadinh_id,
            'loaicutru_id' => $this->loaicutru_id,
            'quanhechuho_id' => $this->quanhechuho_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'upper(ho_ten)', mb_strtoupper($this->ho_ten)])
            ->andFilterWhere(['like', 'upper(dia_chi)', mb_strtoupper($this->dia_chi)])
            ->andFilterWhere(['like', 'upper(so_dien_thoai)', mb_strtoupper($this->so_dien_thoai)])
            ->andFilterWhere(['like', 'upper(email)', mb_strtoupper($this->email)])
            ->andFilterWhere(['like', 'upper(nhom_doi_tuong)', mb_strtoupper($this->nhom_doi_tuong)])
            ->andFilterWhere(['like', 'upper(cccd)', mb_strtoupper($this->cccd)])
            ->andFilterWhere(['like', 'upper(cccd_ngaycap)', mb_strtoupper($this->cccd_ngaycap)])
            ->andFilterWhere(['like', 'upper(cccd_noicap)', mb_strtoupper($this->cccd_noicap)]);

        return $dataProvider;
    }

    public function getExportColumns()
    {
        return [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            'id',
        'ho_ten',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'nhom_doi_tuong',
        'gioitinh_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'hogiadinh_id',
        'loaicutru_id',
        'cccd',
        'cccd_ngaycap',
        'cccd_noicap',
        'quanhechuho_id',
        'status',        ];
    }
}
