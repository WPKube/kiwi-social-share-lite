<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Kiwi_Social_Share_Social_Button_Google_Plus
 */
final class Kiwi_Social_Share_Social_Button_Google_Plus extends Kiwi_Social_Share_Social_Button implements Kiwi_Social_Share_Interface_Social {
	/**
	 * Kiwi_Social_Share_Social_Button_Google_Plus constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->platform = 'google-plus';
		$this->url      = $this->build_url();
		$this->api_url  = 'https://clients6.google.com/rpc';
	}

	/**
	 * @return string
	 */
	public function build_url() {
		$url = $this->get_current_page_url( $this->post_id );
		return '//plus.google.com/share?url=' . rawurlencode( $url );
	}

	/**
	 * @return string
	 */
	public function generate_output() {
		return '<a data-class="popup" data-network="' . esc_attr( $this->platform ) . '" class="' . esc_attr( $this->generate_anchor_class() ) . '" href="' . esc_url( $this->url ) . '" target="_blank" rel="nofollow">' . $this->generate_anchor_icon() . ' ' . $this->build_shared_count() . '</a>';
	}
}