<?php
add_action('wp_ajax_nitto_fetch_products', 'nitto_fetch_products');

function nitto_fetch_products()
{
    $external_db = nitto_magento_db();

    if (!$external_db) {
        wp_die('<p>Magento DB connection failed</p>');
    }

    $store_id = 1; // change for language

    // 🔹 helper
    function getAttr($db, $code, $type = 4)
    {
        return $db->get_var("SELECT attribute_id FROM eav_attribute 
            WHERE attribute_code = '{$code}' AND entity_type_id = {$type}");
    }

    // 🔹 attributes
    $name_attr = getAttr($external_db, 'name');
    $price_attr = getAttr($external_db, 'price');
    $special_price_attr = getAttr($external_db, 'special_price');
    $cost_attr = getAttr($external_db, 'cost');
    $weight_attr = getAttr($external_db, 'weight');
    $short_desc_attr = getAttr($external_db, 'short_description');
    $visibility_attr = getAttr($external_db, 'visibility');
    $status_attr = getAttr($external_db, 'status');
    $image_attr = getAttr($external_db, 'thumbnail');
    $color_attr = getAttr($external_db, 'color');
    $size_attr = getAttr($external_db, 'size');
    $material_attr = getAttr($external_db, 'material');
    $manufacturer_attr = getAttr($external_db, 'manufacturer');

    $category_name_attr = getAttr($external_db, 'name', 3);

    // 🔥 QUERY
    $products = $external_db->get_results("
    SELECT 
        p.entity_id,
        p.sku,
        p.type_id,
        aset.attribute_set_name,

        MAX(name.value) AS name,
        MAX(price.value) AS price,
        MAX(special_price.value) AS special_price,
        MAX(cost.value) AS cost,
        MAX(weight.value) AS weight,
        MAX(short_desc.value) AS short_description,

        MAX(vis.value) AS visibility,
        MAX(stat.value) AS status,

        stock.qty AS qty,

        MAX(img.value) AS image,

        GROUP_CONCAT(DISTINCT cat_name.value SEPARATOR ', ') AS categories,

        -- dropdown labels
        color_val.value AS color,
        size_val.value AS size,
        material_val.value AS material,
        manufacturer_val.value AS manufacturer

    FROM catalog_product_entity p

    LEFT JOIN eav_attribute_set aset 
        ON aset.attribute_set_id = p.attribute_set_id

    -- VARCHAR
    LEFT JOIN catalog_product_entity_varchar name 
        ON name.entity_id = p.entity_id AND name.attribute_id = {$name_attr} AND name.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_varchar img 
        ON img.entity_id = p.entity_id AND img.attribute_id = {$image_attr} AND img.store_id IN (0, {$store_id})

    -- TEXT
    LEFT JOIN catalog_product_entity_text short_desc 
        ON short_desc.entity_id = p.entity_id AND short_desc.attribute_id = {$short_desc_attr} AND short_desc.store_id IN (0, {$store_id})

    -- DECIMAL
    LEFT JOIN catalog_product_entity_decimal price 
        ON price.entity_id = p.entity_id AND price.attribute_id = {$price_attr} AND price.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal special_price 
        ON special_price.entity_id = p.entity_id AND special_price.attribute_id = {$special_price_attr} AND special_price.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal cost 
        ON cost.entity_id = p.entity_id AND cost.attribute_id = {$cost_attr} AND cost.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal weight 
        ON weight.entity_id = p.entity_id AND weight.attribute_id = {$weight_attr} AND weight.store_id IN (0, {$store_id})

    -- INT
    LEFT JOIN catalog_product_entity_int vis 
        ON vis.entity_id = p.entity_id AND vis.attribute_id = {$visibility_attr} AND vis.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_int stat 
        ON stat.entity_id = p.entity_id AND stat.attribute_id = {$status_attr} AND stat.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_int color 
        ON color.entity_id = p.entity_id AND color.attribute_id = {$color_attr}

    LEFT JOIN eav_attribute_option_value color_val
        ON color_val.option_id = color.value AND color_val.store_id = 0

    LEFT JOIN catalog_product_entity_int size 
        ON size.entity_id = p.entity_id AND size.attribute_id = {$size_attr}

    LEFT JOIN eav_attribute_option_value size_val
        ON size_val.option_id = size.value AND size_val.store_id = 0

    LEFT JOIN catalog_product_entity_int material 
        ON material.entity_id = p.entity_id AND material.attribute_id = {$material_attr}

    LEFT JOIN eav_attribute_option_value material_val
        ON material_val.option_id = material.value AND material_val.store_id = 0

    LEFT JOIN catalog_product_entity_int manufacturer 
        ON manufacturer.entity_id = p.entity_id AND manufacturer.attribute_id = {$manufacturer_attr}

    LEFT JOIN eav_attribute_option_value manufacturer_val
        ON manufacturer_val.option_id = manufacturer.value AND manufacturer_val.store_id = 0

    -- STOCK
    LEFT JOIN cataloginventory_stock_item stock 
        ON stock.product_id = p.entity_id

    -- CATEGORY
    LEFT JOIN catalog_category_product ccp 
        ON ccp.product_id = p.entity_id

    LEFT JOIN catalog_category_entity_varchar cat_name
        ON cat_name.entity_id = ccp.category_id
        AND cat_name.attribute_id = {$category_name_attr}
        AND cat_name.store_id IN (0, {$store_id})

    GROUP BY p.entity_id
    ");

    if (!$products) {
        wp_die('<p>No products found</p>');
    }

    // 🔹 helpers
    function mapVisibility($v)
    {
        return [1 => 'Not Visible', 2 => 'Catalog', 3 => 'Search', 4 => 'Catalog, Search'][$v] ?? $v;
    }

    function mapStatus($s)
    {
        return $s == 1 ? 'Enabled' : 'Disabled';
    }

    ob_start();
?>

    <div class="nitto-table-container">
        <h2>Magento Products</h2>
        <button class="button button-primary" id="migrate-all-promo" onclick="migratePromoProduct()">
            Migrate All Promo Products
        </button>

        <div class="progress-container">
            <div class="progress-track">
                <div id="progress-bar"></div>
            </div>
            <p id="progress-text">0%</p>
        </div>
        <table class="nitto-modern-table">

            <thead>
                <tr>
                    <th></th>
                    <th>Sl no.</th>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Attribute Set</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Special Price</th>
                    <th>Qty</th>
                    <th>Visibility</th>
                    <th>Status</th>
                    <th>Category</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Material</th>
                    <th>Manufacturer</th>
                    <th>Short Description</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <button
                                class="migrate-promo-btn button button-secondary"
                                data-product="<?php echo esc_attr(json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT)); ?>">
                                Migrate
                            </button>
                        </td>

                        <td><?php echo $i; ?></td>
                        <td><?= esc_html($p->entity_id) ?></td>

                        <td>
                            <?php if ($p->image): ?>
                                <img src="<?= esc_url('https://promo.nittosupport.ca/pub/media/catalog/product' . $p->image) ?>" width="50">
                            <?php endif; ?>
                        </td>

                        <td><?= esc_html($p->name) ?></td>
                        <td><?= esc_html($p->type_id) ?></td>
                        <td><?= esc_html($p->attribute_set_name) ?></td>
                        <td><?= esc_html($p->sku) ?></td>
                        <td><?= esc_html($p->price) ?></td>
                        <td><?= esc_html($p->special_price) ?></td>
                        <td><?= esc_html($p->qty) ?></td>
                        <td><?= esc_html(mapVisibility($p->visibility)) ?></td>
                        <td><?= esc_html(mapStatus($p->status)) ?></td>
                        <td><?= esc_html($p->categories) ?></td>
                        <td><?= esc_html($p->color) ?></td>
                        <td><?= esc_html($p->size) ?></td>
                        <td><?= esc_html($p->material) ?></td>
                        <td><?= esc_html($p->manufacturer) ?></td>
                        <td><?= esc_html(wp_trim_words($p->short_description, 10)) ?></td>
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

// Migrate Product
add_action('wp_ajax_nitto_migrate_product', 'nitto_migrate_product');

function nitto_migrate_product_old()
{
    $product_data = json_decode(stripslashes($_POST['product']), true);
    // print_r($product_data);
    // die;

    if (!$product_data) {
        wp_send_json_error('No product data');
    }

    switch_to_blog(3);

    $existing_id = wc_get_product_id_by_sku($product_data['sku']);
    if ($existing_id) {
        restore_current_blog();
        wp_send_json_error('Product already exists with SKU: ' . $product_data['sku']);
    }

    $product = new WC_Product_Simple();

    $product->set_name($product_data['name']);
    $product->set_sku($product_data['sku']);
    $product->set_regular_price($product_data['price']);

    if (!empty($product_data['special_price'])) {
        $product->set_sale_price($product_data['special_price']);
    }

    $product->set_description($product_data['description'] ?? '');
    $product->set_short_description($product_data['short_description'] ?? '');

    $product->set_manage_stock(true);
    $product->set_stock_quantity($product_data['qty'] ?? 0);
    $product->set_stock_status('instock');

    if (!empty($product_data['entity_id'])) {
        $product->update_meta_data('old_product_id', $product_data['entity_id']);
    }


    $product_id = $product->save();

    if (!empty($product_data['categories'])) {
        $cats = explode(',', $product_data['categories']);
        $term_ids = [];

        foreach ($cats as $cat) {
            $cat = trim($cat);

            $term = term_exists($cat, 'product_cat');

            if (!$term) {
                $term = wp_insert_term($cat, 'product_cat');
            }

            if (!is_wp_error($term)) {
                $term_ids[] = is_array($term) ? $term['term_id'] : $term;
            }
        }

        wp_set_object_terms($product_id, $term_ids, 'product_cat');
    }

    if (!empty($product_data['color'])) {
        wp_set_object_terms($product_id, $product_data['color'], 'pa_color');
    }

    if (!empty($product_data['image'])) {

        $image_url = 'https://promo.nittosupport.ca/pub/media/catalog/product' . $product_data['image'];

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_sideload_image($image_url, $product_id, null, 'id');

        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($product_id, $attachment_id);
        }
    }

    restore_current_blog();

    wp_send_json_success("Product migrated to site ID 3: " . $product_id);
}

add_action('wp_ajax_nitto_migrate_promo_products_batch', 'nitto_migrate_promo_products_batch');

function nitto_log_promo($msg)
{
    error_log('[NITTO MIGRATION] ' . $msg);
}

function nitto_migrate_promo_products_batch_backup()
{
    $products = json_decode(stripslashes($_POST['products']), true);

    if (!$products || !is_array($products)) {
        wp_send_json_error('Invalid batch data');
    }

    switch_to_blog(3);

    $failed = [];

    foreach ($products as $product_data) {

        try {

            // ✅ Duplicate check using old_product_id
            $existing = get_posts([
                'post_type' => 'product',
                'meta_query' => [
                    [
                        'key' => 'old_product_id',
                        'value' => $product_data['entity_id'],
                        'compare' => '='
                    ]
                ],
                'fields' => 'ids',
                'posts_per_page' => 1
            ]);

            if (!empty($existing)) {
                nitto_log_promo('Skipped existing: ' . $product_data['entity_id']);
                continue;
            }

            $product = new WC_Product_Simple();

            $product->set_name($product_data['name']);
            $product->set_sku($product_data['sku']);
            $product->set_regular_price($product_data['price']);

            if (!empty($product_data['special_price'])) {
                $product->set_sale_price($product_data['special_price']);
            }

            $product->set_description($product_data['description'] ?? '');
            $product->set_short_description($product_data['short_description'] ?? '');

            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['qty'] ?? 0);
            $product->set_stock_status('instock');

            
            if (!empty($product_data['entity_id'])) {
                $product->update_meta_data('old_product_id', $product_data['entity_id']);
            }

            $product_id = $product->save();

            // Categories
            if (!empty($product_data['categories'])) {
                $cats = explode(',', $product_data['categories']);
                $term_ids = [];

                foreach ($cats as $cat) {
                    $cat = trim($cat);

                    $term = term_exists($cat, 'product_cat');
                    if (!$term) {
                        $term = wp_insert_term($cat, 'product_cat');
                    }

                    if (!is_wp_error($term)) {
                        $term_ids[] = is_array($term) ? $term['term_id'] : $term;
                    }
                }

                wp_set_object_terms($product_id, $term_ids, 'product_cat');
            }

            // Color attribute
            if (!empty($product_data['color'])) {
                wp_set_object_terms($product_id, $product_data['color'], 'pa_color');
            }

            // Image
            if (!empty($product_data['image'])) {

                $image_url = 'https://promo.nittosupport.ca/pub/media/catalog/product' . $product_data['image'];

                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';

                $attachment_id = media_sideload_image($image_url, $product_id, null, 'id');

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($product_id, $attachment_id);
                }
            }

            nitto_log('Created product ID: ' . $product_id);
        } catch (Exception $e) {
            $failed[] = $product_data;
            nitto_log('Failed: ' . $e->getMessage());
        }
    }

    restore_current_blog();

    wp_send_json_success([
        'processed' => count($products),
        'failed' => $failed
    ]);
}

function nitto_migrate_promo_products_batch()
{
    $products = json_decode(stripslashes($_POST['products']), true);

    if (!$products || !is_array($products)) {
        wp_send_json_error('Invalid batch data');
    }

    switch_to_blog(3);

    $failed = [];

    foreach ($products as $product_data) {

        try {

            $existing = get_posts([
                'post_type' => 'product',
                'meta_query' => [
                    [
                        'key' => 'old_product_id',
                        'value' => $product_data['entity_id'],
                        'compare' => '='
                    ]
                ],
                'fields' => 'ids',
                'posts_per_page' => 1
            ]);

            if (!empty($existing)) {
                nitto_log_promo('Skipped existing: ' . $product_data['entity_id']);
                continue;
            }

            // =========================
            // ✅ CREATE EN PRODUCT
            // =========================
            $product = new WC_Product_Simple();

            $product->set_name($product_data['name']);
            $product->set_sku($product_data['sku']);
            $product->set_regular_price($product_data['price']);

            if (!empty($product_data['special_price'])) {
                $product->set_sale_price($product_data['special_price']);
            }

            $product->set_description($product_data['description'] ?? '');
            $product->set_short_description($product_data['short_description'] ?? '');

            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['qty'] ?? 0);
            $product->set_stock_status('instock');

            // ✅ Only EN gets old_product_id
            if (!empty($product_data['entity_id'])) {
                $product->update_meta_data('old_product_id', $product_data['entity_id']);
            }

            $product_id = $product->save();

            // =========================
            // ✅ CATEGORIES
            // =========================
            $term_ids = [];

            if (!empty($product_data['categories'])) {
                $cats = explode(',', $product_data['categories']);

                foreach ($cats as $cat) {
                    $cat = trim($cat);

                    $term = term_exists($cat, 'product_cat');
                    if (!$term) {
                        $term = wp_insert_term($cat, 'product_cat');
                    }

                    if (!is_wp_error($term)) {
                        $term_ids[] = is_array($term) ? $term['term_id'] : $term;
                    }
                }

                wp_set_object_terms($product_id, $term_ids, 'product_cat');
            }

            // =========================
            // ✅ COLOR ATTRIBUTE
            // =========================
            if (!empty($product_data['color'])) {
                wp_set_object_terms($product_id, $product_data['color'], 'pa_color');
            }

            // =========================
            // ✅ IMAGE
            // =========================
            $attachment_id = null;

            if (!empty($product_data['image'])) {

                $image_url = 'https://promo.nittosupport.ca/pub/media/catalog/product' . $product_data['image'];

                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';

                $attachment_id = media_sideload_image($image_url, $product_id, null, 'id');

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($product_id, $attachment_id);
                }
            }

            // =========================
            // ✅ CREATE FRENCH PRODUCT
            // =========================
            $french_product = new WC_Product_Simple();

            $french_product->set_name($product_data['name']); // replace with translated if available
            $french_product->set_sku($product_data['sku'] . '-fr'); // unique SKU
            $french_product->set_regular_price($product_data['price']);

            if (!empty($product_data['special_price'])) {
                $french_product->set_sale_price($product_data['special_price']);
            }

            $french_product->set_description($product_data['description'] ?? '');
            $french_product->set_short_description($product_data['short_description'] ?? '');

            $french_product->set_manage_stock(true);
            $french_product->set_stock_quantity($product_data['qty'] ?? 0);
            $french_product->set_stock_status('instock');

            // ❌ No old_product_id here

            $french_product_id = $french_product->save();

            // =========================
            // ✅ APPLY SAME TAXONOMIES TO FR
            // =========================
            if (!empty($term_ids)) {
                wp_set_object_terms($french_product_id, $term_ids, 'product_cat');
            }

            if (!empty($product_data['color'])) {
                wp_set_object_terms($french_product_id, $product_data['color'], 'pa_color');
            }

            // =========================
            // ✅ COPY IMAGE TO FR
            // =========================
            if ($attachment_id) {
                set_post_thumbnail($french_product_id, $attachment_id);
            }

            // =========================
            // ✅ LINK VIA WPML
            // =========================
            $trid = apply_filters('wpml_element_trid', null, $product_id, 'post_product');

            do_action('wpml_set_element_language_details', [
                'element_id' => $french_product_id,
                'element_type' => 'post_product',
                'trid' => $trid,
                'language_code' => 'fr',
                'source_language_code' => 'en'
            ]);

            nitto_log('Created product ID: ' . $product_id . ' | FR: ' . $french_product_id);

        } catch (Exception $e) {
            $failed[] = $product_data;
            nitto_log('Failed: ' . $e->getMessage());
        }
    }

    restore_current_blog();

    wp_send_json_success([
        'processed' => count($products),
        'failed' => $failed
    ]);
}






// For Point of purchase
add_action('wp_ajax_nitto_fetch_pos_products', 'nitto_fetch_pos_products');

function nitto_fetch_pos_products()
{
    $external_db = nitto_magento_pos_db();

    if (!$external_db) {
        wp_die('<p>Magento DB connection failed</p>');
    }

    $store_id = 1; // change for language

    // 🔹 helper
    function getAttr($db, $code, $type = 4)
    {
        return $db->get_var("SELECT attribute_id FROM eav_attribute 
            WHERE attribute_code = '{$code}' AND entity_type_id = {$type}");
    }

    // 🔹 attributes
    $name_attr = getAttr($external_db, 'name');
    $price_attr = getAttr($external_db, 'price');
    $special_price_attr = getAttr($external_db, 'special_price');
    $cost_attr = getAttr($external_db, 'cost');
    $weight_attr = getAttr($external_db, 'weight');
    $short_desc_attr = getAttr($external_db, 'short_description');
    $visibility_attr = getAttr($external_db, 'visibility');
    $status_attr = getAttr($external_db, 'status');
    $image_attr = getAttr($external_db, 'thumbnail');
    $color_attr = getAttr($external_db, 'color');
    $size_attr = getAttr($external_db, 'size');
    $material_attr = getAttr($external_db, 'material');
    $manufacturer_attr = getAttr($external_db, 'manufacturer');

    $category_name_attr = getAttr($external_db, 'name', 3);

    // 🔥 QUERY
    $products = $external_db->get_results("
    SELECT 
        p.entity_id,
        p.sku,
        p.type_id,
        aset.attribute_set_name,

        MAX(name.value) AS name,
        MAX(price.value) AS price,
        MAX(special_price.value) AS special_price,
        MAX(cost.value) AS cost,
        MAX(weight.value) AS weight,
        MAX(short_desc.value) AS short_description,

        MAX(vis.value) AS visibility,
        MAX(stat.value) AS status,

        stock.qty AS qty,

        MAX(img.value) AS image,

        GROUP_CONCAT(DISTINCT cat_name.value SEPARATOR ', ') AS categories,

        -- dropdown labels
        color_val.value AS color,
        size_val.value AS size,
        material_val.value AS material,
        manufacturer_val.value AS manufacturer

    FROM catalog_product_entity p

    LEFT JOIN eav_attribute_set aset 
        ON aset.attribute_set_id = p.attribute_set_id

    -- VARCHAR
    LEFT JOIN catalog_product_entity_varchar name 
        ON name.entity_id = p.entity_id AND name.attribute_id = {$name_attr} AND name.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_varchar img 
        ON img.entity_id = p.entity_id AND img.attribute_id = {$image_attr} AND img.store_id IN (0, {$store_id})

    -- TEXT
    LEFT JOIN catalog_product_entity_text short_desc 
        ON short_desc.entity_id = p.entity_id AND short_desc.attribute_id = {$short_desc_attr} AND short_desc.store_id IN (0, {$store_id})

    -- DECIMAL
    LEFT JOIN catalog_product_entity_decimal price 
        ON price.entity_id = p.entity_id AND price.attribute_id = {$price_attr} AND price.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal special_price 
        ON special_price.entity_id = p.entity_id AND special_price.attribute_id = {$special_price_attr} AND special_price.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal cost 
        ON cost.entity_id = p.entity_id AND cost.attribute_id = {$cost_attr} AND cost.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_decimal weight 
        ON weight.entity_id = p.entity_id AND weight.attribute_id = {$weight_attr} AND weight.store_id IN (0, {$store_id})

    -- INT
    LEFT JOIN catalog_product_entity_int vis 
        ON vis.entity_id = p.entity_id AND vis.attribute_id = {$visibility_attr} AND vis.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_int stat 
        ON stat.entity_id = p.entity_id AND stat.attribute_id = {$status_attr} AND stat.store_id IN (0, {$store_id})

    LEFT JOIN catalog_product_entity_int color 
        ON color.entity_id = p.entity_id AND color.attribute_id = {$color_attr}

    LEFT JOIN eav_attribute_option_value color_val
        ON color_val.option_id = color.value AND color_val.store_id = 0

    LEFT JOIN catalog_product_entity_int size 
        ON size.entity_id = p.entity_id AND size.attribute_id = {$size_attr}

    LEFT JOIN eav_attribute_option_value size_val
        ON size_val.option_id = size.value AND size_val.store_id = 0

    LEFT JOIN catalog_product_entity_int material 
        ON material.entity_id = p.entity_id AND material.attribute_id = {$material_attr}

    LEFT JOIN eav_attribute_option_value material_val
        ON material_val.option_id = material.value AND material_val.store_id = 0

    LEFT JOIN catalog_product_entity_int manufacturer 
        ON manufacturer.entity_id = p.entity_id AND manufacturer.attribute_id = {$manufacturer_attr}

    LEFT JOIN eav_attribute_option_value manufacturer_val
        ON manufacturer_val.option_id = manufacturer.value AND manufacturer_val.store_id = 0

    -- STOCK
    LEFT JOIN cataloginventory_stock_item stock 
        ON stock.product_id = p.entity_id

    -- CATEGORY
    LEFT JOIN catalog_category_product ccp 
        ON ccp.product_id = p.entity_id

    LEFT JOIN catalog_category_entity_varchar cat_name
        ON cat_name.entity_id = ccp.category_id
        AND cat_name.attribute_id = {$category_name_attr}
        AND cat_name.store_id IN (0, {$store_id})

    GROUP BY p.entity_id
    ");

    if (!$products) {
        wp_die('<p>No products found</p>');
    }

    // 🔹 helpers
    function mapVisibility($v)
    {
        return [1 => 'Not Visible', 2 => 'Catalog', 3 => 'Search', 4 => 'Catalog, Search'][$v] ?? $v;
    }

    function mapStatus($s)
    {
        return $s == 1 ? 'Enabled' : 'Disabled';
    }

    ob_start();
?>

    <div class="nitto-table-container">
        <h2>Magento POS Products</h2>

        <button class="button button-primary" id="migrate-all-pos" onclick="migratePosProduct()">
            Migrate All POS Products
        </button>

        <div class="progress-container">
            <div class="progress-track">
                <div id="progress-bar"></div>
            </div>
            <p id="progress-text">0%</p>
        </div>

        <table class="nitto-modern-table">

            <thead>
                <tr>
                    <th></th>
                    <th>Sl no.</th>
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Attribute Set</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Special Price</th>
                    <th>Qty</th>
                    <th>Visibility</th>
                    <th>Status</th>
                    <th>Category</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Material</th>
                    <th>Manufacturer</th>
                    <th>Short Description</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <button
                                class="migrate-pos-btn button button-secondary"
                                data-product="<?php echo esc_attr(json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT)); ?>">
                                Migrate
                            </button>
                        </td>

                        <td><?php echo $i; ?></td>
                        <td><?= esc_html($p->entity_id) ?></td>

                        <td>
                            <?php if ($p->image): ?>
                                <img src="<?= esc_url('https://pos.nittosupport.ca/pub/media/catalog/product' . $p->image) ?>" width="50">
                            <?php endif; ?>
                        </td>

                        <td><?= esc_html($p->name) ?></td>
                        <td><?= esc_html($p->type_id) ?></td>
                        <td><?= esc_html($p->attribute_set_name) ?></td>
                        <td><?= esc_html($p->sku) ?></td>
                        <td><?= esc_html($p->price) ?></td>
                        <td><?= esc_html($p->special_price) ?></td>
                        <td><?= esc_html($p->qty) ?></td>
                        <td><?= esc_html(mapVisibility($p->visibility)) ?></td>
                        <td><?= esc_html(mapStatus($p->status)) ?></td>
                        <td><?= esc_html($p->categories) ?></td>
                        <td><?= esc_html($p->color) ?></td>
                        <td><?= esc_html($p->size) ?></td>
                        <td><?= esc_html($p->material) ?></td>
                        <td><?= esc_html($p->manufacturer) ?></td>
                        <td><?= esc_html(wp_trim_words($p->short_description, 10)) ?></td>
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

// Migrate POS Product
// add_action('wp_ajax_nitto_migrate_pos_product', 'nitto_migrate_pos_product');

// function nitto_migrate_pos_product()
// {
//     $product_data = json_decode(stripslashes($_POST['product']), true);
//     // print_r($product_data);
//     // die;

//     if (!$product_data) {
//         wp_send_json_error('No product data');
//     }

//     switch_to_blog(2); 

//     $existing_id = wc_get_product_id_by_sku($product_data['sku']);
//     if ($existing_id) {
//         restore_current_blog();
//         wp_send_json_error('Product already exists with SKU: ' . $product_data['sku']);
//     }

//     $product = new WC_Product_Simple();

//     $product->set_name($product_data['name']);
//     $product->set_sku($product_data['sku']);
//     $product->set_regular_price($product_data['price']);

//     if (!empty($product_data['special_price'])) {
//         $product->set_sale_price($product_data['special_price']);
//     }

//     $product->set_description($product_data['description'] ?? '');
//     $product->set_short_description($product_data['short_description'] ?? '');

//     $product->set_manage_stock(true);
//     $product->set_stock_quantity($product_data['qty'] ?? 0);
//     $product->set_stock_status('instock');

//     if (!empty($product_data['entity_id'])) {
//         $product->update_meta_data('old_product_id', $product_data['entity_id']);
//     }


//     $product_id = $product->save();

//     if (!empty($product_data['categories'])) {
//         $cats = explode(',', $product_data['categories']);
//         $term_ids = [];

//         foreach ($cats as $cat) {
//             $cat = trim($cat);

//             $term = term_exists($cat, 'product_cat');

//             if (!$term) {
//                 $term = wp_insert_term($cat, 'product_cat');
//             }

//             if (!is_wp_error($term)) {
//                 $term_ids[] = is_array($term) ? $term['term_id'] : $term;
//             }
//         }

//         wp_set_object_terms($product_id, $term_ids, 'product_cat');
//     }

//     if (!empty($product_data['color'])) {
//         wp_set_object_terms($product_id, $product_data['color'], 'pa_color');
//     }

//     if (!empty($product_data['image'])) {

//         $image_url = 'https://pos.nittosupport.ca/pub/media/catalog/product' . $product_data['image'];

//         require_once ABSPATH . 'wp-admin/includes/file.php';
//         require_once ABSPATH . 'wp-admin/includes/media.php';
//         require_once ABSPATH . 'wp-admin/includes/image.php';

//         $attachment_id = media_sideload_image($image_url, $product_id, null, 'id');

//         if (!is_wp_error($attachment_id)) {
//             set_post_thumbnail($product_id, $attachment_id);
//         }
//     }

//     restore_current_blog();

//     wp_send_json_success("Product migrated to site ID 2: " . $product_id);
// }

add_action('wp_ajax_nitto_migrate_pos_products_batch', 'nitto_migrate_pos_products_batch');

function nitto_log($msg)
{
    error_log('[NITTO MIGRATION] ' . $msg);
}

function nitto_migrate_pos_products_batch()
{
    $products = json_decode(stripslashes($_POST['products']), true);

    if (!$products || !is_array($products)) {
        wp_send_json_error('Invalid batch data');
    }

    switch_to_blog(2);

    $failed = [];

    foreach ($products as $product_data) {

        try {

            // ✅ Duplicate check using old_product_id
            $existing = get_posts([
                'post_type' => 'product',
                'meta_query' => [
                    [
                        'key' => 'old_product_id',
                        'value' => $product_data['entity_id'],
                        'compare' => '='
                    ]
                ],
                'fields' => 'ids',
                'posts_per_page' => 1
            ]);

            if (!empty($existing)) {
                nitto_log('Skipped existing: ' . $product_data['entity_id']);
                continue;
            }

            $product = new WC_Product_Simple();

            $product->set_name($product_data['name']);
            $product->set_sku($product_data['sku']);
            $product->set_regular_price($product_data['price']);

            if (!empty($product_data['special_price'])) {
                $product->set_sale_price($product_data['special_price']);
            }

            $product->set_description($product_data['description'] ?? '');
            $product->set_short_description($product_data['short_description'] ?? '');

            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['qty'] ?? 0);
            $product->set_stock_status('instock');

            
            if (!empty($product_data['entity_id'])) {
                $product->update_meta_data('old_product_id', $product_data['entity_id']);
            }

            $product_id = $product->save();

            // Categories
            if (!empty($product_data['categories'])) {
                $cats = explode(',', $product_data['categories']);
                $term_ids = [];

                foreach ($cats as $cat) {
                    $cat = trim($cat);

                    $term = term_exists($cat, 'product_cat');
                    if (!$term) {
                        $term = wp_insert_term($cat, 'product_cat');
                    }

                    if (!is_wp_error($term)) {
                        $term_ids[] = is_array($term) ? $term['term_id'] : $term;
                    }
                }

                wp_set_object_terms($product_id, $term_ids, 'product_cat');
            }

            // Color attribute
            if (!empty($product_data['color'])) {
                wp_set_object_terms($product_id, $product_data['color'], 'pa_color');
            }

            // Image
            if (!empty($product_data['image'])) {

                $image_url = 'https://pos.nittosupport.ca/pub/media/catalog/product' . $product_data['image'];

                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';

                $attachment_id = media_sideload_image($image_url, $product_id, null, 'id');

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($product_id, $attachment_id);
                }
            }

            nitto_log('Created product ID: ' . $product_id);
        } catch (Exception $e) {
            $failed[] = $product_data;
            nitto_log('Failed: ' . $e->getMessage());
        }
    }

    restore_current_blog();

    wp_send_json_success([
        'processed' => count($products),
        'failed' => $failed
    ]);
}
