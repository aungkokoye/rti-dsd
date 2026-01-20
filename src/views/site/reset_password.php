<?php

/** @var yii\web\View $this */
/** @var ResetForm $model */

use app\models\ResetForm;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reset-password">
    <h4><?= Html::encode($this->title) ?></h4>

    <p>Please reset your password:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                    'id' => 'reset-password-form',
                    'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'col-form-label'],
                            'inputOptions' => ['class' => 'col-lg-3 form-control'],
                            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
            ]); ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'verifyPassword')->passwordInput() ?>
            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'captchaAction' => 'site/captcha',
                    'template' => '{image} {input}',
                    'options' => ['class' => 'form-control'],
                    'imageOptions' => [
                            'class' => 'captcha-img',
                            'title' => 'Click to refresh',
                            'style' => 'cursor:pointer',
                            'onclick' => 'this.src=this.src+"?"+Math.random();'
                    ],
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
</div>
