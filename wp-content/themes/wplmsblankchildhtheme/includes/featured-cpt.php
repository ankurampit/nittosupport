<?php
function create_featured_items_cpt()
{
    $labels = array(
        'name'               => 'Featured Items',
        'singular_name'      => 'Featured Item',
        'menu_name'          => 'Featured Items',
        'name_admin_bar'     => 'Featured Item',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Featured Item',
        'edit_item'          => 'Edit Featured Item',
        'new_item'           => 'New Featured Item',
        'view_item'          => 'View Featured Item',
        'search_items'       => 'Search Featured Items',
        'not_found'          => 'No featured items found',
        'not_found_in_trash' => 'No featured items found in trash',
    );

    $args = array(
        'label'             => 'Featured Items',
        'labels'            => $labels,
        'public'            => true,
        'menu_icon'         => 'dashicons-media-document',
        'show_ui'           => true,
        'show_in_menu'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
        'has_archive'       => true,
        'rewrite'           => array('slug' => 'featured'),
    );

    register_post_type('featured', $args);
}
add_action('init', 'create_featured_items_cpt');