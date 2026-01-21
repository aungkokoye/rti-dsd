<?php

use app\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var User $user */

$resetPasswordLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->verification_token]);
?>
<div class="reset-password">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetPasswordLink), $resetPasswordLink) ?></p>
</div>
