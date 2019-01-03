<?php

if (!defined('ABSPATH')) {
    exit;
}

class Kiwi_Social_Share_Counts {


    public function kiwi_share_me() {

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'social_counts') {

            $page_id = (isset($_REQUEST['page_id']) && $_REQUEST['page_id']) ? $_REQUEST['page_id'] : false;
            $url = get_the_permalink($page_id);
            $response = get_transient('kiwi_' . $page_id . '_share_count_transient');

            if (!empty($response)) {
                $shares = array(
                    'facebook'    => $response['facebook'],
                    'twitter'     => $response['twitter'],
                    'pinterest'   => $response['pinterest'],
                    'linkedin'    => $response['linkedin'],
                    'google_plus' => $response['google-plus'],
                );
                $shares = json_encode($shares);
                echo $shares;
                wp_die();

            } else {

                $socials = $_REQUEST['socials'];
                $shares = array();
                $shares_fix = array();

                foreach ($socials as $social) {
                    switch ($social) {
                        case 'facebook':
                            $api_url = 'http://graph.facebook.com/?id=' . $url;
                            $response = wp_remote_get($api_url);
                            $share_counts = json_decode($response['body']);
                            $shares['facebook'] = $share_counts->share->share_count;
                            $shares_fix['facebook'] = $share_counts->share->share_count;
                            break;

                        case 'twitter' :
                            $api_url = 'http://opensharecount.com/count.json?url=' . $url;
                            $response = wp_remote_get($api_url);
                            $share_counts = json_decode($response['body']);
                            $shares['twitter'] = $share_counts->count;
                            $shares_fix['twitter'] = $share_counts->count;
                            break;

                        case 'pinterest':
                            $api_url = 'https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url;
                            $response = wp_remote_get($api_url, array('type' => '2.0'));
                            $response = preg_replace('/^receiveCount\((.*)\)$/', '\\1', $response['body']);
                            $share_counts = json_decode($response, true);
                            $shares['pinterest'] = $share_counts['count'];
                            $shares_fix['pinterest'] = $share_counts['count'];
                            break;

                        case 'linkedin' :
                            $api_url = 'http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json';
                            $response = wp_remote_get($api_url);
                            $share_counts = json_decode($response['body']);
                            $shares['linkedin'] = $share_counts->count;
                            $shares_fix['linkedin'] = $share_counts->count;
                            break;

                        case 'google-plus':
                            $api_url = 'https://clients6.google.com/rpc';
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $api_url);
                            curl_setopt($curl, CURLOPT_POST, 1);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                            $curl_results = curl_exec($curl);
                            curl_close($curl);
                            $json = json_decode($curl_results, true);
                            $shares['google_plus'] = $json[0]['result']['metadata']['globalCounts']['count'];
                            $shares_fix['google-plus'] = $json[0]['result']['metadata']['globalCounts']['count'];
                            break;
                    }
                }


                set_transient('kiwi_' . $page_id . '_share_count_transient', $shares_fix, 4 * HOUR_IN_SECONDS);

                $shares = json_encode($shares);
                echo $shares;
                wp_die();
            }
        }
    }
}