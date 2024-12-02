<?php

if (!defined('ABSPATH')) exit;

/**
 * License manager module
 */
function mmwea_updater_utility() {
    $prefix = 'MMWEA_';
    $settings = [
        'prefix' => $prefix,
        'get_base' => MMWEA_PLUGIN_BASENAME,
        'get_slug' => MMWEA_PLUGIN_DIR,
        'get_version' => MMWEA_VERSION,
        'get_api' => 'https://download.geekcodelab.com/',
        'license_update_class' => $prefix . 'Update_Checker'
    ];

    return $settings;
}

register_activation_hook(__FILE__, 'mmwea_updater_activate');
function mmwea_updater_activate() {

    // Refresh transients
    delete_site_transient('update_plugins');
    delete_transient('mmwea_plugin_updates');
    delete_transient('mmwea_plugin_auto_updates');
}

require_once(MMWEA_PLUGIN_DIR_PATH . 'updater/class-update-checker.php');
