<?php
/**
 * Plugin Name: Full Site Editor (FSE) - Trash Template Changes
 * Description: Allows users to clear the Full Site Editor template modifications, forcing WordPress to regenerate them. This is useful for troubleshooting theme compatibility issues with FSE.
 * Version: 1.0.0
 * Author: Jess Planck
 * Author URI: https://swampthings.org
 * License: GPLv2 or later
 */

// Enqueue the plugin's CSS file.
function fse_clear_templates_css()
{
    wp_enqueue_style('fse-clear-templates-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('admin_enqueue_scripts', 'fse_clear_templates_css');

// Add a link to the WordPress admin bar to clear FSE templates.
function add_fse_clear_admin_bar_link( $wp_admin_bar )
{
    // Only show the link to administrators.
    if (! current_user_can('manage_options') ) {
        return;
    }

    $wp_admin_bar->add_node(
        [
        'id'    => 'fse-clear-templates', // ID of the admin bar link.
        'title' => '<span class="ab-icon dashicons dashicons-trash"></span> ' . __('Clear FSE Templates', 'fse-clear-templates-lang'), // Title of the admin bar link.
        'href'  => admin_url('admin.php?page=fse-clear-templates'), // URL of the settings page.
        'meta'  => [
            'target' => '_self',
            'title'  => __('Clear FSE Templates', 'fse-clear-templates-lang'),
        ],
         ] 
    );
}
add_action('admin_bar_menu', 'add_fse_clear_admin_bar_link', 999);

/**
 * Register the plugin's settings page under the Tools menu.
 */
function fse_clear_templates_settings_page()
{
    add_submenu_page(
        'tools.php', // Parent menu slug.
        __('Clear FSE Templates', 'fse-clear-templates-lang'), // Page title.
        __('Clear FSE Templates', 'fse-clear-templates-lang'), // Menu title.
        'manage_options', // Capability required to access the page.
        'fse-clear-templates', // Menu slug.
        'fse_clear_templates_settings_page_content' // Callback function to display the page content.
    );
}
add_action('admin_menu', 'fse_clear_templates_settings_page');

/**
 * Display the content of the plugin's settings page.
 */
function fse_clear_templates_settings_page_content()
{
    // Only allow administrators to access the page.
    if (! current_user_can('manage_options') ) {
        return;
    }

    // Check if the form has been submitted.
    if (isset($_GET['clear_fse_templates']) && $_GET['clear_fse_templates'] === 'true' ) {
        // Verify the nonce for security.
        check_admin_referer('fse_clear_templates_action');

        // Delete all FSE templates and template parts.
        delete_fse_templates();

        // Display a success message.
        echo '<div class="notice notice-success is-dismissible"><p>' . __('FSE templates and template parts have been cleared.', 'fse-clear-templates-lang') . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php _e('This tool allows you to clear all Full Site Editor templates and template parts. This is useful for troubleshooting theme compatibility issues with FSE.', 'fse-clear-templates-lang'); ?></p>
        <form method="get" action="">
            <input type="hidden" name="page" value="fse-clear-templates">
            <input type="hidden" name="clear_fse_templates" value="true">
            <?php wp_nonce_field('fse_clear_templates_action'); ?>
            <?php submit_button(__('Clear FSE Templates', 'fse-clear-templates-lang'), 'primary'); ?>
        </form>
    </div>
    <?php
}

/**
 * Deletes all existing Full Site Editor templates, template parts, and their revisions.
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 */
function delete_fse_templates()
{
    global $wpdb;

    $post_types = array('wp_template', 'wp_template_part');

    foreach ($post_types as $post_type) {
        // Get all post IDs for the current post type
        $post_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s",
                $post_type
            )
        );

        if (!empty($post_ids)) {
            // Delete all revisions
            $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_parent IN (" . implode(',', $post_ids) . ") AND post_type = 'revision'");

            // Delete all posts of the current post type
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->posts} WHERE post_type = %s",
                    $post_type
                )
            );

            // Delete post meta
            $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id IN (" . implode(',', $post_ids) . ")");
        }
    }

    // Clear the cache
    wp_cache_flush();
}
