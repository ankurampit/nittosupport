<?php
function create_bulletinboards_cpt()
{
    $labels = array(
        'name'               => 'Bulletin Boards',
        'singular_name'      => 'Bulletin Board',
        'menu_name'          => 'Bulletin Boards',
        'name_admin_bar'     => 'Bulletin Board',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Bulletin Board',
        'edit_item'          => 'Edit Bulletin Board',
        'new_item'           => 'New Bulletin Board',
        'view_item'          => 'View Bulletin Board',
        'search_items'       => 'Search Bulletin Boards',
        'not_found'          => 'No bulletin boards found',
        'not_found_in_trash' => 'No bulletin boards found in trash',
    );

    $args = array(
        'label'             => 'Bulletin Boards',
        'labels'            => $labels,
        'public'            => true,
        'menu_icon'         => 'dashicons-media-document',
        'show_ui'           => true,
        'show_in_menu'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
        'has_archive'       => true,
        'rewrite'           => array('slug' => 'bulletinboards'),
    );

    register_post_type('bulletinboards', $args);
}
add_action('init', 'create_bulletinboards_cpt');

add_action('init', function () {
    global $wpdb;

    $wpdb->update(
        $wpdb->posts,
        array('post_type' => 'bulletinboards'),
        array('post_type' => 'bulletins')
    );
});
