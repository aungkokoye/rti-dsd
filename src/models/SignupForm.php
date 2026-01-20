<?php

namespace app\models;

use app\runtime\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $user_type;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\runtime\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 50],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 100],
            ['email', 'unique', 'targetClass' => 'app\runtime\User', 'message' => 'This email address has already been taken.'],

            ['user_type', 'required'],
            ['user_type', 'integer'],
            ['user_type', 'in', 'range' => [User::ADMIN_ROLE, User::DEVELOPER_ROLE, User::USER_ROLE]],
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
        $user->email = $this->email;
        $user->user_type = $this->user_type;

        $user->domain_type = User::DSD_DOMAIN;
        $user->expires_at = (new \DateTime())->modify('+15 minutes')
            ->format('Y-m-d H:i:s');
        $user->setPassword(Yii::$app->security->generateRandomString());
        //$user->setPassword('password');
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
            ->setTo($this->email)
            ->setSubject('Password Reset Link')
            ->send();
    }
}
