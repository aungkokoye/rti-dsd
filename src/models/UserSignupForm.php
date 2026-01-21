<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class UserSignupForm extends Model
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $site_user_id;

    /**
     * @var integer
     */
    public $domain_id;

    /**
     * @var string
     */
    public $site_key;

    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 50],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'string', 'max' => 100],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            ['site_user_id', 'required'],
            ['site_user_id', 'integer'],
            ['site_key', 'required'],
            ['site_key', 'string', 'max' =>5, 'min' => 5],
            ['domain_id', 'required'],
            ['domain_id', 'integer'],

            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return null|bool whether the creating a new account was successful and email was sent
     * @throws \Throwable
     */
    public function signup(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->role = User::USER_ROLE;
        $user->status = User::STATUS_INACTIVE;
        $user->site_user_id = $this->site_user_id;
        $user->site_key = $this->site_key;
        $user->domain_id = $this->domain_id;

        $user->expires_at = (new \DateTime())->modify('+15 minutes')
            ->format('Y-m-d H:i:s');
        $user->setPassword(Yii::$app->security->generateRandomString());
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be sent
     * @return bool whether the email was sent
     */
    protected function sendEmail(User $user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'layouts/resetPassword-html', 'text' => 'layouts/resetPassword-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['noReplyEmail'] => Yii::$app->name])
            ->setTo($this->username)
            ->setSubject('Password Reset Link')
            ->send();
    }
}
