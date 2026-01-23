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
<div class="site-audit__list" id="page_wrapper">


    <div class="flex-table-container">
        <!-- Table Header -->
        <div class="flex-table-header">
            <div class="flex-table-cell cell-ticket-id ">User ID</div>
            <div class="flex-table-cell cell-title">Action</div>
            <div class="flex-table-cell cell-ip-address">Ip Address</div>
            <div class="flex-table-cell cell-created-date">Created Date</div>
        </div>

        <!-- Table Body -->
        <div class="flex-table-body">
            <?php

            foreach ($dataProvider->models as $model) : ?>
                <div class="flex-table-row">
                    <div class="flex-table-cell cell-ticket-id"><?= $model->user_id ?></div>
                    <div class="flex-table-cell cell-title"><?= $model->action ?></div>
                    <div class="flex-table-cell cell-ip-address"><?= $model->ip_address ?></div>
                    <div class="flex-table-cell cell-created-date"><?= date('Y M d', strtotime($model['created_at'])) ?> </div>

                </div>
            <?php endforeach; ?>

        </div>
    </div>

</div>
