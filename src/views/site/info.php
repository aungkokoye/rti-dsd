<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var string $message */

use app\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Info';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h4><?= Html::encode($this->title) ?></h4>

    <p><?= $message ?></p>
</div>
