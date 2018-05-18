<?php
/**
 * When a user joins a group, tell them about the group site if one exists.
 *
 * @package Hc_Notifications
 */

/**
 * Notification.
 */
class HC_Notification_Join_MLA_Forum extends HC_Notification {

	/**
	 * Component action.
	 *
	 * @var string
	 */
	public static $action = 'join_mla_forum';

	/**
	 * Set up notification actions.
	 */
	public static function setup_actions() {
		$add_notification = function( $group_id, $user_id ) {
			$mla_oid = groups_get_groupmeta( $group_id, 'mla_oid' );

			// Only add notifications for MLA forums.
			if ( ! $mla_oid ) {
				return;
			}

			$result = bp_notifications_add_notification(
				[
					'user_id'          => $user_id,
					'component_name'   => 'hc_notifications',
					'component_action' => self::$action,
					'item_id'          => $group_id,
				]
			);
		};

		add_action( 'groups_join_group', $add_notification, 10, 2 );
	}

	/**
	 * Link to the site.
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
		return bp_get_group_permalink( groups_get_group( $item_id ) );
	}

	/**
	 * You have been added to your group's site.
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
		$group = groups_get_group( $item_id );
		$text  = sprintf(
			'You\'ve been added to "%s" because of your MLA forum membership.',
			$group->name
		);

		if ( groups_is_user_admin( get_current_user_id(), $item_id ) ) {
			$text .= sprintf(
				' Because you\'re a primary member of this forum, you cannot leave this group directly on the Commons - change your forums on %s and your Commons membership will be automatically updated.',
				'mla.org'
			);
		}

		return $text;
	}

}
