<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Client Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h4><?= Html::encode($this->title) ?></h4>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'login-form',]); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'username')->textInput() ?>

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
                <div>
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>