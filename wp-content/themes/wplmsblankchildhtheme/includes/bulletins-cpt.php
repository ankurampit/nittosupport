<?php
function create_bulletins_cpt()
{

    $labels = array(
        'name'               => 'Bulletins',
        'singular_name'      => 'Bulletin',
        'menu_name'          => 'Bulletins',
        'name_admin_bar'     => 'Bulletin',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Bulletin',
        'edit_item'          => 'Edit Bulletin',
        'new_item'           => 'New Bulletin',
        'view_item'          => 'View Bulletin',
        'search_items'       => 'Search Bulletins',
        'not_found'          => 'No bulletins found',
        'not_found_in_trash' => 'No bulletins found in trash',
    );

    $args = array(
        'label'             => 'Bulletins',
        'labels'            => $labels,
        'public'            => true,
        'menu_icon'         => 'dashicons-media-document',
        'show_ui'           => true,
        'show_in_menu'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
        'has_archive'       => true,
        'rewrite'           => array('slug' => 'bulletins'),
    );

    register_post_type('bulletins', $args);
}
add_action('init', 'create_bulletins_cpt');