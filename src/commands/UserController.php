<?php

namespace app\commands;

use app\models\User;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;

class UserController extends Controller
{

    public $type;

    public $email;

    public $username;

    public $password;

    public function options($actionID): array
    {
        return ['type', 'username', 'email', 'password'];
    }

    public function optionAliases(): array
    {
        return ['t' => 'type', 'n' => 'username', 'e' => 'email', 'p' => 'password'];
    }

    /**
     * @throws Exception
     */
    public function actionSignup(): int
    {
        $type = $this->type === 'developer' ? 2 : 1;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->email = 'admin@dsd.com';
        }

        if (empty($this->password) ||
            !preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $this->password)
        ) {
            $this->password = 'Password!';
        }

        if (empty($this->username) || mb_strlen($this->username) > 2) {
            $this->username = 'admin';
        }

        $user = new User();
        $user->name = $this->username;
        $user->username = $this->email;
        $user->role = $type;
        $user->domain_id = User::DSD_DOMAIN_ID;
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->setPassword($this->password);
        $user->generateEmailVerificationToken();
        $now = date('Y-m-d H:i:s');
        $user->expires_at = $now;
        $user->created_at = $now;
        $user->updated_at = $now;
        if($user->save()) {
            echo "{$user->getUserTypeName()} User successfully created with (username: {$user->username}, password: {$this->password})\n";
        } else {
            echo "Error creating user\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }

}