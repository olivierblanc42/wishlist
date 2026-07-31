<?php

// Register the REST API endpoint to toggle a wishlist item
add_action('rest_api_init', function () {
    register_rest_route(
        'wishlist/v1',
        '/toggle',
        [
            'methods' => 'POST',
            'callback' => 'toggle_wishlist_item',
            'permission_callback' => function () {
                return is_user_logged_in();
            },
        ]
    );
});

// Add or remove a post from the user's wishlist
function toggle_wishlist_item(WP_REST_Request $request)
{
    // Check if the user is logged in. If not, return an error.
    if (!is_user_logged_in()) {
        return new WP_Error(
            'not_logged_in',
            'You must be logged in..',
            ['status' => 401]
        );
    }

    // Retrieve the current user ID
    $userId = get_current_user_id();

    // Retrieve the post ID from WP_REST_Request and convert it to an integer
    $postId = (int) $request->get_param('postId');

    // check if the post exists
    if (!get_post($postId)) {
        return new WP_Error(
            'post_not_found',
            'The post does not exist.',
            ['status' => 404]
        );
    }

    // Retrieve the user's wishlist from user meta
    $wishlist = get_user_meta($userId, 'wishlist', true);

    // If the wishlist is not an array, initialize it as an empty array
    if (!is_array($wishlist)) {
        $wishlist = [];
    }

    // Ensure all wishlist values are integers
    $wishlist = array_map('intval', $wishlist);

    // Search for the post ID in the wishlist
    $key = array_search($postId, $wishlist, true);

    if ($key === false) {
        // Add the post ID to the wishlist
        $wishlist[] = $postId;
    } else {
        // Remove the post ID from the wishlist
        unset($wishlist[$key]);
        $wishlist = array_values($wishlist);
    }

    // Update the user's wishlist in the database
    update_user_meta($userId, 'wishlist', $wishlist);

    return rest_ensure_response($wishlist);
}

// Display the wishlist button and set the active state if the post is in the wishlist
function wishlist_button(int $postId)
{
    $class = '';

    if (is_user_logged_in()) {

        // Retrieve the current user ID
        $userId = get_current_user_id();

        // Retrieve the user's wishlist from user meta
        $wishlist = get_user_meta($userId, 'wishlist', true);

        // If the wishlist is not an array, initialize it as an empty array
        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        if (in_array((int) $postId, $wishlist, true)) {
            $class = ' active';
        }
    }

    echo '<button class=" btn-wishlist button-login-required' . esc_attr($class) . '" data-post-id="' . esc_attr($postId) . '"></button>';
}



// Retrieve the current user's wishlist
function get_user_wishlist()
{
    // Retrieve the current user ID
    $userId = get_current_user_id();
    // Retrieve the user's wishlist from user meta
    $wishlist = get_user_meta($userId, 'wishlist', true);

    // If the wishlist is not an array, initialize it as an empty array
    if (!is_array($wishlist)) {
        $wishlist = [];
    }
    // Create an array to store the wishlist posts
    $postArray = [];

    // Retrieve each post from the wishlist

    foreach ($wishlist as $postId) {
        $post = get_post($postId);

         // Add the post object to the array
        if ($post) {
            $postArray[] = $post;
        }
    }

    return $postArray;
}



// Display the user's wishlist
function display_wishlist()
{
    // Retrieve the wishlist posts
    $postArray = get_user_wishlist();

    // Display each wishlist item
    foreach ($postArray as $post) {
        $post_id = $post->ID;

        // Display the featured image
        echo get_the_post_thumbnail(
            $post_id,
            'thumbnail',
            array('class' => 'bounce_button')
        );
    }
}