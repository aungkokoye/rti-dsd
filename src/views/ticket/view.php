<?php

use app\models\Ticket;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Ticket $model */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


?>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>


<!-- ======================= Delete Modal -->
<div class="custom-modal" id="deleteCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="delete-custom-modal__content" data-content>

        <div class="delete-custom-modal__body">
            <p>Are you sure you want to delete this ticket?</p>
        </div>

        <div class="delete-custom-modal__footer">
            <button class="btn btn btn-outline-primary" close-delete-modal>Cancel</button>
            <button class="btn btn-primary" confirm-delete-modal>Delete</button>
        </div>
    </div>
</div>
<!-- ======================= End Delete Modal -->

<!-- ======================= Delete Comment Modal -->
<div class="custom-modal" id="deleteCmtCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="delete-custom-modal__content" data-content>

        <div class="delete-custom-modal__body">
            <p>Are you sure you want to delete this comment?</p>
        </div>

        <div class="delete-custom-modal__footer">
            <button class="btn btn btn-outline-primary" close-delete-cmt-modal>Cancel</button>
            <button class="btn btn-primary" confirm-delete-cmt-modal>Delete</button>
        </div>
    </div>
</div>
<!-- ======================= End Delete Comment Modal -->

<!-- ======================= Create Comment Modal -->
<div class="custom-modal" id="commentCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="create-custom-modal__content" data-content>
        <button type="button" class="close__modal" close-create-modal>
            <img src="/images/icons/cross-dark.svg" />
        </button>

        <div class="create-custom-modal__head">
            <div class="title">Create a Comment</div>
        </div>

        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'createCommentForm',
            'action' => ['comment/create', 'ticketId' => $model->id],
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="create-custom-modal__body">
            <div class="mb-2 field-description required" bis_skin_checked="1">
                <?= $form->field($commentModel, 'message')->textarea([
                    'rows' => 4,
                    'class' => 'form-control'
                ]) ?>
            </div>
            <div class="mb-4 field-attachment required" bis_skin_checked="1">
                <?= $form->field($commentModel, 'attachmentFiles[]')
                    ->fileInput([
                        'multiple' => true,
                        'class' => 'form-control'
                ]) ?>
            </div>
        </div>

        <div class="create-custom-modal__footer">
            <button type="button"
                    class="btn btn-outline-primary"
                    close-create-modal>
                Cancel
            </button>

            <?= \yii\helpers\Html::submitButton(
                'Submit',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
<!-- ======================= End Create Comment Modal -->


<!-- ======================= Assign Modal -->
<div class="custom-modal" id="assignCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="create-custom-modal__content" data-content>
        <button class="close__modal" close-assign-modal>
            <img src="/images/icons/cross-dark.svg" />
        </button>
        <div class="create-custom-modal__head">
            <div class="title">
                Assign
            </div>
        </div>

        <form id="assignForm">
            <?= \yii\helpers\Html::csrfMetaTags() ?>
            <div class="create-custom-modal__body">
                <div class="mb-2 field-assign required" bis_skin_checked="1">
                    <label class="col-form-label" for="assign">Assign To</label>
                    <select class="form-select" name="assignee_id">
                        <?php foreach (User::getAssignableUsers() as $key => $name): ?>
                            <option
                                value="<?= $key ?>"
                                <?= $key == ($model->assignee->id ?? null) ? 'selected' : '' ?>>
                                <?= Html::encode($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4 field-assign required" bis_skin_checked="1">
                    <label class="col-form-label" for="assign">Assign To</label>
                    <select class="form-select" name="status_id">
                        <?php foreach (Ticket::STATUS_TYPES as $key => $type): ?>
                            <option
                                value="<?= $key ?>"
                                <?= $key == $model->status_id ? 'selected' : '' ?>>
                                <?= $type ?>
                            </option>
                        <?php endforeach ?>

                    </select>
                </div>
            </div>

            <div class="create-custom-modal__footer">
                <button type="button" class="btn btn btn-outline-primary" close-assign-modal>Cancel</button>
                <button type="submit" class="btn btn-primary" close-assign-modal>Submit</button>
            </div>
        </form>
    </div>
</div>
<!-- ======================= End Assign Modal -->

<div class="site-detail_ticket" id="page_wrapper">
    <div class="site_id">
        Site ID: -
    </div>
    <h4><?= $model->subject ?> </h4>
    <div class="d-flex gap-3 action_btns">
        <a href="/ticket/update?id=<?= $model->id ?>" class="btn btn-outline-secondary">
            <img src="/images/icons/edit.svg" />
            Edit
        </a>
        <button class="btn btn-outline-danger" data-open-delete-modal>
            <img src="/images/icons/trash.svg" />
            Delete
        </button>
        <button class="btn btn-primary" data-open-assign-modal>Assign</button>
    </div>

    <div class="ticket-summary d-flex flex-wrap gap-4 mt-3">

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Category</div>
            <div class="ticket-summary__value fw-semibold"><?= $model->category->name ?></div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Status</div>
            <div class="ticket-summary__value">
                <span class="badge <?= strtolower(str_replace(' ', '_', Ticket::getStatusTypeById($model->status_id))) ?>"><?= Ticket::getStatusTypeById($model->status_id) ?></span>
            </div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Assigned To</div>
            <div class="ticket-summary__value"><?= $model->assignee->name ?></div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Created By</div>
            <div class="ticket-summary__value"><?= $model->creator->name ?></div>
        </div>

    </div>

    <div class="description__wrapper">
        <div class="title">
            Description
            <span class="line"></span>
        </div>
        <div class="description">
            <p>
                <?= $model->description ?>
            </p>
        </div>
    </div>

    <?php if (!empty($model->betting_number)): ?>
        <div class="betting_number__wrapper">
            <div class="title">
                <span>Betting Number</span>
                <span class="line"></span>
            </div>
            <div class="description">
                <p><?= $model->betting_number ?></p>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($model->betting_time_of_occurrence)): ?>
        <div class="betting_number__wrapper">
            <div class="title">
                <span>Betting Time Of Occurrence</span>
                <span class="line"></span>
            </div>
            <div class="description">
                <p><?= $model->betting_time_of_occurrence ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($model->hasFiles() && !empty($model->attachments)): ?>
        <div class="attachment__wrapper">
            <div class="title">
                Attachment
                <span class="line"></span>
            </div>

            <?php
            $images = [];
            $downloads = [];

            foreach ($model->attachments as $attachment) {
                $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    $images[] = $attachment;
                } else {
                    $downloads[] = $attachment;
                }
            }
            ?>

            <!-- ======= Image Attachments ======= -->
            <?php if (!empty($images)): ?>
                <div class="attachment-images d-flex flex-wrap gap-2 mb-3">
                    <?php foreach ($images as $img): ?>
                        <div class="attachment-image">
                            <a href="<?=  $img->file_path ?>" data-fancybox="gallery">
                                <img src="<?=  $img->file_path ?>" 
                                    alt="<?= htmlspecialchars($img->file_name) ?>" 
                                    style="max-width:150px; max-height:150px; object-fit:cover; border-radius:4px;" />
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ======= Other Attachments (Download) ======= -->
            <?php if (!empty($downloads)): ?>
                <div class="attachment-downloads">
                    <?php foreach ($downloads as $file): ?>
                        <?= Html::a(
                            '<img src="/images/icons/link.svg" class="me-1"/> ' . Html::encode($file->file_path),
                                $file->file_path,
                            [
                                'class' => 'download_btn btn btn-outline-secondary btn-sm mb-1',
                                'download' => $file->file_path,
                                'title' => Yii::t('app', 'Download') . ' ' . $file->file_name,
                            ]
                        ) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <div class="comment__wrapper">
        <div class="title d-flex justify-content-between">
            <span>Comments</span>
            <button class="btn btn-primary" data-open-create-modal>Add Comment</button>
        </div>
        <?php if (empty($model->comments)): ?>
            <div class="text-center text-muted py-5 bg-light rounded">
                <i class="bi bi-chat-square-text" style="font-size: 3rem;"></i>
                <p class="mt-3 mb-0"><?= Yii::t('app', 'No comments yet. Be the first to comment!') ?></p>
            </div>
        <?php else: ?>
            <ul class="comment_list">
                <?php foreach ($model->comments as $index => $comment): ?>
                    <?php
                        // Separate attachments for this comment
                        $images = [];
                        $downloads = [];
                        if (!empty($comment->attachments)) {
                            foreach ($comment->attachments as $attachment) {
                                $ext = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                                    $images[] = $attachment;
                                } else {
                                    $downloads[] = $attachment;
                                }
                            }
                        }
                    ?>
                    <li id="comment-<?= $comment->id ?>" class="<?= strtolower($comment->creator->userTypeName ?? '') ?>">
                        <div class="head">
                            <span class="username"><?= Html::encode($comment->creator->name ?? 'Unknown') ?></span>
                            <span class="timestamp"><?= date('Y M d', strtotime($model['created_at'])) ?></span>
                        </div>
                        <div class="body">
                            <p><?= $comment->message ?> </p>
                        </div>
                        <button class="btn btn-outline-danger delete-cmt-btn" data-open-delete-cmt-modal data-cmtId="<?= $comment->id ?>">
                            <img src="/images/icons/trash.svg">
                        </button>

                        <!-- ======= Image Attachments ======= -->
                        <?php if (!empty($images)): ?>
                            <div class="comment-images mb-3">
                                <?php foreach ($images as $img): ?>
                                    <div class="comment-image mb-2">
                                        <a href="<?= $img->file_path ?>" data-fancybox="gallery">
                                            <img src="<?= $img->file_path ?>" 
                                                alt="<?= htmlspecialchars($img->file_name) ?>" 
                                                class="img-fluid" />
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- ======= Other Attachments (Download) ======= -->
                        <?php if (!empty($downloads)): ?>
                            <div class="comment-downloads">
                                <?php foreach ($downloads as $file): ?>
                                    <?= Html::a(
                                        '<img src="/images/icons/link.svg" class="me-1"/> ' . Html::encode($file->file_name),
                                        '/uploads/' . $file->file_name,
                                        [
                                            'class' => 'download_btn btn btn-outline-secondary btn-sm mb-1',
                                            'download' => $file->file_name,
                                            'title' => Yii::t('app', 'Download') . ' ' . $file->file_name,
                                        ]
                                    ) ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- <button class="btn btn-primary loadmore">Load More</button> -->
</div>

<script>
    $(document).on('click', '[data-open-delete-modal]', function() {
        $('#deleteCustomModal').fadeIn(200);
    });

    $(document).on('click', '[close-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
    });

    $(document).on('click', '[data-open-create-modal]', function() {
        $('#commentCustomModal').fadeIn(200);
    });

    $(document).on('click', '[close-create-modal]', function() {
        $('#commentCustomModal').fadeOut(200);
    });

    $(document).on('click', '[data-open-assign-modal]', function() {
        $('#assignCustomModal').fadeIn(200);
    });

    $(document).on('click', '[close-assign-modal]', function() {
        $('#assignCustomModal').fadeOut(200);
    });

    let deleteCommentId = null;

    $(document).on('click', '[data-open-delete-cmt-modal]', function() {
        deleteCommentId = $(this).data('cmtid'); 
        $('#deleteCmtCustomModal').fadeIn(200);
    });

    $(document).on('click', '[close-delete-cmt-modal]', function() {
        $('#deleteCmtCustomModal').fadeOut(200);
    });

    Fancybox.bind("[data-fancybox]");

    let deleteTicketId = "<?= $model->id ?>";

    $(document).on('click', '[confirm-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
        $.ajax({
            url: '/ticket/delete?id=' + deleteTicketId,
            type: 'POST',
            data: {
                _csrf: yii.getCsrfToken()
            },
            success: function() {},
            error: function(err) {}
        });
    });

    $(document).on('click', '[confirm-delete-cmt-modal]', function() {
        $('#deleteCmtCustomModal').fadeOut(200);

        $.ajax({
            url: '/comment/delete?id=' + deleteCommentId,
            type: 'POST',
            data: {
                _csrf: yii.getCsrfToken()
            },
            success: function(res) {
                if (res.success) {
                    $('#comment-' + deleteCommentId).fadeOut(200, function() { $(this).remove(); });
                } else {
                    alert('Failed to delete comment.');
                }
            },
            error: function() {
                alert('Failed to delete comment.');
            }
        });
    });


    $('#assignForm').on('submit', function(e) {
        e.preventDefault();

        $.post(
            '<?= \yii\helpers\Url::to(['ticket/assignee-update', 'id' => $model->id]) ?>',
            $(this).serialize(),
            function(res) {
                if (res.success) {
                    location.reload();
                }
            }
        );
    });
</script>