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

	/**
	 * Component action.
	 *
	 * @var string
	 */
	public static $action = 'new_user_email_settings';

	/**
	 * Set up notification actions.
	 */
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

		add_action( 'user_register', $add_notification, 10, 2 );
	}

	/**
	 * Link to user notification settings.
	 *
	 * @param string $action            The kind of notification being rendered.
	 * @param int    $item_id           The primary item id.
	 * @param int    $secondary_item_id The secondary item id.
	 * @param int    $total_items       The total number of messaging-related notifications
	 *                                  waiting for the user.
	 * @param string $format            Return value format. 'string' for BuddyBar-compatible
	 *                                  notifications; 'array' for WP Toolbar. Default: 'string'.
	 *
	 * @return string Value of the notification link href attribute.
	 */
	public static function filter_link( $action, $item_id, $secondary_item_id, $total_items, $format ) {
		return trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ) . 'notifications';
	}

	/**
	 * Welcome! Now get to work.
	 *
	 * @param string $action            The kind of notification being rendered.
	 * @param int    $item_id           The primary item id.
	 * @param int    $secondary_item_id The secondary item id.
	 * @param int    $total_items       The total number of messaging-related notifications
	 *                                  waiting for the user.
	 * @param string $format            Return value format. 'string' for BuddyBar-compatible
	 *                                  notifications; 'array' for WP Toolbar. Default: 'string'.
	 *
	 * @return string Text content of the notification link.
	 */
	public static function filter_text( $action, $item_id, $secondary_item_id, $total_items, $format ) {
		return 'Welcome! Be sure to review your email preferences.';
	}

}
