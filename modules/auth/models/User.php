<?php

namespace app\modules\auth\models;

use hcmgis\user\models\AuthUser;
use app\modules\quanly\models\Truonghoc;
use app\modules\quanly\models\Lophoc;
use app\modules\quanly\models\Phuongxa;
use Yii;

/**
 * This is the model class for table "auth_user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $auth_key
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $status
 * @property string|null $password_reset_token
 * @property string|null $access_token
 * @property int|null $active
 * @property string|null $avatar
 * @property string|null $fullname
 * @property int|null $confirmed
 * @property string|null $email
 * @property bool|null $is_admin
 * @property int|null $khupho_id
 * @property string|null $quyen
 * @property string|null $nhomlinhvuc
 */
class User extends AuthUser
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['created_at', 'updated_at', 'phuongxa'], 'safe'],
            [['status', 'active', 'confirmed', 'donvi_id', 'canbo_id', 'captaikhoan', 'nguoidan_id'], 'default', 'value' => null],
            [['status', 'active', 'confirmed', 'donvi_id', 'canbo_id', 'captaikhoan', 'nguoidan_id'], 'integer'],
            [['is_admin', 'is_nguoidan'], 'boolean'],
            [['username', 'password', 'auth_key', 'password_reset_token', 'access_token', 'avatar', 'fullname', 'email', 'maxacthuc'], 'string', 'max' => 255],
            [['cccd'], 'string', 'max' => 12],
            [['sodienthoai', 'diachi'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'password_reset_token' => 'Password Reset Token',
            'access_token' => 'Access Token',
            'active' => 'Active',
            'avatar' => 'Avatar',
            'fullname' => 'Họ và tên',
            'confirmed' => 'Confirmed',
            'email' => 'Email',
            'is_admin' => 'Is Admin',
            'phuongxa' => 'Phường xã',
            'donvi_id' => 'Đơn vị',
            'canbo_id' => 'Cán bộ',
            'captaikhoan' => 'Cấp tài khoản',
            'nguoidan_id' => 'Người dân',
            'is_nguoidan' => 'Người dân',
            'maxacthuc' => 'Mã xác thực',
            'sodienthoai' => 'Số điện thoại',
            'diachi' => 'Địa chỉ',
            'cccd' => 'CCCD',
        ];
    }

    public function getPhuongXa()
    {
        return $this->hasOne(Phuongxa::className(), ['ma_dvhc' => 'phuongxa']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'active' =>  Constant::UNLOCK]);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function getAvatarUrl()
    {
        return $this->avatar == null || $this->avatar == '' ? \Yii::$app->assetManager->getAssetUrl(Yii::$app->assetManager->getBundle(UserAsset::class), 'images/blank.svg') : $this->avatar;
    }

    public function  isActive()
    {
        return $this->active == Constant::UNLOCK;
    }
}
