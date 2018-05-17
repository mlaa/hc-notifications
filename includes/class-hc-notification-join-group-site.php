<?php
/**
 * When a user joins a group, tell them about the group site if one exists.
 *
 * @package Hc_Notifications
 */

/**
 * Notification.
 */
class HC_Notification_Join_Group_Site extends HC_Notification {

	/**
	 * Component action.
	 *
	 * @var string
	 */
	public static $action = 'new_group_site_member';

	/**
	 * Set up notification actions.
	 */
	public static function setup_actions() {
		$add_notification = function( $group_id, $user_id ) {
			$blog_id = get_groupblog_blog_id( $group_id );

			// Bail if this group has no blog.
			if ( ! $blog_id ) {
				return;
			}

			$result = bp_notifications_add_notification(
				[
					'user_id'           => $user_id,
					'component_name'    => 'hc_notifications',
					'component_action'  => self::$action,
					'item_id'           => $group_id,
					'secondary_item_id' => $blog_id,
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
		return get_site_url( $item_id );
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
		switch_to_blog( $secondary_item_id );
		$blog_name = get_bloginfo( 'name' );
		$caps      = array_keys( get_user_meta( get_current_user_id(), 'wp_capabilities', true ) );
		$role      = $caps[0]; // Just report the first role for now.
		restore_current_blog();

		return sprintf(
			'You\'ve been added to the group site "%s" with the role of %s.',
			$blog_name,
			$role
		);
	}

}
