<?php
/**
 * Plugin Name: Custom JWT Expiration
 * Description: Allows the admin to set the JWT expiration token manually.
 * Version: 1.0.0
 * Author: Tamjid
 * Author URI: https://teejaay.me
 */

// Add the JWT expiration setting to the admin menu
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
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_jwt_expiration');
            do_settings_sections('custom_jwt_expiration');
            submit_button();
            ?>
        </form>
    </div>
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
            'default' => 300,
        )
    );
}
add_action('admin_init', 'custom_jwt_expiration_init');

// Modify the JWT expiration time
function custom_jwt_expiration_filter($expiration)
{
    $custom_expiration = get_option('custom_jwt_expiration', 3600);
    return $custom_expiration;
}
add_filter('graphql_jwt_auth_expire', 'custom_jwt_expiration_filter', 10);

// Add a function to display the custom JWT expiration setting on the settings page
function custom_jwt_expiration_settings_field()
{
    $custom_expiration = get_option('custom_jwt_expiration', 3600);
    ?>
    <input type="number" name="custom_jwt_expiration" id="custom_jwt_expiration"
        value="<?php echo esc_attr($custom_expiration); ?>" />
    <p class="description">Set the JWT expiration time in seconds.</p>
    <?php
}
add_action('admin_init', 'custom_jwt_expiration_settings_field');