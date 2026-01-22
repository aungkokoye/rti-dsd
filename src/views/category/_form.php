<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group d-flex flex-column align-items-center flex-sm-row justify-content-center gap-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(
            'Cancel',
            '/category/index',
            [
                'class' => 'btn btn-outline-primary',
                'name' => 'cancel-button',
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
