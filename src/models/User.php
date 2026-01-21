<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string  $name
 * @property string $username
 * @property integer $role
 * @property string $site_key
 * @property integer $site_user_id
 * @property integer $domain_id
 * @property string  $password_hash
 * @property string  $verification_token
 * @property string  $auth_key
 * @property integer $status
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $password write-only password
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

    const SOLUTION_DOMAIN_ID = 1;

    const VINUS_DOMAIN_ID = 2;

    const DSD_DOMAIN_ID = 3;

    const SITE_KEY = 'site_key';

    const SITE_USER_ID = 'user_id';

    const SITE_ID = 'site_id';

    const USER_TYPES = [
        self::ADMIN_ROLE => 'Admin',
        self::DEVELOPER_ROLE => 'Developer',
        self::USER_ROLE => 'User',
    ];

    const DOMAIN_TYPES = [
        self::SOLUTION_DOMAIN_ID => 'Solution',
        self::VINUS_DOMAIN_ID => 'Vinus',
        self::DSD_DOMAIN_ID => 'DSD',
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
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'string', 'max' => 100],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 50],

            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],

            ['role', 'required'],
            ['role', 'in', 'range' => [self::ADMIN_ROLE, self::DEVELOPER_ROLE, self::USER_ROLE]],

            ['domain_id', 'required'],
            ['domain_id', 'in', 'range' => [self::SOLUTION_DOMAIN_ID, self::VINUS_DOMAIN_ID, self::DSD_DOMAIN_ID]],

            [['site_key', 'site_user_id'], 'required', 'when' => function ($model) {
                return $model->role == self::USER_ROLE;
            }, 'whenClient' => "function (attribute, value) {                                                                                                                                                                 
                return $('#user-role').val() == " . self::USER_ROLE . ";                                                                                                                                                      
            }"],
            ['site_key', 'string', 'max' => 32],
            ['site_user_id', 'integer'],

            [['expires_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername(string $email): ?User
    {
        return static::findOne(['username' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public static function tokenValidation(string $token): array
    {
        if (empty($token)) {
            throw new NotFoundHttpException('Invalid token.');
        }

        $encrypted = base64_decode($token);
        $decryptedToken = Yii::$app->security
            ->decryptByPassword($encrypted, Yii::$app->params['encryptionKey']);

        if ($decryptedToken === false) {
            throw new BadRequestHttpException('Invalid token - decryption failed.');
        }

        $data = json_decode($decryptedToken, true);

        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid token - invalid data format.');
        }

        $required = [self::SITE_KEY, self::SITE_ID, self::SITE_USER_ID];
        $missing = array_diff($required, array_keys($data));

        if (!empty($missing)) {
            throw new BadRequestHttpException('Missing data: ' . implode(', ', $missing));
        }

        return $data;
    }

    public static function findForLinkLogin(string $key, int $siteUserId, int $siteId ): ?User
    {
        return static::findOne([
            'site_key'      => $key,
            'site_user_id'  => $siteUserId,
            'domain_id'     => $siteId,
            'status'        => self::STATUS_ACTIVE,
            'role'          => self::USER_ROLE
        ],
        );
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
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role == self::ADMIN_ROLE;
    }

    public function isDeveloper(): bool
    {
        return $this->role == self::DEVELOPER_ROLE;
    }

    public function isUser(): bool
    {
        return $this->role == self::USER_ROLE;
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
        return self::USER_TYPES[$this->role];
    }

    public function getDomainName(): string
    {
        return self::DOMAIN_TYPES[$this->domain_id];
    }
}
