<?php
add_action('wp_ajax_nitto_fetch_pos_orders', 'nitto_fetch_pos_orders');

function nitto_fetch_pos_orders()
{
    $external_db = nitto_magento_pos_db();

    if (!$external_db) {
        wp_die('<p>Magento DB connection failed</p>');
    }

    // 🔥 Fetch Orders
    $orders = $external_db->get_results("
        SELECT 
            o.entity_id,
            o.increment_id,
            o.created_at,
            o.status,
            o.state,

            o.customer_email,
            o.customer_firstname,
            o.customer_lastname,

            o.grand_total,
            o.subtotal,
            o.total_qty_ordered,
            o.total_paid,

            pay.method AS payment_method,

            bill.city AS billing_city,
            bill.country_id AS billing_country,

            ship.city AS shipping_city,
            ship.country_id AS shipping_country,

            GROUP_CONCAT(
                DISTINCT CONCAT(oi.name, ' (', oi.qty_ordered, ')')
                SEPARATOR ', '
            ) AS items

        FROM sales_order o

        LEFT JOIN sales_order_payment pay 
            ON pay.parent_id = o.entity_id

        LEFT JOIN sales_order_address bill 
            ON bill.parent_id = o.entity_id 
            AND bill.address_type = 'billing'

        LEFT JOIN sales_order_address ship 
            ON ship.parent_id = o.entity_id 
            AND ship.address_type = 'shipping'

        LEFT JOIN sales_order_item oi 
            ON oi.order_id = o.entity_id 
            AND oi.parent_item_id IS NULL

        GROUP BY o.entity_id

        ORDER BY o.created_at DESC
        LIMIT 200
    ");

    if (!$orders) {
        wp_die('<p>No orders found</p>');
    }

    // 🔹 Status label helper (optional refinement)
    function formatOrderStatus($status)
    {
        return ucfirst(str_replace('_', ' ', $status));
    }

    ob_start();
?>

    <div class="nitto-table-container">
        <h2>Magento Orders</h2>

        <button onclick="startMigration()">Migrate orders</button>
        <div id="migration-status"></div>
        <table class="nitto-modern-table">

            <thead>
                <tr>
                    <th>Sl no.</th>
                    <th>ID</th>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Billing</th>
                    <th>Shipping</th>
                    <th>Items</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($orders as $o): ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= esc_html($o->entity_id); ?></td>
                        <td><?= esc_html($o->increment_id); ?></td>
                        <td><?= esc_html($o->created_at); ?></td>

                        <td>
                            <?= esc_html($o->customer_firstname . ' ' . $o->customer_lastname); ?>
                        </td>

                        <td><?= esc_html($o->customer_email); ?></td>

                        <td><?= esc_html($o->grand_total); ?></td>
                        <td><?= esc_html($o->total_paid); ?></td>
                        <td><?= esc_html($o->total_qty_ordered); ?></td>

                        <td><?= esc_html(formatOrderStatus($o->status)); ?></td>

                        <td><?= esc_html($o->payment_method); ?></td>

                        <td>
                            <?= esc_html($o->billing_city . ', ' . $o->billing_country); ?>
                        </td>

                        <td>
                            <?= esc_html($o->shipping_city . ', ' . $o->shipping_country); ?>
                        </td>

                        <td style="max-width:300px;">
                            <?= esc_html($o->items); ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                endforeach; ?>
            </tbody>

        </table>
    </div>

<?php

    echo ob_get_clean();
    wp_die();
}

add_action('wp_ajax_nitto_migrate_pos_orders_batch', 'nitto_migrate_pos_orders_batch');
function nitto_migrate_pos_orders_batch()
{
    $offset = intval($_POST['offset']);
    $limit  = intval($_POST['limit']);

    $external_db = nitto_magento_pos_db();

    if (!$external_db) {
        wp_send_json_error('DB connection failed');
    }


    switch_to_blog(2);

    $orders = $external_db->get_results("
        SELECT *
        FROM sales_order
        ORDER BY entity_id ASC
        LIMIT $limit OFFSET $offset
    ");

    if (!$orders) {
        restore_current_blog();
        wp_send_json_success(['has_more' => false]);
    }

    global $wpdb;

    foreach ($orders as $o) {


        $existing = wc_get_orders([
            'meta_key'   => 'old_order_id',
            'meta_value' => $o->entity_id,
            'limit'      => 1
        ]);

        if (!empty($existing)) {
            continue;
        }


        $user = get_user_by('email', $o->customer_email);
        $user_id = $user ? $user->ID : 0;


        $order = wc_create_order([
            'customer_id' => $user_id
        ]);


        $items = $external_db->get_results("
            SELECT * FROM sales_order_item
            WHERE order_id = {$o->entity_id}
            AND parent_item_id IS NULL
        ");

        foreach ($items as $item) {


            $product_id = $wpdb->get_var("
                SELECT post_id FROM {$wpdb->postmeta}
                WHERE meta_key = 'old_product_id'
                AND meta_value = '{$item->product_id}'
                LIMIT 1
            ");

            if ($product_id) {
                $product = wc_get_product($product_id);

                if ($product) {
                    $order->add_product($product, $item->qty_ordered, [
                        'subtotal' => $item->row_total,
                        'total'    => $item->row_total
                    ]);
                }
            }
        }


        $order->set_payment_method($o->payment_method ?? 'cod');


        $order->set_total($o->grand_total);


        $order->update_meta_data('old_order_id', $o->entity_id);


        $order->set_date_created($o->created_at);

        $order->save();
    }


    $total = $external_db->get_var("SELECT COUNT(*) FROM sales_order");
    $has_more = ($offset + $limit) < $total;


    restore_current_blog();

    wp_send_json_success([
        'has_more' => $has_more
    ]);
}



// For Promo Materials

add_action('wp_ajax_nitto_fetch_promo_orders', 'nitto_fetch_promo_orders');

function nitto_fetch_promo_orders()
{
    $external_db = nitto_magento_db();

    if (!$external_db) {
        wp_die('<p>Magento DB connection failed</p>');
    }


    $orders = $external_db->get_results("
        SELECT 
            o.entity_id,
            o.increment_id,
            o.created_at,
            o.status,
            o.state,

            o.customer_email,
            o.customer_firstname,
            o.customer_lastname,

            o.grand_total,
            o.subtotal,
            o.total_qty_ordered,
            o.total_paid,

            pay.method AS payment_method,

            bill.city AS billing_city,
            bill.country_id AS billing_country,

            ship.city AS shipping_city,
            ship.country_id AS shipping_country,

            GROUP_CONCAT(
                DISTINCT CONCAT(oi.name, ' (', oi.qty_ordered, ')')
                SEPARATOR ', '
            ) AS items

        FROM sales_order o

        LEFT JOIN sales_order_payment pay 
            ON pay.parent_id = o.entity_id

        LEFT JOIN sales_order_address bill 
            ON bill.parent_id = o.entity_id 
            AND bill.address_type = 'billing'

        LEFT JOIN sales_order_address ship 
            ON ship.parent_id = o.entity_id 
            AND ship.address_type = 'shipping'

        LEFT JOIN sales_order_item oi 
            ON oi.order_id = o.entity_id 
            AND oi.parent_item_id IS NULL

        GROUP BY o.entity_id

        ORDER BY o.created_at DESC
        LIMIT 200
    ");

    if (!$orders) {
        wp_die('<p>No orders found</p>');
    }

    // 🔹 Status label helper (optional refinement)
    function formatOrderStatus($status)
    {
        return ucfirst(str_replace('_', ' ', $status));
    }

    ob_start();
?>

    <div class="nitto-table-container">
        <h2>Magento Orders</h2>
        <button onclick="startPromoOrderMigration()">Migrate Promo orders</button>
        <div id="migration-status"></div>
        <table class="nitto-modern-table">

            <thead>
                <tr>
                    <th>Sl no.</th>
                    <th>ID</th>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Billing</th>
                    <th>Shipping</th>
                    <th>Items</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($orders as $o): ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?= esc_html($o->entity_id); ?></td>
                        <td><?= esc_html($o->increment_id); ?></td>
                        <td><?= esc_html($o->created_at); ?></td>

                        <td>
                            <?= esc_html($o->customer_firstname . ' ' . $o->customer_lastname); ?>
                        </td>

                        <td><?= esc_html($o->customer_email); ?></td>

                        <td><?= esc_html($o->grand_total); ?></td>
                        <td><?= esc_html($o->total_paid); ?></td>
                        <td><?= esc_html($o->total_qty_ordered); ?></td>

                        <td><?= esc_html(formatOrderStatus($o->status)); ?></td>

                        <td><?= esc_html($o->payment_method); ?></td>

                        <td>
                            <?= esc_html($o->billing_city . ', ' . $o->billing_country); ?>
                        </td>

                        <td>
                            <?= esc_html($o->shipping_city . ', ' . $o->shipping_country); ?>
                        </td>

                        <td style="max-width:300px;">
                            <?= esc_html($o->items); ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                endforeach; ?>
            </tbody>

        </table>
    </div>

<?php

    echo ob_get_clean();
    wp_die();
}

add_action('wp_ajax_nitto_migrate_promo_orders_batch', 'nitto_migrate_promo_orders_batch');
function nitto_migrate_promo_orders_batch()
{
    $offset = intval($_POST['offset']);
    $limit  = intval($_POST['limit']);

    $external_db = nitto_magento_db();
    
    if (!$external_db) {
        wp_send_json_error('DB connection failed');
    }


    switch_to_blog(3);

    $orders = $external_db->get_results("
        SELECT *
        FROM sales_order
        ORDER BY entity_id ASC
        LIMIT $limit OFFSET $offset
    ");

    if (!$orders) {
        restore_current_blog();
        wp_send_json_success(['has_more' => false]);
    }

    global $wpdb;

    foreach ($orders as $o) {


        $existing = wc_get_orders([
            'meta_key'   => 'old_order_id',
            'meta_value' => $o->entity_id,
            'limit'      => 1
        ]);

        if (!empty($existing)) {
            continue;
        }


        $user = get_user_by('email', $o->customer_email);
        $user_id = $user ? $user->ID : 0;


        $order = wc_create_order([
            'customer_id' => $user_id
        ]);


        $items = $external_db->get_results("
            SELECT * FROM sales_order_item
            WHERE order_id = {$o->entity_id}
            AND parent_item_id IS NULL
        ");

        foreach ($items as $item) {


            $product_id = $wpdb->get_var("
                SELECT post_id FROM {$wpdb->postmeta}
                WHERE meta_key = 'old_product_id'
                AND meta_value = '{$item->product_id}'
                LIMIT 1
            ");

            if ($product_id) {
                $product = wc_get_product($product_id);

                if ($product) {
                    $order->add_product($product, $item->qty_ordered, [
                        'subtotal' => $item->row_total,
                        'total'    => $item->row_total
                    ]);
                }
            }
        }


        $order->set_payment_method($o->payment_method ?? 'cod');


        $order->set_total($o->grand_total);


        $order->update_meta_data('old_order_id', $o->entity_id);


        $order->set_date_created($o->created_at);

        $order->save();
    }


    $total = $external_db->get_var("SELECT COUNT(*) FROM sales_order");
    $has_more = ($offset + $limit) < $total;


    restore_current_blog();

    wp_send_json_success([
        'has_more' => $has_more
    ]);
}
