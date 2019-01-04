<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Kiwi_Social_Share_Social (Buttons)
 */
interface Kiwi_Social_Share_Interface_Social {
	/**
	 * Builds the Url of the button
	 *
	 * @return mixed
	 */
	public function build_url();

	/**
	 * Returns a string containing HTML code of the button
	 *
	 * @return mixed
	 */
	public function generate_output();

	/**
	 * Returns an int, number of shares the post has on a certain network
	 *
	 * @return mixed
	 */
	public function get_shared_count();

	/**
	 * Returns HTML Code
	 *
	 * @return mixed
	 */
	public function build_shared_count();
}