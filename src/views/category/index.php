<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Custom Modal -->
<div class="custom-modal" id="deleteCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="delete-custom-modal__content" data-content>

        <div class="delete-custom-modal__body">
            <p>Are you sure you want to delete this category?</p>
        </div>

        <div class="delete-custom-modal__footer">
            <button class="btn btn btn-outline-primary" close-delete-modal>Cancel</button>
            <button class="btn btn-primary" confirm-delete-modal>Delete</button>
        </div>
    </div>
</div>


<div class="site-category__list" id="page_wrapper">

    <a href="/category/create" class="btn btn-primary create_btn">+ Create Category</a>

    <div class="flex-table-container">
        <!-- Table Header -->
        <div class="flex-table-header">
            <div class="flex-table-cell cell-ticket-id">ID</div>
            <div class="flex-table-cell cell-title">Name</div>
            <div class="flex-table-cell cell-created-date">Created At</div>
            <div class="flex-table-cell cell-created-date">Created At</div>
            <div class="flex-table-cell cell-action"></div>
        </div>

        <!-- Table Body -->
        <div class="flex-table-body">
            <?php foreach ($dataProvider->models as $model) : ?>
                <div class="flex-table-row">
                    <div class="flex-table-cell cell-ticket-id"><?= $model['id'] ?></div>
                    <div class="flex-table-cell cell-title">
                        <?= Html::a($model['name'], ['category/update', 'id' => $model->id]) ?>
                    </div>
                    <div class="flex-table-cell cell-created-date"><?= date('Y M d', strtotime($model['created_at'])) ?> </div>
                    <div class="flex-table-cell cell-created-date"><?= date('Y M d', strtotime($model['updated_at'])) ?> </div>
                    <div class="dropdown" data-bs-auto-close="outside">
                        <img src="/images/icons/three_dots.svg" alt="More Icon" class="dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;" />
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/category/update?id=<?= $model['id'] ?>">Edit</a></li>
                            <li>
                                <a class="dropdown-item text-danger delete-ticket-btn"
                                    href="javascript:;"
                                    data-id="<?= $model['id'] ?>" data-open-delete-modal>
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

</div>

<script>
    let deleteCategoryId = "";
    $(document).on('click', '[data-open-delete-modal]', function() {
        $('#deleteCustomModal').fadeIn(200);
        deleteCategoryId = $(this).data('id');
    });

    $(document).on('click', '[close-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
    });

    $(document).on('click', '[confirm-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
        $.ajax({
            url: '/category/delete?id=' + deleteCategoryId,
            type: 'POST',
            data: {
                _csrf: yii.getCsrfToken()
            },
            success: function() {},
            error: function(err) {}
        });
    });
</script>