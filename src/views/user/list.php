<!-- Custom Modal -->
<div class="custom-modal" id="deleteCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="delete-custom-modal__content" data-content>

        <div class="delete-custom-modal__body">
            <p>Are you sure you want to delete this user?</p>
        </div>

        <div class="delete-custom-modal__footer">
            <button class="btn btn btn-outline-primary" close-delete-modal>Cancel</button>
            <button class="btn btn-primary" close-delete-modal>Delete</button>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="custom-modal" id="successCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="success-custom-modal__content" data-content>

        <div class="success-custom-modal__body">
            <img src="/images/icons/success.svg" alt="Success Icon" />
            <p>Reset password request sent successfully.</p>
        </div>

        <div class="success-custom-modal__footer">
            <button class="btn btn-primary" data-close-success-modal>OK</button>
        </div>
    </div>
</div>

<div class="site-user__list" id="page_wrapper">


    <a href="/user/create" class="btn btn-primary create_btn">+ Create a User</a>

    <div class="flex-table-container">
        <!-- Table Header -->
        <div class="flex-table-header">
            <div class="flex-table-cell cell-user-id">ID</div>
            <div class="flex-table-cell cell-email">Email</div>
            <div class="flex-table-cell cell-username">Username</div>
            <div class="flex-table-cell cell-role">Role</div>
            <div class="flex-table-cell cell-status">Status</div>
            <div class="flex-table-cell cell-created-date">Created Date</div>
            <div class="flex-table-cell cell-action"></div>
        </div>

        <!-- Table Body -->
        <div class="flex-table-body">
            <?php
            $statuses = ['active', 'inactive', 'disabled'];
            $ticketId = 1;

            foreach ($statuses as $status) : ?>
                <div class="flex-table-row">
                    <div class="flex-table-cell cell-user-id"><?= $ticketId++ ?></div>
                    <div class="flex-table-cell cell-email"><a  href="/user/edit">test@gmail.com</a></div>
                    <div class="flex-table-cell cell-username">User<?= $ticketId ?></div>
                    <div class="flex-table-cell cell-role">Designer</div>
                    <div class="flex-table-cell cell-status">
                        <span class="status-chip <?= $status ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                    </div>
                    <div class="flex-table-cell cell-created-date">2026 Jan 19</div>
                    <div class="flex-table-cell cell-action">
                        <div class="dropdown" data-bs-auto-close="outside">
                            <img src="/images/icons/three_dots.svg" alt="More Icon" class="dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;" />
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/user/edit">Edit</a></li>
                                <li><a class="dropdown-item" href="javascript:;" data-open-success-modal>Reset Password</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:;" data-open-delete-modal>Delete</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <div class="pagination-container mt-3" id="pagination">
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
    </div>

</div>



<script>
    $(document).ready(function() {

        function updateFilterChips() {
            const $chipsContainer = $('.filter__value_chips');
            $chipsContainer.empty();

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
        }

        $(document).on('click', '.chip_value .remove', function() {
            const $chip = $(this).closest('.chip_value');
            const name = $chip.data('name');

            $(`.dropdown select[name="${name}"]`).val('');
            $chip.remove();

            applyFilters();
        });

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

        function applyFilters() {
            const searchQuery = $('.filter-bar-right input[name="search"]').val().trim();

            // Collect all filter dropdown values
            let filterData = {};
            $('.dropdown').each(function(index) {
                $(this).find('select').each(function(sIndex) {
                    const name = $(this).attr('name');
                    filterData[name] = $(this).val();
                });
            });

            filterData.search = searchQuery;

            updateFilterChips();

            console.log('filterData ', filterData)

            $.ajax({
                url: '/test',
                type: 'POST',
                data: filterData,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#results-container').html(response);
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        // Debounced search input
        $('.filter-bar-right input[name="search"]').on('input', debounce(applyFilters, 500));

        // Dropdown selects change
        $('.dropdown select').on('change', applyFilters);

        // Apply buttons inside dropdown
        $('.dropdown .btn-primary, .dropdown .btn-secondary').on('click', applyFilters);

        // Clear filter buttons
        $('.dropdown .clear-filter-btn').on('click', function() {
            $(this).closest('.dropdown-menu').find('select').prop('selectedIndex', 0);
            applyFilters();
        });

    });


    $(document).on('click', '[data-open-delete-modal]', function() {
        $('#deleteCustomModal').fadeIn(200);
    });

    $(document).on('click', '[close-delete-modal]', function() {
        $('#deleteCustomModal').fadeOut(200);
    });

    $(document).on('click', '[data-open-success-modal]', function() {
        $('#successCustomModal').fadeIn(200);
    });

    $(document).on('click', '[data-close-success-modal]', function() {
        $('#successCustomModal').fadeOut(200);
    });
</script>