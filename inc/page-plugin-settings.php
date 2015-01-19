<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="wrap">
    <h2><?php echo MSD_PLUGIN_NAME . ' Settings' ?></h2>

    <?php
    /* Settings Updated notification
      if ('true' == esc_attr($_GET['updated']))
      echo '<div class="updated" ><p>Plugin Settings updated.</p></div>';
     */
    if (isset($_GET['tab']))
        msd_yc_plugin_admin_tabs($_GET['tab']);
    else
        msd_yc_plugin_admin_tabs('yt_settings');
    ?>

    <div id="poststuff">
        <form method="post" action="<?php admin_url('options-general.php?page=msd-youtube-channel-plugin-settings'); ?>">
            <?php
            wp_nonce_field("msd-yc-plugin-settings");

            if ($pagenow == 'options-general.php' && $_GET['page'] == 'msd-youtube-channel-plugin-settings') {

                if (isset($_GET['tab']))
                    $tab = $_GET['tab'];
                else
                    $tab = 'yt_settings';

                echo '<table class="form-table">';
                switch ($tab) {
                    case 'yt_settings' :
                        ?>
                        <tr>
                            <th><label for="yt_username">YouTube username:</label></th>
                            <td>
                                <input id="yt_username" name="yt_username" type="text" <?php if ($settings["yt_username"]) echo "value=\"{$settings["yt_username"]}\""; ?> />
                                <span id='mycp-message' class="description"></span><br />
                                <span class="description">YouTube username to select videos from.</span>
                            </td>
                        </tr>
                        <?php
                        break;
                    case 'plugin_settings' :
                        ?>
                        <tr>
                            <th><label for="plugin_post_type">Post type:</label></th>
                            <td>
                                <select name="plugin_post_type"><?php
                                    $postTypes = msd_yc_plugin_get_post_types();
                                    if (is_array($postTypes)) {
                                        foreach ($postTypes as $postType) {
                                            $selected = ( $postType == $settings['plugin_post_type'] ) ? " selected" : "";
                                            printf("<option value='%s'%s>%s</option>", $postType, $selected, $postType);
                                        }
                                    }
                                    ?></select><br />
                                <span class="description">Select which type of post do you want to enable the plugin for.</span>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="plugin_post_status">Post type:</label></th>
                            <td>
                                <select name="plugin_post_status"><?php
                                    $postStatuses = array('publish', 'pending', 'draft', 'private', 'trash');
                                    if (is_array($postStatuses)) {
                                        foreach ($postStatuses as $postStatus) {
                                            $selected = ( $postStatus == $settings['plugin_post_status'] ) ? " selected" : "";
                                            printf("<option value='%s'%s>%s</option>", $postStatus, $selected, $postStatus);
                                        }
                                    }
                                    ?></select><br />
                                <span class="description">Select which type of post do you want to enable the plugin for.</span>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="plugin_results_limit">Results limit:</label></th>
                            <td>
                                <select name="plugin_results_limit"><?php
                                    for ($i = 10; $i <= 50; $i+=10) {
                                        $selected = ( $i == $settings['plugin_results_limit'] ) ? " selected" : "";
                                        printf("<option value='%s'%s>%s</option>", $i, $selected, $i);
                                    }
                                    ?></select><br />
                                <span class="description">Select number of table rows per page.</span>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="plugin_post_as">Post videos as:</label></th>
                            <td>
                                <select name="plugin_post_as"><?php
                                    // get_current_user_id( ); // gets current user ID!

                                    $userList = array_merge(
                                            array(array('user_id' => 0, 'user_name' => 'currently logged in user')), msd_yc_plugin_get_users_data());

                                    foreach ($userList as $user) {
                                        $selected = ( $user['user_id'] == $settings['plugin_post_as'] ) ? " selected" : "";
                                        printf("<option value='%s'%s>%s</option>", $user['user_id'], $selected, $user['user_name']);
                                    }
                                    ?></select><br />
                                <span class="description">Select user to post as.</span>
                            </td>
                        </tr>
                        <?php
                        break;
                }
                echo '</table>';
            }
            ?>
            <p class="submit" style="clear: both;">
                <input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
                <input type="hidden" name="settings-submit" value="Y" />
            </p>
        </form>

        <?php
        include_once('page-footer.php')
        ?>
    </div>
</div>