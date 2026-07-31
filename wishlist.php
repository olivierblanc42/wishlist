<?php

/**
 * Plugin Name: Wishlist
 * Plugin URI:  
 * Description: Add a wishlist system to a WordPress site.
 * Version:     1.0.0
 * Author:      Olivier Blanc
 * Author URI:  https://github.com/olivierblanc42
 * Text Domain: wishlist
 */


require_once plugin_dir_path(__FILE__) . 'includes/wishlist-functions.php';

function wishlist_enqueue_scripts()
{
    wp_enqueue_script(
        'wishlist-script',
        plugin_dir_url(__FILE__) . 'assets/js/scripts/wishlist.js',
        array(),
        null,
        true


    );

    wp_localize_script(
        'wishlist-script',
        'wpData',
        [
            'nonce' => wp_create_nonce('wp_rest'),
            'apiUrl' => home_url('/wp-json/wishlist/v1/'),
            'isLogin' => is_user_logged_in(),
            'loginUrl' => wp_login_url(),
        ]
    );

    wp_enqueue_style(
        'wishList-style',
        plugin_dir_url(__FILE__) . 'assets/css/main.css'
    );
};
add_action('wp_enqueue_scripts', 'wishlist_enqueue_scripts');



