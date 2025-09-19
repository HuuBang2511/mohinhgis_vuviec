<?php

namespace hcmgis\user\models;

use hcmgis\user\assets\UserAsset;
use hcmgis\user\Constant;
use Yii;
use hcmgis\user\models\AuthUser;

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
 * @property bool $is_admin
 */
class AuthUser extends AuthUser
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
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'active', 'confirmed'], 'default', 'value' => null],
            [['status', 'active', 'confirmed'], 'integer'],
            [['username', 'password', 'auth_key', 'password_reset_token', 'access_token', 'avatar', 'fullname', 'email'], 'string', 'max' => 255],
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
            'active' => 'Trạng thái tài khoản',
            'avatar' => 'Avatar',
            'fullname' => 'Họ tên',
            'confirmed' => 'Xác nhận email',
            'email' => 'Email',
        ];
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
