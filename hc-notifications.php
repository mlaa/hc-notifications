<?php
/**
 * Plugin Name:     HC Notifications
 * Plugin URI:      https://github.com/mlaa/hc-notifications.git
 * Description:     HC Notifications
 * Author:          MLA
 * Author URI:      https://github.com/mlaa
 * Text Domain:     hc-notifications
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Hc_Notifications
 */

/**
 * Bootstrap the component.
 */
function hc_notifications_init_component() {
	require_once 'includes/class-hc-notifications-component.php';
	buddypress()->hc_notifications = new HC_Notifications_Component();
}
add_action( 'bp_loaded', 'hc_notifications_init_component' );

/**
 * Send new users a notification telling them to review their default notification settings.
 *
 * @param int $user_id User ID.
 */
function hc_notifications_user_register( $user_id ) {
	$action = 'new_user_notification_settings';

	add_filter( "hc_notifications_{$action}_link", function() {
		return trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ) . 'notifications';
	} );

	add_filter( "hc_notifications_{$action}_text", function() {
		return 'Welcome! Be sure to review your email preferences.';
	} );

	$notification_id = bp_notifications_add_notification(
		[
			'user_id'          => $user_id,
			'component_name'   => 'hc_notifications',
			'component_action' => $action,
		]
	);
}
add_action( 'user_register', 'hc_notifications_user_register' );

/**
 * Send new group site members a notification about their new role.
 *
 * @param int $group_id ID of the group.
 * @param int $user_id  Optional. ID of the user. Defaults to the currently
 *                      logged-in user.
 * @return bool True on success, false on failure.
 */
function hc_notifications_groups_join_group( $group_id, $user_id ) {
	$action  = 'new_group_site_member';
	$blog_id = get_groupblog_blog_id( $group_id );
	switch_to_blog( $blog_id );
	$blog_name = get_bloginfo( 'name' );
	$caps = array_keys( get_user_meta( $user_id, 'wp_capabilities', true ) );
	$role = $caps[0]; // Just report the first role for now.
	restore_current_blog();

	add_filter( "hc_notifications_{$action}_link", function() use ( $blog_id ) {
		return get_site_url( $blog_id );
	} );

	add_filter( "hc_notifications_{$action}_text", function() use ( $blog_name, $role ) {
		return sprintf(
			'You\'ve been added to the group site "%s" with the role of %s.',
			$blog_name,
			$role
		);
	} );

	$notification_id = bp_notifications_add_notification(
		[
			'user_id'          => $user_id,
			'component_name'   => 'hc_notifications',
			'component_action' => $action,
			'item_id'          => $group_id,
			'allow_duplicate'  => true,
		]
	);
}
add_action( 'groups_join_group', 'hc_notifications_groups_join_group', 10, 2 );
