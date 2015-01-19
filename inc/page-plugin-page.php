<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//wp_redirect(); add_query_arg('status', 'success')
// wp_redirect(admin_url('options-general.php?page=msd-youtube-channel-plugin-settings&' . $url_parameters));

/*
  $url = 'http://www.example.com';
  $resp = get_headers($url, 1);
  print_r($resp[1]); */

//echo var_dump(msd_yc_plugin_check_youtube_user("TOPCAMERMAN2"));

if (!empty($_POST)) {
    if ($_POST['add-videos'] == "Y") {
        $errors = 0;
        $count = 0;
        foreach ($_POST['videos'] as $video) {
            if (isset($video['checked']) && $video['checked'] == true) {
                $count++;
                !msd_yc_plugin_add_video($video) ? $errors++ : null;
            }
        }
    }
}

// Pagination settings
$p->items($youTube->total_videos);
$p->limit($settings['plugin_results_limit']);
$p->target(sprintf("?%spage=msd-yc-plugin", !empty($_GET['post_type']) ? "post_type={$_GET['post_type']}&" : "" ));
$p->currentPage(isset($_GET['p']) ? $_GET['p'] : 1);
$p->adjacents(2);

// Get videos
$limit = $settings['plugin_results_limit'];
$start = (!isset($_GET['p']) || $_GET['p'] == 1) ? 1 : (($_GET['p'] - 1) * $limit) + 1;

// Returns YouTube user name if correct, returns false otherwise
$userExists = $youTube->user_name;
?>
<div class="wrap">
    <h2 class="slogan"><?php echo MSD_PLUGIN_NAME; ?><br />
        <span>an easy way to post your YouTube videos!</span></h2>
    <?php
    if ($userExists) {
        $list = $youTube->get_videos($limit, $start);
        ?>
        <table class="info">
            <tr>
                <td>YouTube username:</td><td><?php printf('<a href="http://www.youtube.com/user/%s/about" target="_blank">%s</a>', $settings['yt_username'], $settings['yt_username']); ?></td>
            </tr>
            <tr>
                <td>Total number of videos:</td><td><?php printf('<a href="http://www.youtube.com/user/%s/videos" target="_blank">%d</a>', $settings['yt_username'], $youTube->total_videos); ?></td>
            </tr>
            <tr>
                <td>Videos per page:</td><td><?php echo $limit; ?></td>
            </tr>
        </table>
        <?php
        /* Settings Updated notification */
        if (!empty($_POST)) {
            if ($_POST['add-videos'] == "Y") {
                if (isset($errors) && $errors == 0) {
                    if (isset($count) && $count == 0) {
                        echo '<div class="error mycp-notification" ><p>Please select at least one video!</p></div>';
                    } else {
                        $s = $count !== 1 ? "s" : "";
                        $link = admin_url((isset($_GET['post_type']) && post_type_exists($_GET['post_type']) ) ? "edit.php?post_type={$_GET['post_type']}" : "edit.php");
                        echo '<div class="updated mycp-notification" ><p>' . $count . ' video' . $s . ' successfully added! <a href="' . $link . '">Browse Videos</a></p></div>';
                    }
                } else {
                    echo '<div class="error mycp-notification" ><p>Errors occured!</p></div>';
                }
            }
        }
        ?>
        <form method="POST">

            <div class="pagination-above">
                <input type="submit" name="Submit"  class="button-primary mycp-submit-button" value="Add Selected Videos" />
                <?php
                $p->show();
                ?></div>
            <table class="widefat video-list">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="selectall" readonly /></th>
                        <th>Video Thumbnail</th>
                        <th>Video Title</th>
                        <th>Video Description</th>
                        <th>Video ID</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><input type="checkbox" class="selectall" readonly /></th>
                        <th>Video Thumbnail</th>
                        <th>Video Title</th>
                        <th>Video Description</th>
                        <th>Video ID</th>
                    </tr>
                </tfoot>
                <tbody><?php
                    $i = 1;
                    foreach ($list as $video) {
                        ?><tr>
                            <th>
                                <input type="hidden"    name="videos[<?php echo $i; ?>][username]"  value="<?php echo $settings['yt_username']; ?>" />
                                <input type="hidden"    name="videos[<?php echo $i; ?>][video_id]"  value="<?php echo $video['id']; ?>" />
                                <input type="hidden"    name="videos[<?php echo $i; ?>][video_url]" value="<?php echo $video['url']; ?>" />
                                <input type="checkbox"  name="videos[<?php echo $i; ?>][checked]" /></th>
                            <td>
                                <a href="<?php echo $video['url']; ?>" rel="lightbox[videos]"><img src="<?php echo $video['thumb']; ?>" width="96" height="72" alt="<?php echo $video['title']; ?>" /></a>
                                <input type="hidden"    name="videos[<?php echo $i; ?>][thumb_url]"     value="<?php echo $video['thumb']; ?>" />
                            </td>
                            <td><?php echo $video['title']; ?>
                                <input type="hidden"    name="videos[<?php echo $i; ?>][title]"         value="<?php echo $video['title']; ?>" />
                            </td>
                            <td><?php echo wp_trim_words($video['desc'], 20); ?>
                                <input type="hidden"    name="videos[<?php echo $i; ?>][description]"   value="<?php echo $video['desc']; ?>" />
                            </td>
                            <td><?php printf('<a href="%s" target="_blank">%s</s>', $video['url'], $video['id']); ?>
                                <input type="hidden" name="videos[<?php echo $i; ?>][post_as]"          value="<?php echo $settings['plugin_post_as']; ?>" />
                                <input type="hidden" name="videos[<?php echo $i; ?>][date_published]"   value="<?php echo $video['published']; ?>" />
                                <input type="hidden" name="videos[<?php echo $i; ?>][post_type]"        value="<?php echo $settings['plugin_post_type']; ?>" />
                                <input type="hidden" name="videos[<?php echo $i; ?>][post_status]"      value="<?php echo $settings['plugin_post_status']; ?>" />
                            </td>
                        </tr><?php
                        $i++;
                    }
                    ?></tbody>
            </table>
            <div class="pagination-below">
                <input type="submit" name="Submit"  class="button-primary mycp-submit-button" value="Add Selected Videos" />
                <input type="hidden" name="add-videos" value="Y" /><?php
                $p->show();
                ?></div>
        </form>
    <?php } else { ?>
        <div class="error mycp-notification" ><p>Please go to settings and set correct YouTube user name first...</p></div>
        <?php
    }
    include_once('page-footer.php');
    ?>
</div>