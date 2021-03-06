<?php
/**
 * Форма авторизации
 *
 * @category YupeComponents
 * @package  yupe.modules.user.forms
 * @author   YupeTeam <team@yupe.ru>
 * @license  BSD http://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_BSD
 * @version  0.5.3
 * @link     http://yupe.ru
 *
 **/
class LoginForm extends yupe\models\YFormModel
{
    public $email;
    public $password;
    public $remember_me;
    public $verifyCode;

    public function rules()
    {
        $module = Yii::app()->getModule('user');

        return array(
            array('email, password', 'required'),
            array('email', 'email'),
            array('remember_me','boolean'),
            array('verifyCode', 'yupe\components\validators\YRequiredValidator', 'allowEmpty' => !$module->showCaptcha || !CCaptcha::checkRequirements(), 'message' => Yii::t('UserModule.user', 'Check code incorrect'), 'on' => 'loginLimit'),
            array('verifyCode', 'captcha', 'allowEmpty' => !$module->showCaptcha || !CCaptcha::checkRequirements(), 'on' => 'loginLimit'),
            array('verifyCode', 'emptyOnInvalid')
        );
    }

    public function attributeLabels()
    {
        return array(
            'email'      => Yii::t('UserModule.user', 'Email'),
            'password'   => Yii::t('UserModule.user', 'Password'),
            'remember_me'=> Yii::t('UserModule.user', 'Remember me'),
            'verifyCode' => Yii::t('UserModule.user', 'Check code'),
        );
    }

    public function attributeDescriptions()
    {
        return array(
            'email'      => Yii::t('UserModule.user', 'Email'),
            'password'   => Yii::t('UserModule.user', 'Password'),
            'remember_me'=> Yii::t('UserModule.user', 'Remember me'),
            'verifyCode' => Yii::t('UserModule.user', 'Check code'),
        );
    }

    /**
     * Обнуляем введённое значение капчи, если оно введено неверно:
     *
     * @param string $attribute - имя атрибута
     * @param mixed  $params    - параметры
     *
     * @return void
     **/
    public function emptyOnInvalid($attribute, $params)
    {
        if ($this->hasErrors()) {
            $this->verifyCode = null;
        }
    }
}