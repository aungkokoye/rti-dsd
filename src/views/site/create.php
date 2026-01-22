<?php

use yii\helpers\Html;
?>

<div class="site-user__create_ticket" id="page_wrapper">
    <h4>Create a Ticket</h4>
    <div>
        <div class="mb-3 field-ticket-title required" bis_skin_checked="1">
            <label class="col-form-label" for="ticket-title">Title</label>
            <input type="text" value="" id="ticket-title" class="col-lg-3 form-control">
        </div>

        <div class="mb-3 field-ticket-description required" bis_skin_checked="1">
            <label class="col-form-label" for="ticket-description">Description</label>
            <input type="text" value="" id="ticket-description" class="col-lg-3 form-control">
        </div>

        <div class="mb-3 field-ticket-category required" bis_skin_checked="1">
            <label class="col-form-label" for="ticket-category">Category</label>
            <select class="form-select">
                <option selected>Design Issue</option>
                <option value="admin">Gaming API</option>
            </select>
        </div>

        <div class="mb-3 field-ticket-attachment required" bis_skin_checked="1">
            <label class="col-form-label" for="ticket-attachment">Attachment</label>
            <input type="file" class="form-control"/>
        </div>

        <div class="form-group d-flex flex-column align-items-center flex-sm-row justify-content-center gap-3">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'edit-button', 'data' => ['open-update-modal' => 'true']]) ?>
            <?= Html::a(
                'Cancel',
                '/',
                [
                    'class' => 'btn btn-outline-primary',
                    'name' => 'cancel-button',
                ]
            ) ?>
        </div>

    </div>
</div>

<script>

    $(document).on('click', '[data-open-reset-pwd-modal]', function() {
        $('.success-custom-modal__body p').text("Reset password request sent successfully.")
        $('#successCustomModal').fadeIn(200);
    });

    $(document).on('click', '[data-open-update-modal]', function() {
        $('.success-custom-modal__body p').text("You've successfully created a ticket.")
        $('#successCustomModal').fadeIn(200);
    });

    $(document).on('click', '.success-custom-modal__content .btn-primary', function() {
        $('#successCustomModal').fadeOut(200);
    });
</script>