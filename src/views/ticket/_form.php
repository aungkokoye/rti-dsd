<?php

use app\models\Category;
use app\models\Ticket;
use app\models\User;
use kartik\editors\Summernote;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

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


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
