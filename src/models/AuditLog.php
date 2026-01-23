<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;

/**
 * This is the model class for table "audit_log".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $model
 * @property int|null $model_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $data
 * @property int $created_at
 */
class AuditLog extends \yii\db\ActiveRecord
{

    const ACTION_LOGIN = 'login';

    const ACTION_LOGOUT = 'logout';


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'audit_log';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // disable updated_at
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'model', 'model_id', 'ip_address', 'user_agent', 'data'], 'default', 'value' => null],
            [['user_id', 'model_id'], 'integer'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['data'], 'string'],
            [['action'], 'string', 'max' => 50],
            [['model'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'action' => 'Action',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'ip_address' => 'Ip Address',
            'user_agent' => 'User Agent',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @throws Exception
     */
    public static function log(string $action, $model = null, $modelId = null, ?array $data = null): void
    {
        $log = new AuditLog([
            'user_id'       => Yii::$app->user->id,
            'action'        => $action,
            'model'         => $model,
            'model_id'      => $modelId,
            'ip_address'    => Yii::$app->request->userIP,
            'user_agent'    => Yii::$app->request->userAgent,
            'data'          => $data ? json_encode($data) : null,
        ]);

        $log->save(false);
    }

}
