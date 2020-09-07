<?php
/**
 * Registers an endpoint at /wp-json/find-my-blocks/blocks
 *
 * @package FindMyBlocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'find_my_blocks_register_route' ) ) :
	/**
	 * Register a custom endpoint that will allow us to get
	 * the posts needed for the block.
	 */
	function find_my_blocks_register_route() {
		register_rest_route(
			'find-my-blocks',
			'blocks',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'find_my_blocks_route_callback',
				'permission_callback' => '__return_true',
			)
		);
	}

	add_action( 'rest_api_init', 'find_my_blocks_register_route' );
endif;

if ( ! function_exists( 'find_my_blocks_route_callback' ) ) :
	/**
	 * Callback function that will get a list of blocks and
	 * the posts that they are used in
	 *
	 * @param WP_REST_Request $request - WordPress api request.
	 */
	function find_my_blocks_route_callback( WP_REST_Request $request ) {
		$blocks = array();

		/**
		 * Get an array of all of our post types, then we will
		 * remove any unwanted post types
		 */
		$post_types = get_post_types(
			array(
				'public'  => true,
				'show_ui' => true,
			)
		);

		array_push( $post_types, 'wp_block' );
		unset( $post_types['attachment'] );

		/**
		 * Get a list of all posts
		 */
		$post_ids = array();

		foreach ( $post_types as $key => $post_type ) {
			$posts = get_posts(
				array(
					'posts_per_page' => -1,
					'post_type'      => $post_type,
				)
			);

			foreach ( $posts as $post ) {
				array_push( $post_ids, $post->ID );
			}
		}

		/**
		 * Loop through post IDs and get the blocks that are used.
		 */
		foreach ( $post_ids as $post_ID ) {
			$post = get_post( $post_ID );

			if ( ! has_blocks( $post->post_content ) ) {
				continue;
			}

			$post_blocks = parse_blocks( $post->post_content );

			foreach ( $post_blocks as $block ) {
				/**
				 * If the block name is blank, skip
				 */
				if ( strlen( $block['blockName'] ) === 0 ) {
					continue;
				}

				/**
				 * If the block is reusable, skip
				 */
				if ( 'core/block' === $block['blockName'] ) {
					continue;
				}

				/**
				 * If block is not in blocks array, push the
				 * blockName into the array.
				 */
				if ( ! in_array( $block['blockName'], array_column( $blocks, 'name' ), true ) ) {
					$block_array = array(
						'name'  => $block['blockName'],
						'posts' => array(),
					);

					array_push( $blocks, $block_array );
				}

				$block_key = find_my_blocks_search_for_block_key( $blocks, 'name', $block['blockName'] );

				if ( ! in_array( $post->ID, array_column( $blocks[ $block_key ]['posts'], 'id' ), true ) ) {
					$blocks[ $block_key ]['posts'][] = array(
						'id'         => $post->ID,
						'title'      => $post->post_title,
						'count'      => 1,
						'isReusable' => 'wp_block' === $post->post_type,
						'postType'   => $post->post_type,
						'post_url'   => get_permalink( $post->ID ),
						'edit_url'   => home_url( '/wp-admin/post.php?post=' . $post->ID . '&action=edit' ),
					);
				} else {
					$post_key = find_my_blocks_search_for_block_key( $blocks[ $block_key ]['posts'], 'id', $post->ID );
					$blocks[ $block_key ]['posts'][ $post_key ]['count']++;
				}
			}
		}

		$data = array(
			'blocks' => $blocks,
		);

		$response = new WP_REST_Response( $data, 200 );
		return $response;
	}
endif;

/**
 * Searches an array for a value.
 *
 * @param array  $array - Array to search through.
 * @param string $field - Key to search.
 * @param string $value - Value to search in key.
 *
 * @return array/boolean
 */
function find_my_blocks_search_for_block_key( $array, $field, $value ) {
	foreach ( $array as $key => $val ) {
		if ( $val[ $field ] === $value ) {
			return $key;
		}
	}
	return false;
}
