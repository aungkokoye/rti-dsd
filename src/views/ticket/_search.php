<?php

use app\models\Category;
use app\models\Ticket;
use app\models\User;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true],
    ]); ?>

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

    <?= $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'betting_relative_user_id') ?>

    <?php // echo $form->field($model, 'betting_number') ?>

    <?php // echo $form->field($model, 'betting_time_of_occurrence') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'created_at_from')->widget(DatePicker::class, [
                    'pluginOptions' => [
                            'label'     => 'Ticket Created From',
                            'autoclose' => true,
                            'format'    => 'yyyy-mm-dd',
                    ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'created_at_to')->widget(DatePicker::class, [
                    'pluginOptions' => [
                            'label'     => 'Ticket Created To',
                            'autoclose' => true,
                            'format'    => 'yyyy-mm-dd',
                    ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
