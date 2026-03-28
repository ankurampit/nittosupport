<?php

/**
 * Dealer Resources Admin Structure
 */


/*---------------------------------------
  1. Create Dealer Resources Main Menu
---------------------------------------*/
function dealer_resources_main_menu()
{

    add_menu_page(
        'Dealer Resources',
        'Dealer Resources',
        'manage_options',
        'dealer_resources_main',
        '',
        'dashicons-portfolio',
        25
    );
}
add_action('admin_menu', 'dealer_resources_main_menu');


/*---------------------------------------
  2. Catalogue Download CPT
---------------------------------------*/
function catalogue_download_post_type()
{
    register_post_type('catalogue_download', array(
        'labels' => array(
            'name'          => 'Catalogue Downloads',
            'singular_name' => 'Catalogue Download',
            'add_new_item'  => 'Add New Catalogue',
            'edit_item'     => 'Edit Catalogue',
            'all_items'     => 'All Catalogues',
        ),
        'public'        => true,
        'supports'      => array('title'),
        'show_in_menu'  => 'dealer_resources_main',
        'show_in_rest'  => true,
        'has_archive'   => false,
    ));
}
add_action('init', 'catalogue_download_post_type');


/*---------------------------------------
  3. Brochures CPT
---------------------------------------*/
function brochures_post_type()
{
    register_post_type('brochures', array(
        'labels' => array(
            'name'          => 'Brochures',
            'singular_name' => 'Brochure',
            'add_new_item'  => 'Add New Brochure',
            'edit_item'     => 'Edit Brochure',
            'all_items'     => 'All Brochures',
        ),
        'public'        => true,
        'supports'      => array('title'),
        'show_in_menu'  => 'dealer_resources_main',
        'show_in_rest'  => true,
        'has_archive'   => false,
    ));
}
add_action('init', 'brochures_post_type');

function misc_documents_post_type()
{
    register_post_type('misc_documents', array(
        'labels' => array(
            'name'          => 'MISC Documents',
            'singular_name' => 'MISC Document',
            'add_new_item'  => 'Add New MISC Document',
            'edit_item'     => 'Edit MISC Document',
            'all_items'     => 'All MISC Documents',
        ),
        'public'        => true,
        'supports'      => array('title'),
        'show_ui'       => true,
        'show_in_menu'  => 'dealer_resources_main', // attach to main menu
        'show_in_rest'  => true,
        'has_archive'   => false,
    ));
}
add_action('init', 'misc_documents_post_type');


add_action('wp_ajax_delete_catalogue_post', 'delete_catalogue_post');
function delete_catalogue_post()
{

    // Security check
    if (
        !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'delete_catalogue_nonce')
    ) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    // Validate post ID
    if (!isset($_POST['post_id'])) {
        wp_send_json_error(['message' => 'No post ID']);
    }

    $post_id = intval($_POST['post_id']);

    // Check permission
    if (!current_user_can('delete_post', $post_id)) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    // Delete post
    wp_delete_post($post_id, true);

    wp_send_json_success(['message' => 'Deleted successfully']);
}


/* ================= DELETE MISC DOCUMENT ================= */

add_action('wp_ajax_delete_misc_document_post', 'delete_misc_document_post');

function delete_misc_document_post()
{

    // Security check
    if (
        !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'delete_misc_document_nonce')
    ) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    // Validate post ID
    if (!isset($_POST['post_id'])) {
        wp_send_json_error(['message' => 'No post ID']);
    }

    $post_id = intval($_POST['post_id']);

    // Check permission
    if (!current_user_can('delete_post', $post_id)) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    // Delete post permanently
    wp_delete_post($post_id, true);

    wp_send_json_success(['message' => 'Deleted successfully']);
}


add_action('wp_ajax_delete_brochure_post', 'delete_brochure_post');
function delete_brochure_post()
{
    // Security check
    if (
        !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'delete_brochure_nonce')
    ) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    // Validate post ID
    if (!isset($_POST['post_id'])) {
        wp_send_json_error(['message' => 'No post ID']);
    }

    $post_id = intval($_POST['post_id']);

    // Check permission
    if (!current_user_can('delete_post', $post_id)) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    // Delete post
    wp_delete_post($post_id, true);

    wp_send_json_success(['message' => 'Deleted successfully']);
}


/*---------------------------------------
  4. Regional Manager CPT
---------------------------------------*/
function regional_manager_post_type()
{
    register_post_type('regional_manager', array(
        'labels' => array(
            'name'          => 'Regional Managers',
            'singular_name' => 'Regional Manager',
            'add_new_item'  => 'Add New Regional Manager',
            'edit_item'     => 'Edit Regional Manager',
            'all_items'     => 'All Regional Managers',
        ),
        'public'        => true,
        'supports'      => array('title'),
        'show_ui'       => true,
        'show_in_menu'  => 'dealer_resources_main',
        'show_in_rest'  => true,
        'has_archive'   => false,
    ));
}
add_action('init', 'regional_manager_post_type');

// Delete Regional Manager Post
add_action('wp_ajax_delete_regional_manager_post', 'delete_regional_manager_post');
 
function delete_regional_manager_post()
{
    if (
        !isset($_POST['nonce']) ||
        !wp_verify_nonce($_POST['nonce'], 'delete_regional_manager_nonce')
    ) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }
 
    if (!isset($_POST['post_id'])) {
        wp_send_json_error(['message' => 'No post ID']);
    }
 
    $post_id = intval($_POST['post_id']);
 
    if (!current_user_can('delete_post', $post_id)) {
        wp_send_json_error(['message' => 'Permission denied']);
    }
 
    wp_delete_post($post_id, true);
 
    wp_send_json_success(['message' => 'Deleted successfully']);
}