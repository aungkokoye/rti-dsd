<?php

use app\models\Category;
use app\models\Ticket;
use app\models\User;
use kartik\editors\Summernote;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        Category::getDropdownList(),
        ['prompt' => '-- Select Category --']
    ) ?>

    <?= $form->field($model, 'assignee_id')->dropDownList(
        User::getAssignableUsers($model->created_by),
        ['prompt' => '-- Select Assignee --']
    ) ?>

    <?= $form->field($model, 'status_id')->dropDownList(
        Ticket::STATUS_TYPES,
        ['prompt' => '-- Select Status --']
    ) ?>

    <?= $form->field($model, 'betting_relative_user_id')->textInput() ?>

    <?= $form->field($model, 'betting_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'betting_time_of_occurrence')->input('datetime-local', [
        'class' => 'form-control',
    ]) ?>

<?= $form->field($model, 'attachmentFiles[]')->widget(FileInput::class, [
    'options' => [
        'accept' => 'image/*,.pdf,.doc,.docx,.xls,.xlsx,.csv',
        'multiple' => true,
    ],
    'pluginOptions' => [
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false,
        'showClose'   => false,
        'browseClass' => 'btn btn-outline-primary',
        'browseIcon' => '<i class="bi bi-folder2-open"></i> ',
        'browseLabel' => 'Select Files',
        'removeClass' => 'btn btn-outline-danger',
        'removeIcon' => '<i class="bi bi-trash"></i> ',
        'removeLabel' => 'Remove',
        'maxFileCount' => 5,
        'maxFileSize' => 2048,
        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv'],
        'fileActionSettings' => [
            'showZoom'    => false,
            'showRemove'  => true,
            'showUpload'  => false,
            'showDrag'    => false,
        ],
        'deleteUrl'            => Url::to(['ticket/delete-image']),
        'initialPreview'        => $model->getImageUrls(),
        'initialPreviewAsData' => true, // show image previews
        'initialPreviewConfig'  => $model->getPreviewImageConfig(),
        'overwriteInitial' => false,    // keep old files
    ],
])->label('Attachments') ?>

    <div class="form-group d-flex flex-column align-items-center flex-sm-row justify-content-center gap-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(
            'Cancel',
            '/ticket/index',
            [
                'class' => 'btn btn-outline-primary',
                'name' => 'cancel-button',
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>