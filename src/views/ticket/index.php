<?php

use app\controllers\TicketController;
use app\models\Ticket;
use app\models\Category;
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