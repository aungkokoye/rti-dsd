<?php

use app\models\Category;
use app\models\Ticket;
use app\models\User;
use kartik\editors\Summernote;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

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
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary',
            'browseIcon' => '<i class="bi bi-folder2-open"></i> ',
            'browseLabel' => 'Select Files',
            'removeClass' => 'btn btn-danger',
            'removeIcon' => '<i class="bi bi-trash"></i> ',
            'removeLabel' => 'Remove',
            'maxFileCount' => 5,
            'maxFileSize' => 2048,
            'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv'],
            'fileActionSettings'    => [
                    'showZoom'    => false, // Hide zoom/enlarge icon
                    'showRemove'  => true,  // Show delete icon
                    'showUpload'  => false, // Hide upload icon
                    'showDrag'    => false, // Hide drag icon
                ],
        ],
    ])->label('Attachments') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
