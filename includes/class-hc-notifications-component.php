<?php
/**
 * Component class.
 *
 * @package Hc_Notifications
 */

/**
 * Component.
 */
class HC_Notifications_Component extends BP_Component {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::start(
			'hc_notifications',
			'HC Notifications',
			plugin_dir_path( __DIR__ )
		);

		add_filter( 'bp_notifications_get_registered_components', [ $this, 'filter_registered_components' ] );
	}

	/**
	 * Set up globals.
	 *
	 * @param array $args Args.
	 */
	public function setup_globals( $args = [] ) {
		parent::setup_globals(
			[
				'notification_callback' => [ $this, 'format_notifications' ],
			]
		);
	}

	/**
	 * Notification formatter callback.
	 *
	 * @param string $action            The kind of notification being rendered.
	 * @param int    $item_id           The primary item id.
	 * @param int    $secondary_item_id The secondary item id.
	 * @param int    $total_items       The total number of messaging-related notifications
	 *                                  waiting for the user.
	 * @param string $format            Return value format. 'string' for BuddyBar-compatible
	 *                                  notifications; 'array' for WP Toolbar. Default: 'string'.
	 * @return string|array Formatted notifications.
	 */
	public function format_notifications( $action, $item_id, $secondary_item_id, $total_items, $format ) {
		$link = apply_filters_ref_array( "hc_notifications_${action}_link", func_get_args() );
		$text = apply_filters_ref_array( "hc_notifications_${action}_text", func_get_args() );

		if ( 'new_user_notification_settings' === $action ) {
			$link = trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ) . 'notifications';
			$text = 'Welcome! Be sure to review your email preferences.';
		}

		if ( 'string' === $format ) {
			$content = sprintf(
				'<a href="%1$s" title="%2$s">%2$s</a>',
				$link,
				$text
			);
		} else {
			$content = [
				'text' => $text,
				'link' => $link,
			];
		}

		return $content;
	}

	/**
	 * Register this component.
	 *
	 * @param array $component_names Array of registered component names.
	 */
	public function filter_registered_components( $component_names ) {
		$component_names[] = buddypress()->hc_notifications->id;
		return $component_names;
	}

}
