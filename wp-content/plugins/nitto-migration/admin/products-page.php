<?php

if (!defined('ABSPATH')) {
    exit;
}

function nitto_migration_products_page()
{

?>

    <div class="nitto-section">
        <div class="nitto-action-bar">
            <button id="nitto-load-products" class="button button-primary" onclick="fetchProduct()">
                <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                Load Products From Source
            </button>
        </div>

        <h2>Fetched Products</h2>

        <div id="nitto-products-list">
            <div class="nitto-empty-state">
                <span class="dashicons dashicons-cart"></span>
                <p>No products loaded yet. Click the button above to begin.</p>
            </div>
        </div>
    </div>

<?php
}
