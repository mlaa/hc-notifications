<?php
/**
 * When a user joins a group, tell them about the group site if one exists.
 *
 * @package Hc_Notifications
 */

/**
 * Notification.
 */
class HC_Notification_New_User_Email_Settings extends HC_Notification {

	public static $action = 'new_user_email_settings';

	public static function setup_actions() {
		$add_notification = function( $user_id ) {
			$result = bp_notifications_add_notification(
				[
					'user_id'          => $user_id,
					'component_name'   => 'hc_notifications',
					'component_action' => self::$action,
				]
			);
		};

		add_action( 'user_registerr', $add_notification, 10, 2 );
	}

	public static function filter_link( $action, $item_id, $secondary_item_id, $total_items, $format ) {
		return trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ) . 'notifications';
	}

	public static function filter_text( $action, $item_id, $secondary_item_id, $total_items, $format ) {
		return 'Welcome! Be sure to review your email preferences.';
	}

}
