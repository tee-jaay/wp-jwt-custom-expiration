<?php
/**
 * Plugin Name: Custom JWT Expiration
 * Description: Allows the admin to set the JWT expiration time manually in seconds.
 * Version: 1.0.1
 * Author: Tamjid
 * Author URI: https://teejaay.me
 * License: MIT License
 * License URI: https://teejaay.me/wp-content/plugins/custom-jwt-expiration/license.txt
 *
 * Requires at least: 6.3.1
 * Tested up to: 6.3.1
 * Requires PHP: 8.1.17
 * 
 * @package Custom_JWT_Expiration
 */

// Add the JWT expiration setting to the Settings menu
function custom_jwt_expiration_menu()
{
    add_options_page(
        'JWT Expiration',
        'JWT Expiration',
        'manage_options',
        'custom-jwt-expiration',
        'custom_jwt_expiration_settings_page'
    );
}
add_action('admin_menu', 'custom_jwt_expiration_menu');

// Display the JWT expiration settings page
function custom_jwt_expiration_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['custom_jwt_expiration'])) {
        update_option('custom_jwt_expiration', absint($_POST['custom_jwt_expiration']));
    }

    $customExpiration = get_option('custom_jwt_expiration', 60);

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <div class="custom-jwt-expiration-settings-field">
                <label for="custom_jwt_expiration">JWT Expiration (seconds):</label>
                <input type="number" name="custom_jwt_expiration" id="custom_jwt_expiration"
                    value="<?php echo esc_attr($customExpiration); ?>" />
                <p class="description">Set the JWT expiration time in seconds.</p>
            </div>
            <?php
            submit_button();
            ?>
        </form>
    </div>
    <style>
        /* Adjust the position of the form on the page */
        .wrap .custom-jwt-expiration-settings-field {
            margin-top: 20px;
        }
    </style>
    <?php
}

// Register the JWT expiration setting
function custom_jwt_expiration_init()
{
    register_setting(
        'custom_jwt_expiration',
        'custom_jwt_expiration',
        array(
            'type' => 'integer',
            'description' => 'Expiration time in seconds',
            'sanitize_callback' => 'absint',
            'default' => 60,
        )
    );
}
add_action('admin_init', 'custom_jwt_expiration_init');

// Set the JWT expiration time from the saved option
function custom_jwt_expiration_filter($expiration)
{
    $custom_expiration = get_option('custom_jwt_expiration', 60);
    return $custom_expiration;
}
add_filter('graphql_jwt_auth_expire', 'custom_jwt_expiration_filter', 10);
