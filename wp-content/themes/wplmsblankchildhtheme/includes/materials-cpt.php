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

function get_image_by_id($image_id)
{
    if (!empty($image_id)) {
        $image_url = wp_get_attachment_url($image_id);
        return $image_url;
    }
}

function get_youtube_thumbnail($url, $quality = 'hqdefault')
{
    // Extract video ID
    preg_match('/(youtu\.be\/|youtube\.com\/(embed\/|watch\?v=))([^?&]+)/', $url, $matches);

    if (!empty($matches[3])) {
        $video_id = $matches[3];
        $thumbnail_url = "https://img.youtube.com/vi/{$video_id}/{$quality}.jpg";
        return $thumbnail_url;
    }

    return false;
}

function get_edit_link($item_details)
{

    if ($item_details['type'] == 'tire-photo') {
        return home_url() . '/tire-photo-page/?edit=' . $item_details['post_id'];
    }

    if ($item_details['type'] == 'print-ads') {
        return home_url() . '/print-ads/?edit=' . $item_details['post_id'];
    }

    if ($item_details['type'] == 'radio') {
        return home_url() . '/radio-page/?edit=' . $item_details['post_id'];
    }

    if ($item_details['type'] == 'nittologo') {
        return home_url() . '/nitto-logo-page/?edit=' . $item_details['post_id'];
    }

    if ($item_details['type'] == 'television-and-video') {
        return home_url() . '/television-and-video-page/?edit=' . $item_details['post_id'];
    }

    if ($item_details['type'] == 'web-online') {
        return home_url() . '/web-online-page/?edit=' . $item_details['post_id'];
    }
}

function is_user_can_edit($user_id, $permission_type = '')
{
    if (!$user_id) {
        return false;
    }
    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }
    if ($user) {
        $user_role = $user->roles[0];
        if (in_array($user_role, ['administrator', 'um_super_user', 'super_user'])) {
            if (!empty($user->caps[$permission_type])) {
                return true;
            }
        }
    }

    return false;
}
