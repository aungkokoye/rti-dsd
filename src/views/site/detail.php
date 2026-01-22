<!-- ======================= Delete Modal -->
<div class="custom-modal" id="deleteCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>
    
    <div class="delete-custom-modal__content" data-content>
        
        <div class="delete-custom-modal__body">
            <p>Are you sure you want to delete this ticket?</p>
        </div>
        
        <div class="delete-custom-modal__footer">
            <button class="btn btn btn-outline-primary" close-delete-modal>Cancel</button>
            <button class="btn btn-primary" close-delete-modal>Delete</button>
        </div>
    </div>
</div>
<!-- ======================= End Delete Modal -->

<!-- ======================= Create Comment Modal -->
<div class="custom-modal" id="commentCustomModal" style="display:none;">
    <div class="custom-modal__overlay"></div>

    <div class="create-custom-modal__content" data-content>
        <button class="close__modal" close-create-modal> 
            <img src="/images/icons/cross-dark.svg" /> 
        </button>
        <div class="create-custom-modal__head">
            <div class="title">
                Create a Comment
            </div>
        </div>

        <form id="createCommentForm" enctype="multipart/form-data">
            <div class="create-custom-modal__body">
                <div class="mb-2 field-description required" bis_skin_checked="1">
                    <label class="col-form-label" for="description">Description</label>
                    <input type="text" value="" id="description" class="col-lg-3 form-control">
                </div>
                <div class="mb-4 field-attachment required" bis_skin_checked="1">
                    <label class="col-form-label" for="attachment">Attachment</label>
                    <input type="file" value="" id="attachment" class="col-lg-3 form-control">
                </div>
            </div>

            <div class="create-custom-modal__footer">
                <button class="btn btn btn-outline-primary" close-create-modal>Cancel</button>
                <button class="btn btn-primary" close-create-modal>Submit</button>
            </div>
        </form>
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
            <div class="create-custom-modal__body">
                <div class="mb-2 field-assign required" bis_skin_checked="1">
                    <label class="col-form-label" for="assign">Assign To</label>
                    <select class="form-select">
                        <option selected>Designer</option>
                        <option value="admin">Developer</option>
                    </select>
                </div>
                <div class="mb-4 field-assign required" bis_skin_checked="1">
                    <label class="col-form-label" for="assign">Assign To</label>
                    <select class="form-select">
                        <option selected value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="create-custom-modal__footer">
                <button class="btn btn btn-outline-primary" close-assign-modal>Cancel</button>
                <button class="btn btn-primary" close-assign-modal>Submit</button>
            </div>
        </form>
    </div>
</div>
<!-- ======================= End Assign Modal -->

<div class="site-detail_ticket" id="page_wrapper">
    <div class="site_id">
        Site ID: 1101
    </div>
    <h4>Image is not exposed</h4>
    <div class="d-flex gap-3 action_btns">
        <a href="/site/edit" class="btn btn-outline-secondary">
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
            <div class="ticket-summary__label text-muted small">Ticket ID</div>
            <div class="ticket-summary__value fw-semibold">#12345</div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Status</div>
            <div class="ticket-summary__value">
                <span class="badge open">Open</span>
            </div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Assigned To</div>
            <div class="ticket-summary__value">John Doe</div>
        </div>

        <div class="ticket-summary__item d-flex flex-column">
            <div class="ticket-summary__label text-muted small">Created By</div>
            <div class="ticket-summary__value">Jane Smith</div>
        </div>

    </div>

    <div class="description__wrapper">
        <div class="title">
            Description 
            <span class="line"></span>
        </div>
        <div class="description">
            <p>
                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
            </p>
        </div>
    </div>

    <div class="attachment__wrapper">
        <div class="title">
            Attachment 
            <span class="line"></span>
        </div>
        <div class="attachment">
            <img src="/images/sample.png" />
        </div>
    </div>

    <div class="comment__wrapper">
        <div class="title d-flex justify-content-between">
            <span>Comments</span>
            <button class="btn btn-primary" data-open-create-modal>Add Comment</button>
        </div>
        <ul class="comment_list">
            <li class="user">
                <div class="head">
                    <span class="username">Client</span>
                    <span class="timestamp">2026 Jan 19</span>
                </div>
                <div class="body">
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its </p>
                </div>
                <a href="#" class="download_btn">
                    <img src="/images/icons/link.svg" /> Download
                </a>
            </li>
            <li class="developer">
                <div class="head">
                    <span class="username">Client</span>
                    <span class="timestamp">2026 Jan 19</span>
                </div>
                <div class="body">
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its </p>
                </div>
                <a href="#" class="download_btn">
                    <img src="/images/icons/link.svg" /> Download
                </a>
            </li>
            <li class="admin">
                <div class="head">
                    <span class="username">Client</span>
                    <span class="timestamp">2026 Jan 19</span>
                </div>
                <div class="body">
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its </p>
                </div>
                <a href="#" class="download_btn">
                    <img src="/images/icons/link.svg" /> Download
                </a>
            </li>
        </ul>
    </div>

    <button class="btn btn-primary loadmore">Load More</button>
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
</script>