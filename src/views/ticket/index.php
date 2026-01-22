<?php

use app\models\Ticket;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ticket'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id' => 'ticket-grid-pjax']); ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width: 60px;'],
            ],
            [
               'attribute' => 'category_id',
               'value' => fn($model) => $model->category?->name,
            ],
            [
               'attribute' => 'assignee_id',
               'value' =>  fn($model) => $model->assignee?->name,
            ],
            [
               'attribute' => 'status_id',
               'value' => fn($model) => $model->getStatusName(),
            ],
            'subject',
            //'description:ntext',
            //'status',
            //'betting_relative_user_id',
            //'betting_number',
            //'betting_time_of_occurrence',
            //'created_by',
            'created_at',
            'updated_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                 'contentOptions' => [
                    'style' => 'white-space: nowrap; width: 90px;',
                 ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
