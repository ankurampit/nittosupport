<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Laravel Database Connection
|--------------------------------------------------------------------------
*/

function nitto_laravel_db(){

    $db = new wpdb(
        'ankur',
        'Ankur@123',
        'nittosup_nittosupportlive',
        'localhost'
    );

    return $db;
}


/*
|--------------------------------------------------------------------------
| Magento Database Connection
|--------------------------------------------------------------------------
*/

function nitto_magento_db(){

    $db = new wpdb(
        'ankur',
        'Ankur@123',
        'promonit_nitopromo',
        'localhost'
    );

    return $db;
}

/*
|--------------------------------------------------------------------------
| Magento Database Connection For Point Of Purchase
|--------------------------------------------------------------------------
*/

function nitto_magento_pos_db(){

    $db = new wpdb(
        'ankur',
        'Ankur@123',
        'posnitto_dev',
        'localhost'
    );

    return $db;
}

