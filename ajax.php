<?php

function check_youtube_user($username = null) {
    $url = !empty($username) ? "http://gdata.youtube.com/feeds/api/users/{$username}" : false;

    if ($url !== false) {
        return @file_get_contents($url) ? true : false;
    } else {
        return false;
    }
}

if (isset($_GET['action']) && !empty($_GET['action'])) {

    switch ($_GET['action']) {
        case 'yt_user_verification':
            if (isset($_GET['user'])) {
                if (!empty($_GET['user'])) {
                    echo check_youtube_user($_GET['user']) ? 0 : 1;
                }else{
                    echo 2;
                }
            }else{
                echo 3;
            }
            break;
    }
}
?>
