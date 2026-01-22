<div id="delete-modal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-icon">
            <i class="fa fa-trash"></i>
        </div>

        <h3>Delete material?</h3>
        <p>This action cannot be undone.</p>

        <div class="delete-actions">
            <button
                id="cancel-delete"
                class="btn-secondary"
                onclick="closeDeleteModal()">
                Cancel
            </button>

            <button
                id="confirm-delete"
                class="btn-danger"
                onclick="confirmDelete()">
                Delete
            </button>
        </div>
    </div>
</div>