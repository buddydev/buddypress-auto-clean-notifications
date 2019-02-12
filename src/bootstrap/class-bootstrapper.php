<?php
/**
 * Bootstrapper. Initializes the plugin.
 *
 * @package    BP_Auto_Clean_Notifications
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2019, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

namespace BP_Auto_Clean_Notifications\Bootstrap;

use BP_Auto_Clean_Notifications\Handlers\Action_Handler;

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Bootstrapper.
 */
class Bootstrapper {

	/**
	 * Setup the bootstrapper.
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Bind hooks
	 */
	private function setup() {
		add_action( 'bp_loaded', array( $this, 'load' ), 1 );
		add_action( 'bp_init', array( $this, 'load_translations' ) );
	}

	/**
	 * Load core functions/template tags.
	 * These are non auto loadable constructs.
	 */
	public function load() {
		Action_Handler::boot();
	}

	/**
	 * Load translations.
	 */
	public function load_translations() {
		load_plugin_textdomain( 'buddypress-auto-clean-notifications', false, basename( bp_auto_clean_notifications()->path ) . '/languages' );
	}
}
