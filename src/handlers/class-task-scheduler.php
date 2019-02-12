<?php
/**
 * Task Scheduler.
 *
 * @package BP_Auto_Clean_Notifications
 * @subpackage Handlers
 */

namespace BP_Auto_Clean_Notifications\Handlers;

// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}


/**
 * Class Task_Scheduler
 */
class Task_Scheduler {

	/**
	 * Setup hooks
	 */
	public static function schedule() {

		if ( ! wp_next_scheduled( 'bp_auto_clear_notifications_cleanup_task' ) ) {
			// Run hourly.
			wp_schedule_event( time(), 'hourly', 'bp_auto_clear_notifications_cleanup_task' );
		}
	}

	/**
	 * Un-schedule the cron job.
	 */
	public static function unschedule() {
		wp_unschedule_hook( 'bp_auto_clear_notifications_cleanup_task' );
	}

}
