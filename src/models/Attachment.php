<?php

namespace app\models;


use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "attachment".
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $file_name
 * @property string $file_path
 * @property string $mimie_type
 * @property int $file_size
 * @property int $created_by
 * @property string $created_at
 */
class Attachment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'model_id', 'file_name', 'file_path', 'mimie_type', 'file_size'], 'required'],
            [['model_id', 'file_size', 'created_by'], 'integer'],
            [['created_at', 'created_by'], 'safe'],
            [['model_type', 'mimie_type'], 'string', 'max' => 100],
            ['model_type', 'in', 'range' => ['app\models\Ticket', 'app\models\Comment']],
            [['file_name', 'file_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'            =>  'ID',
            'model_type'    =>  'Model Type',
            'model_id'      =>  'Model ID',
            'file_name'     =>  'File Name',
            'file_path'     =>  'File Path',
            'mimie_type'    =>  'MIME Type',
            'file_size'     =>  'File Size',
            'created_by'    =>  'Created By',
            'created_at'    =>  'Created At',
        ];
    }


    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
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
     * Get the related model (Ticket or Comment)
     */
    public function getModel(): ?ActiveQuery
    {
        // Dynamically link to the model based on model_type
        $class = $this->model_type;
        if (class_exists($class)) {
            return $this->hasOne($class, ['id' => 'model_id']);
        }
        return null;
    }
}