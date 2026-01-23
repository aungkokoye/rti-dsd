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
            'betting_relative_user_id',
            'betting_number',
            'betting_time_of_occurrence',
            [
                'attribute' => 'created_by',
                'value' => $model->creator->name ?? null,
            ],
            'created_at',
            'updated_at',
            [
                'label' => 'Attachments',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->hasFiles()) {
                        return '<div class="text-muted"><i class="bi bi-paperclip me-1"></i>No attachments</div>';
                    }
                    $html = '<div class="d-flex flex-wrap gap-2">';
                    foreach ($model->attachments as $attachment) {
                        $ext = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                        $iconClass = match(strtolower($ext)) {
                            'pdf' => 'bi-file-earmark-pdf text-danger',
                            'doc', 'docx' => 'bi-file-earmark-word text-primary',
                            'xls', 'xlsx', 'csv' => 'bi-file-earmark-excel text-success',
                            'png', 'jpg', 'jpeg', 'gif' => 'bi-file-earmark-image text-info',
                            default => 'bi-file-earmark text-secondary',
                        };
                        $html .= Html::a(
                            '<i class="bi ' . $iconClass . ' me-1"></i>' . Html::encode($attachment->file_name),
                            $attachment->file_path,
                            [
                                'class' => 'btn btn-outline-secondary btn-sm',
                                'download' => $attachment->file_name,
                                'title' => Yii::t('app', 'Download') . ' ' . $attachment->file_name,
                            ]
                        );
                    }
                    $html .= '</div>';
                    return $html;
                },
            ],
        ],
    ]) ?>

    <!-- Comments Section -->
    <div class="comments-section mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bi bi-chat-dots me-2"></i>
                <?= Yii::t('app', 'Comments') ?>
                <span class="badge bg-secondary ms-2"><?= count($model->comments) ?></span>
            </h5>
            <?= Html::a(
                '<i class="bi bi-plus-lg me-1"></i>' . Yii::t('app', 'Add Comment'),
                ['comment/create', 'ticketId' => $model->id],
                ['class' => 'btn btn-success btn-sm']
            ) ?>
        </div>

        <?php if (empty($model->comments)): ?>
            <div class="text-center text-muted py-5 bg-light rounded">
                <i class="bi bi-chat-square-text" style="font-size: 3rem;"></i>
                <p class="mt-3 mb-0"><?= Yii::t('app', 'No comments yet. Be the first to comment!') ?></p>
            </div>
        <?php else: ?>
            <div class="comment-list">
                <?php foreach ($model->comments as $index => $comment): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <!-- Comment Header -->
                            <div class="d-flex align-items-start">
                                <div class="avatar me-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                         style="width: 45px; height: 45px; font-size: 1.1rem; font-weight: 600;">
                                        <?= strtoupper(substr($comment->creator->username ?? 'U', 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong class="text-dark"><?= Html::encode($comment->creator->username ?? 'Unknown') ?></strong>
                                            <span class="badge bg-info bg-opacity-10 text-info ms-2">
                                                <?= Html::encode($comment->creator->userTypeName ?? '') ?>
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= Yii::$app->formatter->asRelativeTime($comment->created_at) ?>
                                        </small>
                                    </div>

                                    <!-- Comment Message -->
                                    <div class="comment-content mb-3">
                                        <?= $comment->message ?>
                                    </div>

                                    <!-- Attachments -->
                                    <?php if ($comment->attachments): ?>
                                        <div class="attachments-section border-top pt-3">
                                            <small class="text-muted d-block mb-2">
                                                <i class="bi bi-paperclip me-1"></i>
                                                <?= Yii::t('app', 'Attachments') ?> (<?= count($comment->attachments) ?>)
                                            </small>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($comment->attachments as $attachment): ?>
                                                    <?php
                                                    $ext = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                                                    $iconClass = match(strtolower($ext)) {
                                                        'pdf' => 'bi-file-earmark-pdf text-danger',
                                                        'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                                        'xls', 'xlsx', 'csv' => 'bi-file-earmark-excel text-success',
                                                        'png', 'jpg', 'jpeg', 'gif' => 'bi-file-earmark-image text-info',
                                                        default => 'bi-file-earmark text-secondary',
                                                    };
                                                    ?>
                                                    <?= Html::a(
                                                        '<i class="bi ' . $iconClass . ' me-1"></i>' . Html::encode($attachment->file_name),
                                                        $attachment->file_path,
                                                        [
                                                            'class' => 'btn btn-outline-secondary btn-sm',
                                                            'download' => $attachment->file_name,
                                                            'title' => Yii::t('app', 'Download') . ' ' . $attachment->file_name,
                                                        ]
                                                    ) ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
