<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $message
 * @property string $created_at
 * @property int $created_by
 *
 * @property Ticket $ticket
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile[]
     */
    public $attachmentFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'comment';
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
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
                'value' => function () {
                    return Yii::$app->user->id ?? Yii::$app->user->identity->id ?? null;
                },
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ticket_id', 'message'], 'required'],
            [['ticket_id'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'created_by'], 'safe'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['attachmentFiles'], 'file', 'skipOnEmpty' => true,
                'extensions' => 'png, jpg, jpeg, gif, pdf, doc, docx, xls, xlsx, csv', 'maxFiles' => 2,
                'maxSize' => 2 * 1024 * 1024, 'tooBig' => 'Max file size is 2MB'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'            => 'ID',
            'ticket_id'     => 'Ticket',
            'message'       => 'Message',
            'created_by'    => 'Own By',
            'created_at'    => 'Created At',
        ];
    }

    /**
     * Gets a query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }

    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getAttachments(): ActiveQuery
    {
        return $this->hasMany(Attachment::class, ['model_id' => 'id'])
            ->andWhere(['model_type' => self::class]); // 'app\models\Ticket'
    }
}
