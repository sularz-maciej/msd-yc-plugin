<?php

/*
  Plugin Name: MSDesign YouTube Channel Plugin
  Plugin URI: http://msdesign.me/wordpress-plugins/msdesign-youtube-channel-plugin/
  Description: opracuj opis
  Version: 1.0
  Author: Maciej Sularz
  Author URI: http://msdesign.me/about
  License: opracuj licencje
 */

// Includes
include('config.php');
include(MSD_PLUGIN_DIR_INCLUDES . '/msd-yc-plugin-helper-functions.php');
// *Digg Style Paginator Class
include(MSD_PLUGIN_DIR_INCLUDES . '/pagination.class.php');
// YouTube Class
include(MSD_PLUGIN_DIR_INCLUDES . '/youtube.class.php');

// Plugin initialisation
add_action('init',          'msd_yc_plugin_init');
add_action('admin_menu',    'msd_yc_plugin_page_init');

// Initialise settings
// Get settings from Database, if empty value is returned
// add default values
function msd_yc_plugin_init() {

    $settings = get_option("msd_yc_plugin_settings");

    if (empty($settings)) {
        $settings = array(
            'plugin_version' => MSD_PLUGIN_VERSION,
            'post_type' => 'page',
            'plugin_results_limit' => 20
        );

        add_option("msd_yc_plugin_settings", $settings, '', 'yes');
    }
}

function msd_yc_plugin_enqueue_css() {
    // enqueue styles
    wp_register_style(  'msd-yc-plugin-styles',               plugins_url(MSD_PLUGIN_DIR_STYLES . '/plugin.css',        __FILE__), false, null);
    wp_register_style(  'msd-yc-plugin-styles-pagination',    plugins_url(MSD_PLUGIN_DIR_STYLES . '/pagination.css',    __FILE__), false, null);
    wp_register_style(  'msd-yc-plugin-styles-prettyPhoto',   plugins_url(MSD_PLUGIN_DIR_STYLES . '/prettyPhoto.css',   __FILE__), false, null);
    
    wp_enqueue_style(   'msd-yc-plugin-styles');
    wp_enqueue_style(   'msd-yc-plugin-styles-pagination');
    wp_enqueue_style(   'msd-yc-plugin-styles-prettyPhoto');
}

function msd_yc_plugin_enqueue_script() {
    // enqueue scripts
    wp_register_script( 'msd-yc-plugin-scripts',             plugins_url(MSD_PLUGIN_DIR_SCRIPTS . '/scripts.js',             __FILE__),  array('jquery'), true);
    wp_register_script( 'msd-yc-plugin-scripts-prettyPhoto', plugins_url(MSD_PLUGIN_DIR_SCRIPTS . '/jquery.prettyPhoto.js',  __FILE__),  array('jquery'), true);
    
    wp_enqueue_script(  'msd-yc-plugin-scripts');
    wp_enqueue_script(  'msd-yc-plugin-scripts-prettyPhoto');
    
    $parameters = array(
        'pluginsUrl'    => plugins_url('', __FILE__),
        'userOk'        => 'User name is correct :-)',
        'userError_00'  => 'Error occured :(',
        'userError_01'  => 'Error: User name does not exist!',
        'userError_02'  => 'Error: Please type in user name...'
        );
    wp_localize_script( 'msd-yc-plugin-scripts', 'msdObject', $parameters );
}

function msd_yc_plugin_page_init() {

    // Creates variables from array keys
    extract(get_option("msd_yc_plugin_settings"));

    // Adds sub menu to the custom post type menu
    if ($plugin_post_type !== 'post' && post_type_exists($plugin_post_type)) {
        $sub_menu = add_submenu_page(
                'edit.php?post_type=' . $plugin_post_type, // The parent page of this submenu
                __('Custom Post Type Admin', 'mycPlugin'), // The screen title
                __('Add New From YouTube Channel', 'mycPlugin'), // The submenu title
                'edit_posts', // The capability required for access to this submenu
                'msd-yc-plugin', // The slug to use in the URL of the screen
                'msd_yc_plugin_page'                                // The function to call to display the screen
        );
    } else {
        // Adds sub menu to the built-in 'Posts' menu
        $sub_menu = add_posts_page(
                __('Custom Post Type Admin', 'mycPlugin'), // The screen title
                __('Add from YouTube Channel', 'mycPlugin'), // The submenu title
                'edit_posts', // The capability required for access to this submenu
                'msd-yc-plugin', // The slug to use in the URL of the screen
                'msd_yc_plugin_page'                            // The function to call to display the screen
        );
    }

    // Adds sub menu to the 'Settings' menu
    $settings_page = add_options_page(
            __('MSDesign YouTube Channel Plugin Settings', 'mycPlugin'), // The screen title
            __('MSD YouTube Channel Plugin Settings', 'mycPlugin'), // The submenu title
            'edit_plugins', // The capability required for access to this submenu
            'msd-youtube-channel-plugin-settings', // The slug to use in the URL of the screen
            'msd_yc_plugin_settings_page'                                   // The function to call to display the screen
    );

    // Performs msd_yc_plugin_load_settings_page() on plugin settings page load
    add_action("load-{$settings_page}", 'msd_yc_plugin_load_settings_page');

    // Enqueues scripts for plugin pages
    add_action('admin_print_scripts-' . $settings_page, 'msd_yc_plugin_enqueue_script');
    add_action('admin_print_scripts-' . $sub_menu,      'msd_yc_plugin_enqueue_script');

    // Enqueues styles for plugin pages
    add_action('admin_print_styles-' . $settings_page,  'msd_yc_plugin_enqueue_css');
    add_action('admin_print_styles-' . $sub_menu,       'msd_yc_plugin_enqueue_css');
}

// Saves settings if $_POST variable is set and redirects to previous tab
function msd_yc_plugin_load_settings_page() {
    if ($_POST["settings-submit"] == 'Y') {
        msd_yc_plugin_save_settings();
        $url_parameters = isset($_GET['tab']) ? 'updated=true&tab=' . $_GET['tab'] : 'updated=true';
        wp_redirect(admin_url('options-general.php?page=msd-youtube-channel-plugin-settings&' . $url_parameters));
        exit;
    }
}

function msd_yc_plugin_save_settings() {
    global $pagenow;
    $settings = get_option("msd_yc_plugin_settings");

    if ($pagenow == 'options-general.php' && $_GET['page'] == 'msd-youtube-channel-plugin-settings') {
        if (isset($_GET['tab']))
            $tab = $_GET['tab'];
        else
            $tab = 'yt_settings';

        switch ($tab) {
            case 'plugin_settings' :
                $settings['plugin_post_type']       = $_POST['plugin_post_type'];
                $settings['plugin_post_status']     = $_POST['plugin_post_status'];
                $settings['plugin_results_limit']   = $_POST['plugin_results_limit'];
                $settings['plugin_post_as']         = $_POST['plugin_post_as'];
                break;
            case 'yt_settings' :
                $settings['yt_username'] = $_POST['yt_username'];
                break;
        }
    }


    if (!current_user_can('unfiltered_html')) {
        if (is_array($settings) && !empty($settings)) {
            foreach ($settings as $setting) {
                if ($setting) {
                    $settings[$setting] = stripslashes(esc_textarea(wp_filter_post_kses($settings[$setting])));
                }
            }
        }
    }

    $updated = update_option("msd_yc_plugin_settings", $settings);
}

function msd_yc_plugin_settings_page() {
    global $pagenow;
    $settings = get_option("msd_yc_plugin_settings");


    include_once(MSD_PLUGIN_DIR_INCLUDES . '/page-plugin-settings.php');
}

function msd_yc_plugin_page() {
    global $pagenow;
    $settings = get_option("msd_yc_plugin_settings");

    if (!empty($settings['yt_username'])) {
        // Initialise YouTube class
        $youTube = new youTube($settings['yt_username']);
    }

    // Initialises pagination class
    $p = new pagination;
    $p->parameterName("p"); // Sets pagination parameter


    include_once(MSD_PLUGIN_DIR_INCLUDES . '/page-plugin-page.php');
}

// Gets list of users
function msd_yc_plugin_get_users_ids() {
    $users = array();
    $usersIDs = get_users(array('fields' => 'ID'));

    return $usersIDs;
}

// Gets list of users
function msd_yc_plugin_get_users_data($uid = false) {
    $users = array();
    $usersIDs = msd_yc_plugin_get_users_ids();

    if (!$uid) {
        foreach ($usersIDs as $id) {
            // http://codex.wordpress.org/Function_Reference/get_userdata
            $user_info = get_userdata($id);
            $users[] = array(
                'user_id' => $user_info->ID,
                'user_name' => $user_info->user_login,
                'user_displayname' => $user_info->display_name,
                'user_level' => $user_info->user_level,
                'first_name' => $user_info->user_firstname,
                'last_name' => $user_info->user_lastname
            );
        }
    } else {
        $user_info = get_userdata($uid);
        $users[] = array(
            'user_id' => $user_info->ID,
            'user_name' => $user_info->user_login,
            'user_displayname' => $user_info->display_name,
            'user_level' => $user_info->user_level,
            'first_name' => $user_info->user_firstname,
            'last_name' => $user_info->user_lastname
        );
    }

    return $users;
}

function msd_yc_plugin_add_video(array $videoData) {
    
    $postData = array(
        'post_title'    => $videoData['title'],
        'post_content'  => $videoData['description'],
        'post_status'   => $videoData['post_status'],
        'post_author'   => $videoData['post_as'] == 0 ? get_current_user_id( ) : $videoData['post_as'],
        // 'post_category' => array(8,39),
        'post_date'     => $videoData['date_published'],
        'post_date_gmt' => $videoData['date_published'],
        'post_type'     => $videoData['post_type'],
        //'tax_input' => Array
        //      (
        //          'post_tag' => 'tag1,tag2, tentego',
        //          'portfolio_category' => Array(13)
        //       ),
        'post_meta' => array
            (
            'action_value'      => 'video',
            'thumbnail_value'   => $videoData['thumb_url'],
            'preview_value'     => $videoData['thumb_url'],
            'custom_value'      => $videoData['video_url'],
            'crop_value'        => 'c'
        // 'description_value' => $videoData['desc'],
        // 'subtitle_value' => '!SUBTITLE - PEXETO'
        )
    );
    
    
    $postID = wp_insert_post($postData);

    foreach ($postData['post_meta'] as $k => $v) {
        add_post_meta($postID, $k, $v, true) || update_post_meta($postID, $k, $v);
    }

    return true;
}

?>