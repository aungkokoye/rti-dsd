<?php

namespace app\models;

use app\runtime\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ResetForm extends Model
{
    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $verifyPassword;

    public $verifyCode;

    public function rules(): array
    {
        return [
            [['password', 'verifyPassword'], 'required'],
            [
                'password',
                'match',
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                'message' => 'Password must be at least 8 characters and include uppercase, lowercase, number and special character.',
            ],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function resetPassword(ResetForm $model, string $token): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findByPasswordResetToken($token);

        if (!$user instanceof User  ||
            $user->isEmailLinkExpired() ||
            $user->isDisabled()
        ) {
            return false;
        }

        $user->setPassword($model->password);
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        Yii::$app->user->login($user);

        return true;
    }
}