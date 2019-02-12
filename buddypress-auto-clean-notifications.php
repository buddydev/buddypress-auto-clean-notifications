<?php
/**
 * Plugin Name: BuddyPress Auto Clean Notifications
 * Version: 1.0.0
 * Plugin URI: https://buddydev.com/introducing-buddypress-auto-clean-notifications
 * Description: Auto Clean BuddyPress Notifications after a certain period(days).
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Requires PHP: 5.3
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  buddypress-auto-clean-notifications
 * Domain Path:  /languages
 *
 * @package BP_Auto_Clean_Notifications
 **/

use BP_Auto_Clean_Notifications\Bootstrap\Autoloader;
use BP_Auto_Clean_Notifications\Bootstrap\Bootstrapper;
use BP_Auto_Clean_Notifications\Handlers\Task_Scheduler;

// No direct access over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BP_Auto_Clean_Notifications
 *
 * @property-read $path     string Absolute path to the plugin directory.
 * @property-read $url      string Absolute url to the plugin directory.
 * @property-read $basename string Plugin base name.
 * @property-read $version  string Plugin version.
 */
class BP_Auto_Clean_Notifications {

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Class instance
	 *
	 * @var BP_Auto_Clean_Notifications
	 */
	private static $instance = null;

	/**
	 * Plugin absolute directory path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin absolute directory url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Plugin Basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Protected properties. These properties are inaccessible via magic method.
	 *
	 * @var array
	 */
	private $secure_properties = array( 'instance' );

	/**
	 * BP_Skeleton constructor.
	 */
	private function __construct() {
		$this->bootstrap();
	}

	/**
	 * Get Singleton Instance
	 *
	 * @return BP_Auto_Clean_Notifications
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap the core.
	 */
	private function bootstrap() {
		$this->path     = plugin_dir_path( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->basename = plugin_basename( __FILE__ );

		// Load autoloader.
		require_once $this->path . 'src/bootstrap/class-autoloader.php';

		$autoloader = new Autoloader( 'BP_Auto_Clean_Notifications\\', __DIR__ . '/src/' );

		spl_autoload_register( $autoloader );

		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );

		Bootstrapper::boot();
	}

	/**
	 * On activation create table
	 */
	public function on_activation() {
		Task_Scheduler::schedule();
	}


	/**
	 * On deactivation. Do cleanup if needed.
	 */
	public function on_deactivation() {
		Task_Scheduler::unschedule();
	}

	/**
	 * Magic method for accessing property as readonly(It's a lie, references can be updated).
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {

		if ( ! in_array( $name, $this->secure_properties, true ) && property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		return null;
	}
}

/**
 * Helper to access singleton instance
 *
 * @return BP_Auto_Clean_Notifications
 */
function bp_auto_clean_notifications() {
	return BP_Auto_Clean_Notifications::get_instance();
}

bp_auto_clean_notifications();
