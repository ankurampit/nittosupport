<?php

if (!defined('TEXT_DOMAIN'))
    define('TEXT_DOMAIN', 'nittosupport');

if (!defined('VIBE_URL'))
    define('VIBE_URL', get_template_directory_uri());

function nittosupport_register_menus()
{
    register_nav_menus([
        'header_menu' => __('Header Menu', 'nittosupport'),
    ]);
}
add_action('init', 'nittosupport_register_menus');

class Custom_Walker_Nav_Menu extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        // Replace 'sub-menu' with 'dropdown-menu' class
        $output .= '<ul class="dropdown-menu">';
    }
}


// Auto-load all PHP files inside /includes folder
foreach (glob(get_stylesheet_directory() . '/includes/*.php') as $file) {
    require_once $file;
}


/**
 * Auto-load all page templates from /templates/admaterials/ folder
 */
add_filter('theme_page_templates', function ($templates) {
    $template_dir = get_stylesheet_directory() . '/templates/admaterials/';

    // Get all PHP files inside admaterials folder
    foreach (glob($template_dir . '*.php') as $file) {
        $file_name = basename($file);

        // Read the Template Name from inside the file
        $contents = file_get_contents($file);
        if (preg_match('/Template Name:\s*(.+)/', $contents, $match)) {
            $template_name = trim($match[1]);
            $templates['templates/admaterials/' . $file_name] = $template_name;
        }
    }

    return $templates;
});


/**
 * Ensure WordPress loads templates from the custom folder
 */
add_filter('template_include', function ($template) {
    $requested_template = get_page_template_slug();

    if (!empty($requested_template) && strpos($requested_template, 'templates/admaterials/') === 0) {
        $new_template_path = get_stylesheet_directory() . '/' . $requested_template;
        if (file_exists($new_template_path)) {
            return $new_template_path;
        }
    }

    return $template;
});

add_filter('acf/validate_attachment', '__return_true');

add_filter('upload_mimes', function ($mimes) {
    $mimes['zip'] = 'application/zip';
    $mimes['eps'] = 'application/postscript';
    return $mimes;
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
}, 1);
add_action('init', function () {
    $user = wp_get_current_user();
    if (!$user || empty($user->roles)) return;

    foreach ($user->roles as $role_name) {
        $role = get_role($role_name);
        if (!$role) continue;

        $role->add_cap('upload_files');
        $role->add_cap('read');
        $role->add_cap('edit_posts');
    }
});
