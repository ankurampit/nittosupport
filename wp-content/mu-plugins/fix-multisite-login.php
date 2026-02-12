<?php
/**
 * Fix multisite login redirect on localhost
 */
add_filter('login_redirect', function ($redirect_to, $requested, $user) {
    if (is_wp_error($user)) {
        return $redirect_to;
    }

    if (is_multisite()) {
        return admin_url();
    }

    return $redirect_to;
}, 10, 3);
