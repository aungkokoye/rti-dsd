<?php

use app\controllers\TicketController;
use app\models\Ticket;
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tickets');

?>

<!-- Custom Modal -->
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


<div class="filter-bar mb-3">
    <div class="filter-bar__inner d-flex flex-wrap justify-content-between align-items-center">
        <!-- Left side: Filter, Sort, Reset -->
        <div class="filter-bar-left d-flex flex-wrap gap-2 align-items-center">

            <div class="dropdown" data-bs-auto-close="outside">
                <button class="btn dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="/images/icons/filter.svg" alt="Filter Icon" />
                    Filter
                </button>
                <div class="dropdown-menu dropdown-menu-width p-2" aria-labelledby="filterDropdown">
                    <select class="form-select mb-2" name="TicketSearch[category_id]">
                        <option value="">Select Category</option>
                        <?php foreach (Category::getDropdownList() as $key => $category): ?>
                            <option value="<?= $key ?>"><?= $category ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-select mb-2" name="TicketSearch[status_id]">
                        <option value="">Select Status</option>
                        <?php foreach (Ticket::STATUS_TYPES as $key => $type): ?>
                            <option value="<?= $key ?>"><?= $type ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= DatePicker::widget([
                        'name' => 'TicketSearch[created_at_from]',
                        'value' => '',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'id' => 'created_at_from',
                            'class' => 'form-control mb-2 filter-date',
                            'placeholder' => 'Ticket Created From',
                        ],
                    ]); ?>
                    <?= DatePicker::widget([
                        'name' => 'TicketSearch[created_at_to]',
                        'value' => '',
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'id' => 'created_at_to',
                            'class' => 'form-control mb-2 filter-date',
                            'placeholder' => 'Ticket Created To',
                        ],
                    ]); ?>
                    <div class="bottom_filter__wrapper">
                        <button class="clear-filter-btn">Clear Filter</button>
                        <button class="close-dropdown-btn">Close</button>
                    </div>
                </div>
            </div>

            <!-- Sort Button with Dropdown -->
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="/images/icons/sort.svg" alt="Sort Icon" />
                    Sort
                </button>
                <div class="dropdown-menu dropdown-menu-width p-2" aria-labelledby="filterDropdown">
                    <select class="form-select mb-2" name="sort">
                        <option value="">Select Sorting</option>
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <div class="bottom_filter__wrapper">
                        <button class="clear-filter-btn">Clear Filter</button>
                        <button class="close-dropdown-btn">Close</button>
                    </div>
                </div>
            </div>
            <!-- Reset Button -->
            <button type="button" class="reset_btn btn">
                Reset
            </button>

        </div>


        <!-- Right side: Search -->
        <div class="filter-bar-right">
            <img src="/images/icons/search.svg" alt="Search Icon" />
            <input type="text" name="TicketSearch[subject]" class="form-control" placeholder="Search by title or description" />
        </div>
    </div>
</div>

<div class="site-ticket__list" id="page_wrapper">

    <div class="filter__value_chips"></div>

    <a href="/ticket/create" class="btn btn-primary create_btn">+ Create a Ticket</a>
    <div class="flex-table-container">
        <!-- Table Header -->
        <div class="flex-table-header">
            <div class="flex-table-cell cell-ticket-id">Ticket ID</div>
            <div class="flex-table-cell cell-title">Title</div>
            <div class="flex-table-cell cell-category">Category</div>
            <div class="flex-table-cell cell-created-by">Created By</div>
            <div class="flex-table-cell cell-site-id">Site ID</div>
            <div class="flex-table-cell cell-status">Status</div>
            <div class="flex-table-cell cell-assigned-to">Assigned To</div>
            <div class="flex-table-cell cell-created-date">Created Date</div>
            <div class="flex-table-cell cell-action"></div>
        </div>

        <!-- Table Body -->
        <div class="flex-table-body">
            <?php

            foreach ($dataProvider->models as $model) : ?>
                <div class="flex-table-row">
                    <div class="flex-table-cell cell-ticket-id"><?= $model['id'] ?></div>
                    <div class="flex-table-cell cell-title">
                        <?= Html::a($model['subject'], ['ticket/view', 'id' => $model->id]) ?>
                    </div>
                    <div class="flex-table-cell cell-category"><?= $model->category->name ?></div>
                    <div class="flex-table-cell cell-created-by"><?= $model->creator->name ?></div>
                    <div class="flex-table-cell cell-site-id">-</div>
                    <div class="flex-table-cell cell-status">
                        <span class="status-chip <?= strtolower(str_replace(' ', '_', $model->getStatusName())) ?>"><?= $model->getStatusName() ?></span>
                    </div>
                    <div class="flex-table-cell cell-assigned-to"><?= $model->assignee->name ?></div>
                    <div class="flex-table-cell cell-created-date"><?= date('Y M d', strtotime($model['created_at'])) ?> </div>
                    <div class="flex-table-cell cell-action">
                        <div class="dropdown" data-bs-auto-close="outside">
                            <img src="/images/icons/three_dots.svg" alt="More Icon" class="dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;" />
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/ticket/view?id=<?= $model['id'] ?>">View</a></li>
                                <li><a class="dropdown-item" href="/ticket/update?id=<?= $model['id'] ?>">Edit</a></li>
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

    <?php if ($dataProvider->pagination->pageCount > 1): ?>
        <div class="pagination-container mt-3">
            <?php
            $pagination = $dataProvider->pagination;
            $currentPage = $pagination->page; // zero-based
            $pageCount = $pagination->pageCount;

            $queryParams = Yii::$app->request->getQueryParams();

            for ($i = 0; $i < $pageCount; $i++):
                $queryParams['page'] = $i; // Add page param for GET
                $url = '?' . http_build_query($queryParams);
                $activeClass = $i === $currentPage ? 'active' : '';
            ?>
                <a href="<?= $url ?>" class="page-btn <?= $activeClass ?>"><?= $i + 1 ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>



<script>
    $(document).ready(function() {

        // ----------------- Helpers -----------------
        function debounce(func, delay = 500) {
            let timeoutId;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeoutId);
                timeoutId = setTimeout(function() {
                    func.apply(context, args);
                }, delay);
            };
        }

        // ----------------- Filter Chips -----------------
        function updateFilterChips() {
            const $chipsContainer = $('.filter__value_chips');
            $chipsContainer.empty();

            // Dropdowns
            $('.dropdown select').each(function() {
                const val = $(this).val();
                const name = $(this).attr('name');
                const label = $(this).find('option:selected').text();

                if (val) {
                    const $chip = $(`
                    <div class="chip_value" data-name="${name}">
                        <span class="remove"><img src="/images/icons/cross.svg" alt="Remove" /></span>
                        ${label}
                    </div>
                `);
                    $chipsContainer.append($chip);
                }
            });

            // Datepickers
            $('.filter-date').each(function() {
                const val = $(this).val();
                const name = $(this).attr('name'); // e.g., TicketSearch[created_at_from]
                if (val) {
                    const label = name.includes('from') ? 'From: ' + val : 'To: ' + val;
                    const $chip = $(`
                    <div class="chip_value" data-name="${name}">
                        <span class="remove"><img src="/images/icons/cross.svg" alt="Remove" /></span>
                        ${label}
                    </div>
                `);
                    $chipsContainer.append($chip);
                }
            });

            // Search input
            const searchInput = $('input[name="TicketSearch[subject]"]');
            if (searchInput.length && searchInput.val().trim()) {
                const $chip = $(`
                <div class="chip_value" data-name="TicketSearch[subject]">
                    <span class="remove"><img src="/images/icons/cross.svg" alt="Remove" /></span>
                    ${searchInput.val().trim()}
                </div>
            `);
                $chipsContainer.append($chip);
            }
        }

        // Remove chip
        $(document).on('click', '.chip_value .remove', function() {
            const $chip = $(this).closest('.chip_value');
            const name = $chip.data('name');

            $(`[name="${name}"]`).val('');
            $chip.remove();
            applyFilters();
        });

        // ----------------- Apply Filters -----------------
        function applyFilters() {
            const filterData = {};

            // Serialize inputs with TicketSearch[] format
            $('input[name^="TicketSearch"], .dropdown select, .filter-date').each(function() {
                const name = $(this).attr('name');
                filterData[name] = $(this).val() || '';
            });

            updateFilterChips();

            // Build URL with proper Yii2 format
            const queryParams = $.param(filterData); // automatically encodes like TicketSearch%5Bfield%5D=value
            const newUrl = window.location.pathname + '?' + queryParams;

            // Reload page with new URL
            window.location.href = newUrl;
        }

        // ----------------- Populate Inputs from URL on Page Load -----------------
        const urlParams = new URLSearchParams(window.location.search);
        $('input[name^="TicketSearch"], .dropdown select, .filter-date').each(function() {
            const name = $(this).attr('name');
            if (urlParams.has(name)) {
                $(this).val(urlParams.get(name));
            }
        });

        // Create chips for existing values
        updateFilterChips();

        // ----------------- Event Listeners -----------------
        $('input[name^="TicketSearch"]').on('input', debounce(applyFilters, 500));
        $('.dropdown select').on('change', applyFilters);
        $(document).on('change', '.filter-date', applyFilters);
        $('.dropdown .btn-primary, .dropdown .btn-secondary').on('click', applyFilters);
        $('.dropdown .clear-filter-btn').on('click', function() {
            $(this).closest('.dropdown-menu').find('select').prop('selectedIndex', 0);
            applyFilters();
        });
        $('.reset_btn').on('click', function() {
            $('input[name^="TicketSearch"]').val('');
            $('.dropdown select').prop('selectedIndex', 0);
            $('.filter-date').val('');
            $('.filter__value_chips').empty();
            applyFilters();

            const cleanUrl = window.location.pathname;
            window.location.href = cleanUrl;
        });

    let deleteTicketId = "";
    $(document).on('click', '[data-open-delete-modal]', function() {
        $('#deleteCustomModal').fadeIn(200);
        deleteTicketId =$(this).data('id');
    });

    $(document).on('click', '[close-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
    }); 

    $(document).on('click', '[confirm-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
        $.ajax({
            url: '/ticket/delete?id=' + deleteTicketId,
            type: 'POST', 
            data: {
                _csrf: yii.getCsrfToken()
            },
            success: function() {
            },
            error: function(err) {
            }
        });
    }); 

    });
</script>