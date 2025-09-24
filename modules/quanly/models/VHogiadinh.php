<?php

namespace app\modules\quanly\models;
use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "v_hogiadinh".
 *
 * @property int|null $id
 * @property string|null $ma_hsct
 * @property int|null $nocgia_id
 * @property int|null $loaicutru_id
 * @property string|null $diachi_nocgia
 */
class VHogiadinh extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_hogiadinh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nocgia_id', 'loaicutru_id'], 'default', 'value' => null],
            [['id', 'nocgia_id', 'loaicutru_id'], 'integer'],
            [['ma_hsct', 'diachi_nocgia'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ma_hsct' => 'Mã hồ sơ cư trú',
            'nocgia_id' => 'Nocgia ID',
            'loaicutru_id' => 'Loại cư trú',
            'diachi_nocgia' => 'Địa chỉ nóc gia',
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }
}
