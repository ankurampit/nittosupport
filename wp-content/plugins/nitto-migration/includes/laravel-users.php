<?php
function nitto_get_laravel_users(){

    $laravel_db = nitto_laravel_db();

    $users = $laravel_db->get_results(
        "SELECT id, name, email FROM users"
    );

    return $users;
}