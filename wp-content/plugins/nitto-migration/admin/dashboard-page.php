<?php
if (!defined('ABSPATH')) {
    exit;
}

function nitto_migration_dashboard_page()
{
?>
    <div class="wrap nitto-dashboard-wrapper">
        <header class="nitto-header">
            <h1>Nitto Migration <span class="version-tag">v2.0</span></h1>
            <p>Transfer your data seamlessly between platforms.</p>
        </header>

        <div class="nitto-grid">
            <div class="nitto-card">
                <div class="card-icon icon-products">📦</div>
                <div class="card-content">
                    <h2>Products</h2>
                    <p>Sync your catalog from Magento or Laravel into WooCommerce.</p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url('admin.php?page=nitto-migration-products'); ?>" class="nitto-btn">
                        Launch Migration
                    </a>
                </div>
            </div>

            <div class="nitto-card">
                <div class="card-icon icon-products">📦</div>
                <div class="card-content">
                    <h2>Products of Point of Purchase</h2>
                    <p>Sync your catalog from Magento or Laravel into WooCommerce.</p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url('admin.php?page=nitto-migration-products-pos'); ?>" class="nitto-btn">
                        Launch Migration
                    </a>
                </div>
            </div>

            <div class="nitto-card">
                <div class="card-icon icon-customers">👥</div>
                <div class="card-content">
                    <h2>Users</h2>
                    <p>Migrate user profiles, addresses, and account metadata.</p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url('admin.php?page=nitto-migration-users'); ?>"
                        class="button button-primary">
                        Migrate Users
                    </a>
                </div>
            </div>

            <div class="nitto-card">
                <div class="card-icon icon-orders">📜</div>
                <div class="card-content">
                    <div class="title-row">
                        <h2>Promo Orders</h2>
                        <span class="badge">Soon</span>
                    </div>
                    <p>Historical order data and transaction records migration.</p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url('admin.php?page=nitto-migration-orders'); ?>"
                        class="button button-primary">
                        Migrate Orders
                    </a>
                </div>
            </div>
            
            <div class="nitto-card">
                <div class="card-icon icon-orders">📜</div>
                <div class="card-content">
                    <div class="title-row">
                        <h2>POS Orders</h2>
                        <span class="badge">Soon</span>
                    </div>
                    <p>Historical order data and transaction records migration.</p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url('admin.php?page=nitto-migration-pos-orders'); ?>"
                        class="button button-primary">
                        Migrate Orders
                    </a>
                </div>
            </div>

            
        </div>
    </div>
<?php
}
