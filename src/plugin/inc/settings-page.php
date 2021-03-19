<?php
/**
 * Registers an options page for find-my-blocks
 *
 * @package FindMyBlocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'find_my_blocks_register_options_page' ) ) :
	/**
	 * Register the options page
	 */
	function find_my_blocks_register_options_page() {
		add_management_page(
			esc_html__( 'Find My Blocks', 'find-my-blocks' ), 
			esc_html__( 'Find My Blocks', 'find-my-blocks' ),
			'edit_posts',
			'find-my-blocks',
			'find_my_blocks_register_options_page_markup',
			0
		);
	}

	add_action( 'admin_menu', 'find_my_blocks_register_options_page' );
endif;

if ( ! function_exists( 'find_my_blocks_register_options_page_markup' ) ) :
	/**
	 * Markup for the options page. This will only display a
	 * div to render our React app.
	 */
	function find_my_blocks_register_options_page_markup() {
		?>
			<div class="wrap">
				<div id="find-my-blocks"></div>
				<div id="editor"></div>
			</div><!-- /.wrap -->
		<?php
	}
endif;
