<?php

use app\models\Ticket;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'category_id',
                'value' => $model->category->name ?? null,
            ],
            [
                'attribute' => 'assignee_id',
                'value' => $model->assignee->name ?? null,
            ],
            [
                'attribute' => 'status_id',
                'value' => Ticket::getStatusTypeById($model->status_id),
            ],
            'subject',
            'description:html',
            'status',
            'betting_relative_user_id',
            'betting_number',
            'betting_time_of_occurrence',
            [
                'attribute' => 'created_by',
                'value' => $model->creator->name ?? null,
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
