<?php

namespace app\modules\auth\services;

use hcmgis\user\Constant;
use hcmgis\user\models\AuthUser;
use app\modules\auth\models\User;
use Yii;

class UserService
{
    public static $AUTH_DELETE = [
        'ALIVE' => 1,
        'DELETED' => 0
    ];

    public static $AUTH_TYPE = [
        'PUBLIC' => 1,
        'PRIVATE' => 0
    ];

    public static $AUTH_CONFIRM = [
        'CONFIRMED' => 1,
        'UNCONFIRMED' => 0
    ];

    public static $AUTH_ROLE = [
        'SUPERUSER' => 1,
        'ADMIN' => 2,
        'USER' => 3
    ];

    public static $AUTH_STATUS = [
        'ACTIVE' => 1,
        'DEACTIVE' => 0
    ];

    public static $USER_STATUS = [
        'ACTIVE' => 1,
        'DEACTIVE' => 0
    ];
    public static $USER_DELETE = [
        'ALIVE' => 1,
        'DELETED' => 0
    ];
    public static $USER_TYPE = [
        'PUBLIC' => 1,
        'PRIVATE' => 0
    ];
    public static $USER_CONFIRM = [
        'CONFIRMED' => 1,
        'UNCONFIRMED' => 0
    ];
    public static $USER_ROLE = [
        'SUPERUSER' => 1,
        'ADMIN' => 2,
        'USER' => 3
    ];
    public static $RESPONSES = [
        'USERNAME_EXIST' => 'Username is already in use by another account',
        'EMPTY_FIELD' => 'Please fill out the form',
        'INCORRECT_PASSWORD' => 'Incorrect password',
        'PASSWORD_LENGTH' => 'Password length is from 6 to 15 characters',
        'PASSWORD_MATCH' => 'Incorrect confirm password',
        'EMAIL_EXIST' => 'Email is already in use by another account',
        'EMAIL_NOT_EXIST' => 'Email has not been registered for any account',
        'EMAIL_FORMAT' => 'Invalid email',
        'INCORRECT_EMAIL_PASSWORD' => 'Incorrect email or password',
        'NOT_ENOUGH_PERMISSION' => 'You have not enough permission to perform this action',
        'USER_NOT_FOUND' => 'User not found',
        'LOGIN_SUCCESS' => 'Login successfully',
        'REGISTER_SUCCESS' => 'Register acoount successfully',
        'CREATE_SUCCESS' => 'Đã tạo tài khoản thành công!',
        'UNCONFIRMED' => 'Email has not been confirmed, please check again',
        'DELETE_SUCCESS' => 'Delete user successfully',
        'ACTIVE_SUCCESS' => 'Active user successfully',
        'DEACTIVE_SUCCESS' => 'Deactive user successfully',
        'CHANGE_ROLE_SUCCESS' => 'Change user\'s role successfully',
        'CHANGE_TYPE_SUCCESS' => 'Change user\'s publish type successfully',
        'EMPTY_LIST' => 'Empty list',
        'CHANGE_AVATAR_SUCCESS' => 'Change avatar successfully',
        'CHANGE_INFORMATION_SUCCESS' => 'Change user\'s information successfully',
        'CHANGE_PASSWORD_SUCCESS' => 'Change password successfully',
        'FOLLOW_USER' => 'Follow user',
        'UNFOLLOW_USER' => 'Unfollow user',
        'ERROR' => 'Something went wrong',
        'ACTION_ERROR' => 'Action error',
        'SEND_EMAIL_RESET_PASSWORD_SUCCESS' => 'Instruction for resetting a new password have been sent, please check your email'
    ];

    public static function View($id)
    {

        $user = User::findOne($id);

        return $user;
    }

    public static function Delete($model)
    {
        $model->status = Constant::STATUS_DELETED;
        if ($model->save()) {
            return true;
        }
    }

    public static function Active($model, $data)
    {
        if ($model->load($data)) {
            $model->save();
            return true;
        };
        return false;
    }

    public static function Create($model, $data)
    {
        $username = $data['User']['username'];
        if (self::CheckUsernameExist($username)) {
            return self::$RESPONSES['USERNAME_EXIST'];
        } else {
            if ($model->load($data)) {
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $model->status = self::$USER_STATUS['ACTIVE'];
                $model->save();
                return true;
            };

            return false;
        }
    }

    public static function CreateNguoidan($model, $data)
    {
        $username = $data['UserNguoidan']['username'];
        if (self::CheckUsernameExist($username)) {
            return self::$RESPONSES['USERNAME_EXIST'];
        } else {
            if ($model->load($data)) {
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                $model->status = self::$USER_STATUS['ACTIVE'];
                $model->save();
                return true;
            };

            return false;
        }
    }


    public static function Update($model, $data)
    {
        if ($model->load($data)) {
            //            $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            return $model->save();
        };
        return false;
    }

    public static function UserFullName()
    {
        return Yii::$app->user->identity->fullname;
    }

    public static function GetUserByUsername($username)
    {
        $user = AuthUser::findOne(['username' => $username]);
        return $user;
    }

    public static function GetIdByUsername($username)
    {
        $user = self::GetUserByUsername($username);
        if ($user) {
            return $user->id;
        }
        return false;
    }

    public static function CheckUsernameExist($username)
    {
        $existUsername = AuthUser::findAll(['username' => $username]);
        if ($existUsername) {
            return true;
        }
        return false;
    }

    public static function CheckEmailFormat($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public static function CheckPhoneFormat($phone)
    {
        if (strlen($phone) == 10 && preg_match("/^[0-9]{10}$/", $phone)) {
            return true;
        }
        return false;
    }

    public static function GetUserAvatar($filename)
    {
        $path = Yii::$app->homeUrl . ($filename ? 'uploads/' . $filename : 'resources/images/no_avatar.jpg');
        return $path;
    }

    public static function SetNewPassword($data)
    {
        $userData = $data['User'];
        if (!$userData['password'] || !$userData['cpassword']) {
            return self::$RESPONSES['EMPTY_FIELD'];
        } else if (strlen($userData['password']) < 6 || strlen($userData['password']) > 15) {
            return self::$RESPONSES['PASSWORD_LENGTH'];
        } else if ($userData['password'] != $userData['cpassword']) {
            return self::$RESPONSES['PASSWORD_MATCH'];
        } else {
            return true;
        }
        return self::$RESPONSES['ERROR'];
    }
}
