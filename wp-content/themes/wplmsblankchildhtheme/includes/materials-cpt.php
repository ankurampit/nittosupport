<?php
// Ad Materials CPT
// Register "Add Materials" Post Type

function add_materials_post_type()
{

    $labels = array(
        'name'               => 'Materials',
        'singular_name'      => 'Material',
        'menu_name'          => 'Materials',
        'name_admin_bar'     => 'Material',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Material',
        'edit_item'          => 'Edit Material',
        'new_item'           => 'New Material',
        'view_item'          => 'View Material',
        'all_items'          => 'All Materials',
        'search_items'       => 'Search Materials',
        'not_found'          => 'No materials found',
        'not_found_in_trash' => 'No materials found in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'materials'),
        'show_in_rest'       => true,   // Enables Gutenberg + API
    );

    register_post_type('materials', $args);
}
add_action('init', 'add_materials_post_type');


// material category in ACF

function materials_category_taxonomy()
{

    $labels = array(
        'name'              => 'Material Categories',
        'singular_name'     => 'Material Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Material Categories',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'material-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('material_category', array('materials'), $args);
}
add_action('init', 'materials_category_taxonomy');



add_action('admin_footer', 'single_category_selection_for_materials');
function single_category_selection_for_materials()
{
    $screen = get_current_screen();
    if ($screen->post_type == 'materials') {
?>
        <script>
            jQuery(function($) {
                $('.categorychecklist input').on('click', function() {
                    $('.categorychecklist input').not(this).prop('checked', false);
                });
            });
        </script>
<?php
    }
}

add_action('acf/save_post', function ($post_id) {

    if (is_admin()) return;

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'materials') {
        return;
    }

    $term_slug = get_field('material_term_slug', $post_id);
    if (empty($term_slug)) {
        return;
    }

    wp_set_object_terms(
        $post_id,
        sanitize_title($term_slug),
        'material_category',
        false
    );
}, 20);


// Delete Post
add_action('wp_ajax_delete_material_post', function () {

    check_ajax_referer('delete_material_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);

    if (!current_user_can('delete_post', $post_id)) {
        wp_send_json_error('Permission denied');
    }

    wp_delete_post($post_id, true);

    wp_send_json_success();
});

function covertDateToReadableFormat($dateString)
{
    $date = DateTime::createFromFormat('Ymd', $dateString);
    if ($date) {
        return $date->format('F j, Y');
    }
    return '';
}
