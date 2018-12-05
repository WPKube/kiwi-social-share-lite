<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Kiwi_Social_Share_Settings
 */
class Kiwi_Social_Share_Settings {

	/**
	 * The single instance of Kiwi_Social_Share_Settings.
	 *
	 * @var    object
	 * @access   private
	 * @since    1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var    object
	 * @access   public
	 * @since    1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	/**
	 * Kiwi_Social_Share_Settings constructor.
	 *
	 * @param $parent
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		$this->base = 'kiwi_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ), array(
			$this,
			'add_settings_link'
		) );
	}

	/**
	 * Initialise settings
	 *
	 * @return void
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 *
	 * @return void
	 */
	public function add_menu_item() {
		/* start-lite-version */
		$page = add_menu_page( __( 'Kiwi', 'kiwi-social-share' ), __( 'Kiwi', 'kiwi-social-share' ), 'manage_options', $this->parent->_token . '_settings', array(
			$this,
			'settings_page'
		), 'dashicons-share-alt' );

		/*Add Kiwi upgrade page*/
		add_submenu_page( $this->parent->_token . '_settings', __( 'Upgrade to PRO', 'kiwi-social-share' ), __( 'Upgrade', 'kiwi-social-share' ), 'manage_options', 'kiwi-upgrade', array(
			$this,
			'render_pro_page',
		) );
		/* end-lite-version */


		$advanced_shortcodes = Kiwi_Social_Share_Helper::get_setting_value( 'advanced_shortcode_manager', false, 'kiwi_advanced_settings' );
		if ( $advanced_shortcodes ) {
			add_submenu_page( $this->parent->_token . '_settings', __( 'Shortcodes', 'kiwi-social-share' ), __( 'Shortcodes', 'kiwi-social-share' ), 'manage_options', 'edit.php?post_type=kiwi-shortcodes' );
		}
	}

	/**
	 * Add settings link to plugin list table
	 *
	 * @param  array $links Existing links
	 *
	 * @return array        Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . esc_html__( 'Settings', 'kiwi-social-share' ) . '</a>';
		array_push( $links, $settings_link );

		return $links;
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {

		$settings['standard'] = array(
			'title'  => '',
			'fields' => array(
				array(
					'id'       => 'general_settings',
					'callback' => array( 'Kiwi_Social_Share_Sanitizers', 'automatic' ),
				),

				array(
					'id'       => 'network_colors',
					'callback' => array( 'Kiwi_Social_Share_Sanitizers', 'multiple_hex_fields' ),
				),

				array(
					'id'       => 'social_identities',
					'callback' => array( 'Kiwi_Social_Share_Sanitizers', 'automatic' ),
				),

				array(
					'id'       => 'advanced_settings',
					'callback' => array( 'Kiwi_Social_Share_Sanitizers', 'automatic' ),
				),
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) {
					continue;
				}

				// Add section to page
				add_settings_section( $section, $data['title'], array(), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
	}

	/**
	 * Load settings page content
	 *
	 * @return void
	 */
	public function settings_page() {
		require_once KIWI_SOCIAL_SHARE_BASE . '/includes/backend/kiwi-social-share-backend.php';
	}

	/**
	 * Main Kiwi_Social_Share_Settings Instance
	 *
	 * Ensures only one instance of Kiwi_Social_Share_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see   Kiwi_Social_Share()
	 * @return Main Kiwi_Social_Share_Settings instance
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}

		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()


	/*
	 *
	 * Upgrade page : Free Vs Pro
	 *
	 */
	public function render_pro_page() {


		wp_enqueue_script( 'kiwi-admin' );

		$features = array(
			'color-options'                => array(
				'label'    => esc_html__( 'Network color options', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Select different types of color options for icons', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'social-networks'              => array(
				'label'    => esc_html__( 'Select social sharing platforms', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Pro version offers besides free version the following: Reddit, Email, Telegram, WhatsApp, Skype and Mix', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'social-bar'                   => array(
				'label'    => esc_html__( 'Article bar', 'saboxplugin' ),
				'sub'      => esc_html__( 'The social buttons can be shown before or after content (post, page, custom post).', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'article-bar'                  => array(
				'label'    => esc_html__( 'Enable Article Bar only on selected post types', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Select the post types where you want the article bar to show up.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'article-bar-button-shapes'    => array(
				'label'    => esc_html__( 'Buttons shape for article bar', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Change the shape of the social buttons.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'article-bar-button-group'     => array(
				'label'    => esc_html__( 'Button group style', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Change the style of the article bar group.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'article-bar-share-counts'     => array(
				'label'    => esc_html__( 'Show share counts', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Display the number of counts next to the social network icon.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'floating-bar'                 => array(
				'label'    => esc_html__( 'Floating bar', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Enalbe floating social bar.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'floating-bar-selected-posts'  => array(
				'label'    => esc_html__( 'Enable Floating Bar only on selected post types', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Select the post types where you want the floating bar to show up.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'floating-bar-buttons-shape'   => array(
				'label'    => esc_html__( 'Buttons shape for floating bar', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Change the shape of the social buttons.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'custom-metaboxes'             => array(
				'label'    => esc_html__( 'Custom metaboxes', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Adds Custom Metaboxes for page/post/cpt meta handling', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'custom-metaboxes'             => array(
				'label'    => esc_html__( 'Custom metaboxes for selected post types', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Adds Custom Metaboxes only on selected post types', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'click-to-tweet'               => array(
				'label'    => esc_html__( 'Click to tweet function', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Add a "Click to tweet" button in WordPress editor.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'click-to-tweet-selected'      => array(
				'label'    => esc_html__( 'Click to tweet function on selected post types', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Add a "Click to tweet" button in WordPress editor only on selected post types.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'tweet-selected-text'          => array(
				'label'    => esc_html__( 'Tweet selected text ', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Allows tweeting the current selected text in the page.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'tweet-selected-text-selected' => array(
				'label'    => esc_html__( 'Tweet selected text on selected post types', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Allows tweeting the current selected text only on selected post types.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-no-alt"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'google-analytics-tracking'    => array(
				'label'    => esc_html__( 'Google Analytics tracking', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Track the click events on your social networks.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'shortcode-manager'            => array(
				'label'    => esc_html__( 'Shortcode manager', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Enable the advanced shortcode manager.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
			'whatsapp-desktop'             => array(
				'label'    => esc_html__( 'WhatsApp icon visible on desktop browsers', 'kiwi-social-share' ),
				'sub'      => esc_html__( 'Desktop browsers can\'t handle WhatsApp sharing correctly, you can enable/disable the visibility of the icon by toggling this option.', 'kiwi-social-share' ),
				'kiwi'     => '<span class="dashicons dashicons-yes"></span>',
				'kiwi-pro' => '<span class="dashicons dashicons-yes"></span></i>',
			),
		);

		?>

        <div class="wrap about-wrap kiwi-social-share-wrap">
            <h1><?php echo esc_html__( 'Why you should be upgrading', 'saboxplugin' ); ?></h1>
            <p class="about-text"><?php echo esc_html__( 'Introducing one of the best social sharing systems ever made for WordPress. Kiwi Social Share is an exquisite WordPress social share plugin perfectly fit for any needs. We\'ve outlined the PRO features below.', 'saboxplugin' ); ?></p>
            <div class="wp-badge"></div>
            <h2 class="nav-tab-wrapper wp-clearfix">
                <a href="<?php echo admin_url( 'admin.php?page=sab-upgrade' ); ?>"
                   class="nav-tab nav-tab-active"><?php echo esc_html__( 'Comparison Table: Lite vs PRO', 'saboxplugin' ); ?></a>
            </h2>
            <div class="featured-section features">
                <table class="free-pro-table kiwi">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php _e( 'Free', 'saboxplugin' ); ?></th>
                        <th><?php _e( 'PRO', 'saboxplugin' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $features as $feature ) : ?>
                        <tr>
                            <td class="feature">
                                <h3><?php echo $feature['label']; ?></h3>
								<?php if ( isset( $feature['sub'] ) ) : ?>
                                    <p><?php echo $feature['sub']; ?></p>
								<?php endif ?>
                            </td>
                            <td class="sab-feature">
								<?php echo $feature['kiwi']; ?>
                            </td>
                            <td class="sab-pro-feature">
								<?php echo $feature['kiwi-pro']; ?>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    <tr>
                        <td></td>
                        <td colspan="2" class="text-right">
                            <a href="https://www.machothemes.com/item/kiwi-pro/?utm_source=kiwi&utm_medium=about-page&utm_campaign=upsell"
                               target="_blank" class="button button-primary button-hero">
                                <span class="dashicons dashicons-cart"></span>
								<?php _e( 'Get The Pro Version Now!', 'kiwi-social-share' ); ?>
                            </a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
		<?php
	}

}