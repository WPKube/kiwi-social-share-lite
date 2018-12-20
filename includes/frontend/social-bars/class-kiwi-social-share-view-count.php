<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Kiwi_Social_Share_Counts extends Kiwi_Social_Share_Social_Button  {

    public function __construct() {
        $this->post_id = get_the_ID();
    }

    public function test_get_shared_count() {
        $response = get_transient( 'kiwi_'.$this->post_id.'_share_count_transient' );

        if ( false === $response || empty( $response[ $this->platform ] ) ) {
            $api = $this->connect_to_api_url();

            if ( ! $api ) {
                $api = 0;
            }

            $response[ $this->platform ] = $api;

            set_transient( 'kiwi_'.$this->post_id.'_share_count_transient', $response, 2 * HOUR_IN_SECONDS );
        }

        echo json_encode($this->post_id );
    }

}