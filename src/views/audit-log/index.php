<?php

use app\models\AuditLog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\AuditLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Audit Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'user_id',
            'action',
            'model',
            'model_id',
            'ip_address',
            'user_agent',
            'data:ntext',
            'created_at',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
