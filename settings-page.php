<?php

// Add the settings page
add_action('admin_menu', 'walmart_api_connector_menu');
function walmart_api_connector_menu() {
    add_options_page('Walmart API Connector', 'Walmart API', 'manage_options', 'walmart-api-connector', 'walmart_api_connector_options_page');
}

// Render the settings page
function walmart_api_connector_options_page() {
    ?>
    <div class="wrap">
        <h1>Walmart API Connector</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('walmart_api_connector_group');
            do_settings_sections('walmart-api-connector');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'walmart_api_connector_settings');
function walmart_api_connector_settings() {
    register_setting('walmart_api_connector_group', 'walmart_api_key');

    add_settings_section('walmart_api_connector_section', '', null, 'walmart-api-connector');

    add_settings_field('walmart_api_key', 'API Key', 'walmart_api_key_field', 'walmart-api-connector', 'walmart_api_connector_section');
}

// Render the API key field
function walmart_api_key_field() {
    $api_key = get_option('walmart_api_key');
    echo '<input type="text" name="walmart_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
}
