<?php

namespace app\runtime;

use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $verification_token
 * @property string  $email
 * @property string  $auth_key
 * @property integer $user_type
 * @property integer $domain_type
 * @property integer $status
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 * @property string  $password write-only password
 *
 * @method static findOne(array $array)
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const ADMIN_ROLE = 1;
    const DEVELOPER_ROLE = 2;
    const USER_ROLE = 3;
    const DSD_DOMAIN = 1;
    const SOLUTION_DOMAIN = 2;
    const VINUS_DOMAIN = 3;

    const USER_TYPES = [
        self::ADMIN_ROLE => 'Admin',
        self::DEVELOPER_ROLE => 'Developer',
        self::USER_ROLE => 'User',
    ];

    const DOMAIN_TYPES = [
        self::DSD_DOMAIN => 'Domain Service Desk',
        self::SOLUTION_DOMAIN => 'Solution',
        self::VINUS_DOMAIN => 'Vinus',
    ];

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 100],
            ['email', 'unique', 'targetClass' => 'app\runtime\User', 'message' => 'This email address has already been taken.'],

            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\runtime\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 50],

            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],

            ['user_type', 'required'],
            ['user_type', 'in', 'range' => [self::ADMIN_ROLE, self::DEVELOPER_ROLE, self::USER_ROLE, self::STATUS_DELETED]],

            ['domain_type', 'required'],
            ['domain_type', 'in', 'range' => [self::DSD_DOMAIN, self::SOLUTION_DOMAIN, self::VINUS_DOMAIN]],
        ];
    }

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?User
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername(string $name): ?User
    {
        return static::findOne(['username' => $name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if the password provided is valid for the current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByPasswordResetToken(string$token): ?self
    {
        return static::findOne(['verification_token' => $token]);
    }

    /**
     * Generates a new token for email verification
     * @throws Exception
     */
    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates a "remember me" authentication key
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getRole(): int
    {
        return $this->user_type;
    }

    public function getDomain(): int
    {
        return $this->domain_type;
    }

    public function isAdmin(): bool
    {
        return $this->user_type == self::ADMIN_ROLE;
    }

    public function isDeveloper(): bool
    {
        return $this->user_type == self::DEVELOPER_ROLE;
    }

    public function isUser(): bool
    {
        return $this->user_type == self::USER_ROLE;
    }

    public function isAdminOrDeveloper(): bool
    {
        return $this->isAdmin() || $this->isDeveloper();
    }

    public function isEmailLinkExpired(): bool
    {
        return $this->expires_at < date('Y-m-d H:i:s');
    }

    public function isDisabled(): bool
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function getUserTypeName(): string
    {
        return self::USER_TYPES[$this->user_type];
    }

    public function getDomainTypeName(): string
    {
        return self::DOMAIN_TYPES[$this->domain_type];
    }
}
