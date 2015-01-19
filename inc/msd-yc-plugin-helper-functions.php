<?php

/*
 * Returns array with registered post types.
 */

function msd_yc_plugin_get_post_types() {
    // Get all registered types of posts
    $post_types = get_post_types('', 'names');
    // Post types to exclude
    $to_exclude = array('attachment', 'revision', 'nav_menu_item');

    return array_diff($post_types, $to_exclude);
}

function msd_yc_plugin_admin_tabs($current = 'yt_settings') {
    $tabs = array('yt_settings' => 'YouTube Settings', 'plugin_settings' => 'Plugin Settings');
    $links = array();
    echo '<div id="icon-upload" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=msd-youtube-channel-plugin-settings&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}



?>