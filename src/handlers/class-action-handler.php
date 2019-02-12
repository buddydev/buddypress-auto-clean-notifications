<?php
/**
 * Action handler class
 *
 * @package BP_Auto_Clean_Notifications
 * @subpackage Handlers
 */

namespace BP_Auto_Clean_Notifications\Handlers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Actions_Handler
 */
class Action_Handler {

	/**
	 * Class self boot
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup
	 */
	private function setup() {
		// Run when the cron job fires.
		add_action( 'bp_auto_clear_notifications_cleanup_task', array( $this, 'cleanup' ) );
	}

	/**
	 * Cleanup older notifications.
	 *
	 * @return false|int
	 */
	public function cleanup() {

		if ( ! bp_is_active( 'notifications' ) ) {
			return false;
		}

		global $wpdb;
		$table      = buddypress()->notifications->table_name;
		$older_than = $this->get_cleanup_days();

		$query = $wpdb->prepare( "DELETE FROM {$table} WHERE DATEDIFF(CURDATE(), date_notified ) > %d", $older_than );

		return $wpdb->query( $query );
	}

	/**
	 * Get the number of days after which notification will be removed.
	 *
	 * @return int
	 */
	private function get_cleanup_days() {
		return 120;// 30 days. update it to number of days you want to.
	}
}
