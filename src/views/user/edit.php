<?php

use yii\helpers\Html;
?>
<!-- Custom Modal -->
<div class="custom-modal" id="successCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="success-custom-modal__content" data-content>

        <div class="success-custom-modal__body">
            <img src="/images/icons/success.svg" alt="Success Icon" />
            <p>Reset password request sent successfully.</p>
        </div>

        <div class="success-custom-modal__footer">
            <button class="btn btn-primary">OK</button>
        </div>
    </div>
</div>


<div class="site-user__profile" id="page_wrapper">
    <h4>Edit User</h4>
    <div>
        <div class="mb-3 field-profile-username required" bis_skin_checked="1">
            <label class="col-form-label" for="profile-username">Username</label>
            <input type="text" value="Denny" id="profile-username" class="col-lg-3 form-control">
        </div>

        <div class="mb-3 field-profile-email required" bis_skin_checked="1">
            <label class="col-form-label" for="profile-email">Email</label>
            <input type="text" value="denny123@gmail.com" id="profile-email" class="col-lg-3 form-control">
        </div>

        <div class="mb-3 field-profile-role required" bis_skin_checked="1">
            <label class="col-form-label" for="profile-role">Role</label>
            <select class="form-select">
                <option selected>Designer</option>
                <option value="admin">Admin</option>
                <option value="developer">Developer</option>
                <option value="developer">Designer</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="form-group d-flex flex-column align-items-center flex-sm-row justify-content-center gap-3">
            <?= Html::submitButton('Edit', ['class' => 'btn btn-primary', 'name' => 'edit-button', 'data' => ['open-update-modal' => 'true']]) ?>
            <?= Html::submitButton('Reset Password', ['class' => 'btn btn-success', 'name' => 'reset-pwd-button', 'data' => ['open-reset-pwd-modal' => 'true']]) ?>
            <?= Html::a(
                'Cancel',
                'list',
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
        $('.success-custom-modal__body p').text("You've successfully updated this user.")
        $('#successCustomModal').fadeIn(200);
    });

    $(document).on('click', '.success-custom-modal__content .btn-primary', function() {
        $('#successCustomModal').fadeOut(200);
    });
</script>