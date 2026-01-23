<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $assignee_id
 * @property int $status_id
 * @property string $subject
 * @property string $description
 * @property int $status
 * @property int|null $betting_relative_user_id
 * @property string|null $betting_number
 * @property string|null $betting_time_of_occurrence
 * @property int $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $assignee
 * @property Category $category
 */
class Ticket extends \yii\db\ActiveRecord
{

    CONST STATUS_NEW = 1;
    CONST STATUS_IN_PROGRESS = 2;
    CONST STATUS_RESOLVED = 3;
    CONST STATUS_REQUEST_FOR_INFORMATION = 4;
    CONST STATUS_LACK_OF_CONTENT = 5;
    CONST STATUS_CLOSED = 6;

    CONST STATUS_TYPES = [
        self::STATUS_NEW => 'Created',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_RESOLVED => 'Resolved',
        self::STATUS_REQUEST_FOR_INFORMATION => 'Request For Information',
        self::STATUS_LACK_OF_CONTENT => 'Lack Of Content',
        self::STATUS_CLOSED => 'Closed',
    ];

    /**
     * @var UploadedFile[]
     */
    public $attachmentFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket';
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
            [['assignee_id', 'betting_relative_user_id', 'betting_number', 'betting_time_of_occurrence'], 'default', 'value' => null],
            [['category_id', 'status_id', 'subject', 'description'], 'required'],
            [['category_id', 'assignee_id', 'status_id', 'betting_relative_user_id', 'created_by'], 'integer'],
            [['description'], 'string'],
            [['betting_time_of_occurrence', 'created_at', 'updated_at', 'created_by'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['betting_number'], 'string', 'max' => 10],
            [['assignee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assignee_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['attachmentFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, pdf, doc, docx, xls, xlsx, csv', 'maxFiles' => 5, 'maxSize' => 2 * 1024 * 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'assignee_id' => 'Assignee',
            'status_id' => 'Status',
            'subject' => 'Subject',
            'description' => 'Description',
            'betting_relative_user_id' => 'Betting Relative User',
            'betting_number' => 'Betting Number',
            'betting_time_of_occurrence' => 'Betting Time Of Occurrence',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Assignee]].
     *
     * @return ActiveQuery
     */
    public function getAssignee(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'assignee_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getAttachments(): ActiveQuery
    {
        return $this->hasMany(Attachment::class, ['model_id' => 'id'])
            ->andWhere(['model_type' => self::class]); // 'app\models\Ticket'
    }

    public function getImageUrls(): array
    {
        $urls = [];
        foreach ($this->attachments as $attachment) {
            $urls[] = $attachment->file_path;
        }

        return $urls;
    }

    public function getPreviewImageConfig(): array
    {
        $config = [];
        foreach ($this->attachments as $attachment) {
            $config[] = ['key' => $attachment->id];
        }

        return $config;
    }
    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return ActiveQuery
     */
    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public static function getStatusTypeById(int $statusId): ?string
    {
        return self::STATUS_TYPES[$statusId] ?? null;
    }

    public function getStatusName(): string
    {
        return self::STATUS_TYPES[$this->status_id];
    }

    public function hasFiles(): bool
    {
        return $this->getAttachments()->exists();
    }
}
