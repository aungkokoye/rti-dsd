<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    #header, #footer {
        display: none;
    }
    #main {
        padding-top: 0px;
    }
</style>

<div class="login__wrapper">
    <div class="site-login">
        <h4><?= Html::encode($this->title) ?></h4>
    
        <div class="row">
            <div class="col-lg-12">
    
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-form-label'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>
    
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
    
                <?= $form->field($model, 'password')->passwordInput() ?>
    
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
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>
    
                <?php ActiveForm::end(); ?>
    
            </div>
        </div>
    </div>
</div>
