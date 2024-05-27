<?php
/*
Plugin Name: Ingredients Filter
Description: A plugin to connect to the Walmart API and fetch product information.
Version: 1.0
Author: Strong Anchor Tech
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include the settings page
include_once(plugin_dir_path(__FILE__) . 'includes/settings-page.php');

// Register activation hook
register_activation_hook(__FILE__, 'walmart_api_connector_activate');
function walmart_api_connector_activate() {
    // Set default options
    add_option('walmart_api_key', '');
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'walmart_api_connector_deactivate');
function walmart_api_connector_deactivate() {
    // Clean up options
    delete_option('walmart_api_key');
}

// Function to connect to the Walmart API
function connect_to_walmart_api() {
    $api_key = get_option('walmart_api_key');
    if (empty($api_key)) {
        return new WP_Error('no_api_key', 'API Key is missing.');
    }

    $endpoint = 'https://api.walmartlabs.com/v1/items';
    $response = wp_remote_get("$endpoint?apiKey=$api_key");

    if (is_wp_error($response)) {
        return $response;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data;
}

// Test the connection on the settings page
add_action('admin_notices', 'test_walmart_api_connection');
function test_walmart_api_connection() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $data = connect_to_walmart_api();
    if (is_wp_error($data)) {
        echo '<div class="notice notice-error"><p>' . $data->get_error_message() . '</p></div>';
    } else {
        echo '<div class="notice notice-success"><p>Successfully connected to the Walmart API.</p></div>';
    }
}
