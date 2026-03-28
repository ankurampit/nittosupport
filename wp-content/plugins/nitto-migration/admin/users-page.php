<?php

if (!defined('ABSPATH')) {
    exit;
}

function nitto_migration_users_page()
{


?>

    <div class="nitto-section">
        <div class="nitto-action-bar">
            <button id="nitto-load-users" class="button button-primary">
                <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                Load Users From Source
            </button>
        </div>

        <h2>Fetched Users</h2>

        <div id="nitto-users-list">
            <div class="nitto-empty-state">
                <span class="dashicons dashicons-admin-users"></span>
                <p>No users loaded yet. Click the button above to begin.</p>
            </div>
        </div>
    </div>

<?php
}
