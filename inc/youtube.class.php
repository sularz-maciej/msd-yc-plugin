<?php

class youTube {

    var $user_name;
    var $total_videos;
    var $list;

    function __construct($username) {


        $this->set_user_name($username);
        $this->get_total_videos();
        //$this->loop();
    }

    function get_all_videos() {
        for ($i = 1; $i <= $this->total_videos; $i+=50) {
            $feedURL = sprintf("http://gdata.youtube.com/feeds/api/users/%s/uploads?max-results=50&start-index=%d", $this->user_name, $i);
            $sxml = simplexml_load_file($feedURL);


            $count = 1;
            foreach ($sxml->entry as $entry) {
                $media = $entry->children('media', true);
                $watch = (string) $media->group->player->attributes()->url;
                $thumbnail = (string) $media->group->thumbnail[0]->attributes()->url;
                $published = explode("T", (string) $entry->published);
                $date = $published[0];

                $time = trim($published[1], '.000Z');


                $vidDATA = parse_str(parse_url($watch, PHP_URL_QUERY), $array);
                $vidID = $array['v'];
                $this->list[] = array(
                    'id' => $vidID,
                    'title' => (string) $media->group->title,
                    'desc' => (string) $media->group->description,
                    'url' => $watch,
                    'thumb' => $thumbnail,
                    'thumbHD' => sprintf("http://i.ytimg.com/vi/%s/hqdefault.jpg", $vidID),
                    'published' => "{$date} {$time}"
                );
                $count++;
            }
        }
    }

    function get_videos($max_results = 50, $start_index = 1) {
        if ($this->user_name) {
            $feedURL = sprintf("http://gdata.youtube.com/feeds/api/users/%s/uploads?max-results=%d&start-index=%d", $this->user_name, $max_results, $start_index);
            $sxml = simplexml_load_file($feedURL);


            $count = 1;
            foreach ($sxml->entry as $entry) {
                $media = $entry->children('media', true);
                $watch = (string) $media->group->player->attributes()->url;
                $thumbnail = (string) $media->group->thumbnail[0]->attributes()->url;
                $published = explode("T", (string) $entry->published);
                $date = $published[0];

                $time = trim($published[1], '.000Z');


                $vidDATA = parse_str(parse_url($watch, PHP_URL_QUERY), $array);
                $vidID = $array['v'];
                $list[] = array(
                    'id' => $vidID,
                    'title' => (string) $media->group->title,
                    'desc' => (string) $media->group->description,
                    'url' => $watch,
                    'thumb' => $thumbnail,
                    'thumbHD' => sprintf("http://i.ytimg.com/vi/%s/hqdefault.jpg", $vidID),
                    'published' => "{$date} {$time}"
                );
                $count++;
            }

            return (array) $list;
        }else{
            // Returns an empty array if the username is incorrect
            return array();
        }
    }

    private function get_total_videos() {
        if ($this->user_name) {
            $userData = json_decode(file_get_contents(sprintf("http://gdata.youtube.com/feeds/api/users/%s/uploads?v=2&alt=jsonc&max-results=0", $this->user_name)));
            $this->total_videos = $userData->data->totalItems;
        } else {
            $this->total_videos = 0;
        }
    }

    private function set_user_name($username) {
        $this->user_name = $this->check_youtube_user($username) ? $username : false;
    }

    private function check_youtube_user($username = null) {
        $url = !empty($username) ? "http://gdata.youtube.com/feeds/api/users/{$username}" : false;

        if ($url !== false) {
            return @file_get_contents($url) ? true : false;
        } else {
            return false;
        }
    }
}
?>
