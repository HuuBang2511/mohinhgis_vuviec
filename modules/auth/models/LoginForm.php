<?php

//namespace hcmgis\user\models\form;
namespace app\modules\auth\models;

use hcmgis\user\models\AuthUser;
use hcmgis\user\services\AuthService;
use Yii;
use yii\base\Model;
//use app\modules\auth\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username', 'checkActive'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'rememberMe' => 'Ghi nhớ đăng nhập'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user) {
                $this->addError($attribute, 'Sai tên đăng nhập hoặc mật khẩu');
                return;
            }
            if (!$user->validatePassword($this->password)) {
                // Login failed
                if (!isset($_SESSION["so_lan_sai"])) {
                    $_SESSION["so_lan_sai"] = 1;
                    //dd($_SESSION["so_lan_sai"]);
                    $this->addError($attribute, 'Bạn nhập sai mật khẩu ' . $_SESSION["so_lan_sai"] . ' lần');
                    // Yii::$app->session->setFlash('success', '1 Bạn nhập sai mật khẩu '.$_SESSION["so_lan_sai"].' lần');
                } else {
                    $_SESSION["so_lan_sai"]++;
                    if (!isset($_SESSION["blocked_until"]) && $_SESSION["so_lan_sai"] >= 5) {
                        // Block user from logging in for 5 minutes
                        $_SESSION["blocked_until"] = time() + 10; // 300 seconds = 5 minutes
                    }
                    if (isset($_SESSION["blocked_until"]) && time() < $_SESSION["blocked_until"]) {
                        // User is blocked from logging in
                        $this->addError($attribute, 'Bạn nhập sai mật khẩu quá nhiều lần. Vui lòng thử lại sau ' . date('i:s', $_SESSION["blocked_until"] - time()) . '!');
                    } else {
                        $this->addError($attribute, 'Bạn nhập sai mật khẩu ' . $_SESSION["so_lan_sai"] . ' lần');
                    }
                }
            } else {
                $_SESSION["so_lan_sai"] = 0;
            }
        }
    }

    public function checkActive($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->isActive()) {
                $this->addError($attribute, 'Account has been locked.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();
        if ($this->validate() && $user->isActive()) {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return AuthUser|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = AuthUser::findByUsername($this->username);
        }

        return $this->_user;
    }
}
