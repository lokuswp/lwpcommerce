<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Admin {
	/**
	 * The current version of the plugin
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version the current version of the plugin.
	 */
	protected $version;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $slug The string used to uniquely identify this plugin.
	 */
	protected $slug;

	/**
	 * The Name of Plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $name The string used to uniquely identify this plugin.
	 */
	protected $name;

	/**
	 * Register the admin page class with all the appropriate WordPress hooks.
	 *
	 * @param  Options  $options
	 */
	public static function register( array $plugin ) {
		$admin = new self( $plugin['slug'], $plugin['name'], $plugin['version'] );

		add_action( 'admin_init', [ $admin, 'admin_init' ] );
		add_action( 'admin_menu', [ $admin, 'register_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $admin, 'enqueue_scripts' ] );
	}

	/**
	 * Constructor function.
	 *
	 * @param  object  $parent  Parent object.
	 */
	public function __construct( $slug, $name, $version ) {
		$this->slug    = $slug;
		$this->name    = $name;
		$this->version = $version;

		// Load Required File
		require_once LWPC_PATH . 'src/admin/settings/functions-tabs.php';
		require_once LWPC_PATH . 'src/admin/class-ajax.php';
		// require_once LWPC_PATH . 'admin/class-autosetup.php';
		// require_once LWPC_PATH . 'admin/class-dashboard.php';
		// require_once LWPC_PATH . 'admin/class-updater.php';
	}

	/**
	 * Initiatie Admin
	 *
	 * @return void
	 */
	public function admin_init() {
		// Redirect to License after activate plugin
		if ( get_option( 'lwpcommerce_activator_redirect' ) ) {
			delete_option( 'lwpcommerce_activator_redirect' );
			exit( wp_redirect( admin_url( 'admin.php?page=lwpcommerce' ) ) );
		}

		// Handle Ignoring Email Failure Notice
		if ( isset( $_GET['mail-failed-ignored'] ) ) {
			update_option( 'lwpcommerce_mail_error', false );
			header( "Refresh:0; url=" . get_admin_url() );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// $dev_css = WP_DEBUG == true ? '.css' : '-min.css';
		$dev_css = '.css';

		if ( isset( $_GET['page'] ) ) {
			if ( $_GET['page'] == 'lwpcommerce' || strpos( $_GET['page'], 'lwpcommerce-' ) !== false ) {
				// wp_enqueue_style('select2', LWPC_URL . 'assets/lib/select2/select2.min.css', array(), '4.1.0', 'all');

				wp_enqueue_style( 'spectre-exp', LWPC_URL . 'src/includes/libraries/css/spectre/spectre-exp.min.css', array(), '0.5.9', 'all' );
				wp_enqueue_style( 'spectre-icons', LWPC_URL . 'src/includes/libraries/css/spectre/spectre-icons.min.css', array(), '0.5.9', 'all' );
				wp_enqueue_style( 'spectre', LWPC_URL . 'src/includes/libraries/css/spectre/spectre.min.css', array(), '0.5.9', 'all' );

				//				wp_enqueue_style( $this->slug, LWPC_URL . 'backend/assets/css/admin-settings' . $dev_css, array(), $this->version, 'all' );
				wp_enqueue_style( 'wp-color-picker' );

				// Load css for report page
				if ( $_GET['page'] === 'lwpcommerce-order' ) {
					wp_enqueue_style( 'datatables-style', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.min.css', array(), $this->version, 'all' );
					wp_enqueue_style( 'datatables-style-buttons', LWPC_URL . 'src/includes/libraries/js/datatables/buttons.dataTables.min.css', array(), $this->version, 'all' );
					wp_enqueue_style( 'datatables-style-select', LWPC_URL . 'src/includes/libraries/js/datatables/select.dataTables.min.css', array(), $this->version, 'all' );

					wp_enqueue_style( 'orders-css', LWPC_URL . 'src/admin/assets/css/orders' . $dev_css, array(), $this->version, 'all' );
				}
			}
		}
		//
		//		if ( strpos( get_post_type( get_the_ID() ), 'lsdc-' ) !== false ) {
		//			wp_enqueue_style( $this->slug . '-product', LWPC_URL . 'backend/assets/css/admin-product' . $dev_css, array(), $this->version, 'all' );
		//		}
		//

		// Global Admin Styles
		wp_enqueue_style( $this->slug . '-global', LWPC_URL . 'src/admin/assets/css/admin-global' . $dev_css, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// $dev_js = WP_DEBUG == true ? '.js' : '-min.js';
		$dev_js = '.js';

		// Datatable
		wp_register_script( 'datatables', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.buttons.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-select', LWPC_URL . 'src/includes/libraries/js/datatables/datatables.select.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons-excel', LWPC_URL . 'src/includes/libraries/js/datatables/jszip.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'datatables-buttons-html5', LWPC_URL . 'src/includes/libraries/js/datatables/buttons.html5.min.js', array( 'jquery' ), $this->version, false );

		// Load Lib Admin Restrict only lwpcommerce Page
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'lwpcommerce' || strpos( get_post_type( get_the_ID() ), 'lwpc-' ) !== false || isset( $_GET['page'] ) && strpos(
			                                                                                                                                                         $_GET['page'],
			                                                                                                                                                         'lwpcommerce-'
		                                                                                                                                                         ) !== false ) {
			// Load Admin Setting Js
			if ( $_GET['page'] === 'lwpcommerce' ) {
				wp_enqueue_script( 'admin-setting', LWPC_URL . 'src/admin/assets/js/admin-setting' . $dev_js, array( 'jquery', 'wp-color-picker' ), $this->version, false );
				wp_localize_script( 'admin-setting', 'lwpc_admin', array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'  => wp_create_nonce( 'lwpc_admin_nonce' ),
					'plugin_url'  => LWPC_URL,
					//				'currency'    => lwpc_get_currency(),
					'translation' => $this->js_translation(),
				) );
			}

			// Load js for report page
			if ( $_GET['page'] === 'lwpcommerce-order' || $_GET['page'] === 'admin.php?page=lwpcommerce-statistics' ) {
				wp_enqueue_script(
					'orders-js',
					LWPC_URL . 'src/admin/assets/js/orders' . $dev_js,
					array( 'jquery', 'datatables', 'datatables-buttons', 'datatables-select', 'datatables-buttons-excel', 'datatables-buttons-html5' ),
					$this->version,
					false
				);
				wp_localize_script( 'orders-js', 'lwpc_orders', array(
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'  => wp_create_nonce( 'lwpc_admin_nonce' ),
					'plugin_url'  => LWPC_URL,
					'translation' => $this->js_translation(),
				) );
			}

			// Enquene Media For Administrator Only
			if ( current_user_can( 'manage_options' ) ) {
				wp_enqueue_media();
			}
		}
	}

	/**
	 * Javascript Translation Stack
	 *
	 * @return array
	 */
	public function js_translation() {
		return array(
			'delete_report' => __( 'Are you sure you want to delete this item ?', 'lwpcommerce' ),
		);
	}

	/**
	 * Register Menu in Admin Area
	 *
	 * lwpcommerce Settings
	 * Products
	 * Orders
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_admin_menu() {

		// Menu lwpcommerce in WP-ADMIN
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug,
			[ $this, 'admin_menu_callback' ],
			LWPC_URL . 'src/admin/assets/lwpcommerce.png',
			2
		);

		// Remove DUsplicate Menu Page -> Sub Menu
		add_submenu_page( $this->slug, '', '', 'manage_options', $this->slug, '__return_null' );
		remove_submenu_page( $this->slug, $this->slug );

		add_submenu_page(
			$this->slug,
			__( 'Settings', 'lwpcommerce' ),
			__( 'Settings', 'lwpcommerce' ),
			'manage_options',
			'admin.php?page=lwpcommerce&tab=settings',
			'',
			0
		);


		add_submenu_page(
			$this->slug,
			__( 'Shippings', 'lwpcommerce' ),
			__( 'Shippings', 'lwpcommerce' ),
			'manage_options',
			'admin.php?page=lwpcommerce&tab=shipping',
			'',
			1
		);

		$backbone = (array) apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		if ( ! in_array( 'lwpcommerce/lwpcommerce.php', $backbone ) ) {

			add_submenu_page(
				$this->slug,
				__( 'Be a Pro', 'lwpcommerce' ),
				__( 'Be a Pro', 'lwpcommerce' ),
				'manage_options',
				'admin.php?page=lwpcommerce&tab=extensions',
				'',
				9999
			);
		} else {
			add_submenu_page(
				$this->slug,
				__( 'Get More Extensions', 'lwpcommerce' ),
				__( 'Get More Extensions', 'lwpcommerce' ),
				'manage_options',
				'admin.php?page=lwpcommerce&tab=extensions',
				'',
				9999
			);
		}

		// Menu Products
		add_menu_page(
			__( 'Products', 'lwpcommerce' ),
			__( 'Products', 'lwpcommerce' ),
			'manage_options',
			'edit.php?post_type=product',
			'',
			LWPC_URL . 'src/admin/assets/svg/product.svg',
			3
		);

		// Submenu Product -> Categories
		add_submenu_page(
			'edit.php?post_type=product',
			__( 'Category', 'lwpcommerce' ),
			__( 'Category', 'lwpcommerce' ),
			'manage_options',
			'edit-tags.php?taxonomy=product-category&post_type=product',
			''
		);

		// Menu Orders
		$awaiting = get_option( 'lwpcommerce_order_awaiting' ) > 0 ? abs( get_option( 'lwpcommerce_order_awaiting' ) ) : 0;
		add_menu_page(
			__( 'Orders', 'lwpcommerce' ),
			$awaiting ? sprintf( ( __( 'Orders', 'lwpcommerce' ) . ' <span class="awaiting-mod">%d</span>' ), $awaiting ) : __( 'Orders', 'lwpcommerce' ),
			'manage_options',
			'lwpcommerce-order',
			[ $this, 'admin_menu_order' ],
			LWPC_URL . 'src/admin/assets/svg/order.svg',
			3
		);


		// Add Shortcode List to wp-admin > lwpcommerce > Appearence
		// require_once LWPC_PATH . 'backend/admin/class-shortcode-lists.php';
		// Admin\Shortcode_Lists::addShortcodeList( $this->slug, $this->name, array(
		//     ['shortcode' => '[lwpcommerce_storefront]', 'description' => __("Menampilkan Etalase", 'lwpcommerce')],
		//     ['shortcode' => '[lwpcommerce_checkout]', 'description' => __("Menampilkan Pembayaran", 'lwpcommerce')],
		// ));

		// // Add Switch Options to wp-admin > lwpcommerce > Appearence
		// require_once LWPC_PATH . 'backend/admin/class-switch-options.php';
		// Admin\Switch_Options::addOptions( $this->slug, $this->name, array(
		//     'lsdc_unique_code' => ['name' => __('Kode Unik', 'lwpcommerce'), 'desc' => __('Matikan/Hidupkan Kode Unik', 'lwpcommerce'), 'override' => false],
		// ));

	}

	/**
	 * Including Reports File
	 * When Clicking Menu Order.
	 *
	 * @return void
	 */
	public function admin_menu_order() {
		include_once LWPC_PATH . 'src/admin/orders/order.php';
	}

	/**
	 * Including settings lwpcommerce page
	 * when clikcing menu LSDDOnation
	 *
	 * @return void
	 */
	public function admin_menu_callback() {
		include_once LWPC_PATH . 'src/admin/settings/tab.php';
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of is forbidden' ) ), LWPC_VERSION );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of is forbidden' ) ), LWPC_VERSION );
	}
}
