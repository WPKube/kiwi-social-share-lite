<?php

if (!defined('ABSPATH')) {
    exit;
}

class Kiwi_Social_Share_Counts {


    public function kiwi_share_me() {

        if (isset($_POST['action']) && $_POST['action'] == 'social_counts') {

            $page_id = (isset($_POST['page_id']) && $_POST['page_id']) ? $_POST['page_id'] : false;
            $url = get_the_permalink($page_id);
            $socials = $_POST['socials'];
            $shares = array();

            foreach ($socials as $social) {
                switch ($social) {
                    case 'facebook':
                        $api_url = 'http://graph.facebook.com/?id=' . $url;
                        $response = wp_remote_get($api_url);
                        $share_counts = json_decode($response['body']);
                        $shares['facebook'] = $share_counts->share->share_count;
                        break;

                    case 'twitter' :
                        $api_url = 'http://opensharecount.com/count.json?url=' . $url;
                        $response = wp_remote_get($api_url);
                        $share_counts = json_decode($response['body']);
                        $shares['twitter'] = $share_counts->count;
                        break;

                    case 'pinterest':
                        $api_url = 'https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url;
                        $response = wp_remote_get($api_url, array('type' => '2.0'));
                        $response = preg_replace('/^receiveCount\((.*)\)$/', '\\1', $response['body']);
                        $share_counts = json_decode($response, true);
                        $shares['pinterest'] = $share_counts['count'];
                        break;

                    case 'linkedin' :
                        $api_url = 'http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json';
                        $response = wp_remote_get($api_url);
                        $share_counts = json_decode($response['body']);
                        $shares['linkedin'] = $share_counts->count;
                        break;

                }
            }

            set_transient('kiwi_' . $page_id . '_share_count_transient', $shares, 4 * HOUR_IN_SECONDS);

            $shares = json_encode($shares);
            echo $shares;
            wp_die();
        }
    }
}