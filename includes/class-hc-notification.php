<?php
/**
 * Abstract class for all notifications in this plugin.
 *
 * @package Hc_Notifications
 */

/**
 * Implement this class to add a new notification.
 */
abstract class HC_Notification {

	/**
	 * Component action sent to bp_notifications_add_notification().
	 *
	 * @var string
	 */
	public static $action = '';

	/**
	 * Set up notification actions.
	 *
	 * This should call add_action() with a callback that calls bp_notifications_add_notification().
	 */
	public static function setup_actions() {}

	/**
	 * Filter notification text.
	 *
	 * This is automatically hooked to the relevant action by HC_Notifications_Component.
	 *
	 * @param array $args See HC_Notifications_Component->format_notifications().
	 *
	 * @return string Text content of the notification link.
	 */
	public static function filter_text( $action, $item_id, $secondary_item_id, $total_items, $format ) {}

	/**
	 * Filter notification link.
	 *
	 * This is automatically hooked to the relevant action by HC_Notifications_Component.
	 *
	 * @param array $args See HC_Notifications_Component->format_notifications().
	 *
	 * @return string Value of the notification link href attribute.
	 */
	public static function filter_link( $action, $item_id, $secondary_item_id, $total_items, $format ) {}

}
