<?php

/**
 * FLK Admin
 *
 * @class FLK_Admin
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
/**
 * FLK_Admin class.
 */
class FLK_Admin
{

	public function __construct()
	{
		add_action('init', array($this, 'includes'));
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes()
	{
		include_once __DIR__ . '/class-flk-admin-menus.php';
		include_once __DIR__ . '/class-flk-admin-metabox.php';
	}
}

return new FLK_Admin();
