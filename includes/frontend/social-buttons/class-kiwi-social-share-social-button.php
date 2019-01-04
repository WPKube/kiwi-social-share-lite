<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Kiwi_Social_Share_Social_Button
 */
abstract class Kiwi_Social_Share_Social_Button {
	/**
	 * @var
	 */
	public $platform;
	/**
	 * @var
	 */
	public $url;
	/**
	 * @var
	 */
	public $api_url;
	/**
		* @var
	 */
	public $settings;
	/**
	 * @var string
	 */
	public $post_id;


	/**
	 * Kiwi_Social_Share_Social_Button constructor.
	 */
	public function __construct() {
		$this->post_id = get_the_ID();
		if ( is_front_page() || is_archive() || is_date() || is_category() || is_home() ) {
			$this->post_id = 'fp';
		}
	}

	/**
	 * @param $id
	 *
	 * @return mixed|string
	 */
	public function get_excerpt_by_id( $id ) {
		return Kiwi_Social_Share_Helper::get_excerpt_by_id( $id );
	}

	/**
	 * @return string
	 */
	public function generate_anchor_class() {
		return 'kiwi-nw-' . $this->platform;
	}

	/**
	 * @param string $custom
	 *
	 * @return string
	 */
	public function generate_anchor_icon( $custom = '' ) {
		if ( ! empty( $custom ) ) {
			$this->platform = $custom;
		}

		return '<span class="kicon-' . esc_attr( $this->platform ) . '"></span>';
	}


	/**
	 * Returns an int, containing the number of shares
	 */
	public function get_shared_count() {
		$response = get_transient( 'kiwi_' . $this->post_id . '_share_count_transient' );
        //platforms where there are share counts to show
        $yes_counts = array(
            'facebook',
            'twitter',
            'pinterest',
            'linkedin'
        );

        if(in_array($this->platform ,$yes_counts)){
            return $response[ $this->platform ];
        } else {
            return '';
        }
	}

	/**
	 * Returns a string, HTML code
	 */
	public function build_shared_count() {

        $response = get_transient( 'kiwi_' . $this->post_id . '_share_count_transient' );

        $yes_counts = array(
            'facebook',
            'twitter',
            'pinterest',
            'linkedin'
        );

        if(false === $response){
            $condition = true;
        } else {
            $condition =  (in_array( $this->platform,$yes_counts));
        }
        if ( Kiwi_Social_Share_Helper::get_setting_value( 'share_counts', '' )  ) {
            if((false === $response || !isset($response[ $this->platform ]) || false === $response[ $this->platform ] || NULL === $response[ $this->platform ]) && $condition ){
                return '<span class="kiwi-share-count" no-transient="true">&nbsp;</span>';
            } else {
                return '<span class="kiwi-share-count">' . $this->get_shared_count() . '</span>';

            }

        };
		return '';
	}


	/**
	 * @param $id
	 *
	 * @return false|string
	 */
	public function get_current_page_url( $id ) {
		if ( $id === 'fp' ) {
			return get_home_url();
		}

		return get_the_permalink( $id );
	}
}