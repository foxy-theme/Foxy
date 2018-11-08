<?php
/**
 * Foxy meta framework interface
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 * @license GPL
 */

/**
 * Foxy_Meta_Framework_Interface interface
 */
interface Foxy_Meta_Framework_Interface {
	public function get( $meta_key, $post_id = null, $is_single = true );

	/**
	 * Meta data content factory
	 *
	 * @param WP_Post $post_type Default argument in action add_meta_box.
	 * @param array   $fields Foxy meta data setting fields.
	 * @return void
	 */
	public function metabox_callback( $post_type, $fields );
}
